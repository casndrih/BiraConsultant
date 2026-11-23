<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class JobSpreadsheetImporter
{
    private const MAX_DATA_ROWS = 500;
    private const MAX_UNCOMPRESSED_BYTES = 20_971_520;

    private const HEADER_ALIASES = [
        'job_title' => 'title',
        'job_description' => 'description',
        'category_name' => 'category',
        'job_category' => 'category',
        'category_id' => 'category',
        'job_type_name' => 'job_type',
        'employment_type' => 'job_type',
        'job_type_id' => 'job_type',
        'experience' => 'experience_level',
        'experience_level_name' => 'experience_level',
        'experience_level_id' => 'experience_level',
        'application_deadline' => 'deadline',
        'application_instructions' => 'how_to_apply',
    ];

    /**
     * Read an Excel .xlsx workbook or a CSV exported from a spreadsheet.
     * Rows are returned with their original 1-based sheet/CSV row number.
     *
     * @return array{headers: array<int, string>, rows: array<int, array{row: int, data: array<string, string>}>}
     */
    public function read(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $rawRows = match ($extension) {
            'xlsx' => $this->readXlsx($file->getRealPath()),
            'csv' => $this->readCsv($file->getRealPath()),
            default => throw new RuntimeException('Upload an .xlsx Excel workbook or a .csv spreadsheet.'),
        };

        $headerPosition = null;
        foreach ($rawRows as $position => [$rowNumber, $values]) {
            if ($this->hasValues($values)) {
                $headerPosition = $position;
                break;
            }
        }

        if ($headerPosition === null) {
            throw new RuntimeException('The spreadsheet is empty. Add a header row and at least one job row.');
        }

        [$headerRowNumber, $headerValues] = $rawRows[$headerPosition];
        $headers = array_map(fn ($value) => $this->normalizeHeader($value), $headerValues);
        foreach (['title', 'description', 'category', 'job_type', 'experience_level'] as $required) {
            if (!in_array($required, $headers, true)) {
                throw new RuntimeException("The header row must include a '{$required}' column.");
            }
        }

        $rows = [];
        foreach (array_slice($rawRows, $headerPosition + 1) as [$rowNumber, $values]) {
            if (!$this->hasValues($values)) {
                continue;
            }

            if (count($rows) >= self::MAX_DATA_ROWS) {
                throw new RuntimeException('A spreadsheet can contain at most 500 job rows per upload.');
            }

            $data = [];
            foreach ($headers as $column => $header) {
                if ($header === '') {
                    continue;
                }

                $data[$header] = trim((string) ($values[$column] ?? ''));
            }

            $rows[] = ['row' => $rowNumber, 'data' => $data];
        }

        if ($headerRowNumber === 0 || count($rows) === 0) {
            throw new RuntimeException('The spreadsheet has no job rows below its header.');
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /** @return array<int, array{0: int, 1: array<int, string>}> */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException('The CSV file could not be opened.');
        }

        try {
            $firstLine = fgets($handle);
            if ($firstLine === false) {
                return [];
            }

            $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
            rewind($handle);

            $rows = [];
            $rowNumber = 0;
            while (($values = fgetcsv($handle, 0, $delimiter, '"', '\\')) !== false) {
                $rowNumber++;
                $rows[] = [$rowNumber, array_map(fn ($value) => (string) ($value ?? ''), $values)];
                if (count($rows) > self::MAX_DATA_ROWS + 1) {
                    throw new RuntimeException('A spreadsheet can contain at most 500 job rows per upload.');
                }
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }

    /** @return array<int, array{0: int, 1: array<int, string>}> */
    private function readXlsx(string $path): array
    {
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('Excel workbook support is unavailable on this server. Upload a CSV file instead.');
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            throw new RuntimeException('The Excel workbook is invalid or damaged.');
        }

        try {
            if ($zip->numFiles > 512) {
                throw new RuntimeException('The Excel workbook contains too many internal files.');
            }

            $uncompressedBytes = 0;
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $stat = $zip->statIndex($index);
                $uncompressedBytes += (int) ($stat['size'] ?? 0);
                if ($uncompressedBytes > self::MAX_UNCOMPRESSED_BYTES) {
                    throw new RuntimeException('The uncompressed Excel workbook is too large.');
                }
            }

            $workbook = $this->parseXml($zip->getFromName('xl/workbook.xml'));
            $relationships = $this->parseXml($zip->getFromName('xl/_rels/workbook.xml.rels'));
            $sheets = $workbook->xpath('//*[local-name()="sheet"]');
            $firstSheet = $sheets[0] ?? null;
            if (!$firstSheet) {
                throw new RuntimeException('The Excel workbook does not contain a worksheet.');
            }

            $relationshipId = '';
            foreach ($firstSheet->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships') as $name => $value) {
                if ($name === 'id') {
                    $relationshipId = (string) $value;
                    break;
                }
            }

            $sheetPath = null;
            foreach ($relationships->xpath('//*[local-name()="Relationship"]') ?: [] as $relationship) {
                if ((string) $relationship['Id'] !== $relationshipId) {
                    continue;
                }

                $target = ltrim((string) $relationship['Target'], '/');
                if (str_contains($target, '..')) {
                    throw new RuntimeException('The Excel workbook contains an invalid worksheet path.');
                }
                $sheetPath = str_starts_with($target, 'xl/') ? $target : 'xl/' . $target;
                break;
            }

            if (!$sheetPath) {
                throw new RuntimeException('The first worksheet could not be found in the Excel workbook.');
            }

            $sheet = $this->parseXml($zip->getFromName($sheetPath));
            $sharedStrings = [];
            $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
            if ($sharedXml !== false) {
                $sharedRoot = $this->parseXml($sharedXml);
                foreach ($sharedRoot->xpath('//*[local-name()="si"]') ?: [] as $sharedItem) {
                    $parts = $sharedItem->xpath('.//*[local-name()="t"]') ?: [];
                    $sharedStrings[] = implode('', array_map('strval', $parts));
                }
            }

            $rows = [];
            foreach ($sheet->xpath('//*[local-name()="sheetData"]/*[local-name()="row"]') ?: [] as $row) {
                $rowNumber = (int) ($row['r'] ?? (count($rows) + 1));
                $values = [];
                foreach ($row->xpath('./*[local-name()="c"]') ?: [] as $cell) {
                    $reference = (string) ($cell['r'] ?? '');
                    if (!preg_match('/^([A-Z]+)/i', $reference, $matches)) {
                        continue;
                    }
                    $column = $this->columnIndex(strtoupper($matches[1]));
                    if ($column > 63) {
                        throw new RuntimeException('A worksheet may contain no more than 64 columns.');
                    }

                    $type = (string) ($cell['t'] ?? '');
                    if ($type === 'inlineStr') {
                        $texts = $cell->xpath('./*[local-name()="is"]//*[local-name()="t"]') ?: [];
                        $value = implode('', array_map('strval', $texts));
                    } else {
                        $valueNodes = $cell->xpath('./*[local-name()="v"]') ?: [];
                        $value = isset($valueNodes[0]) ? (string) $valueNodes[0] : '';
                        if ($type === 's' && $value !== '') {
                            $value = $sharedStrings[(int) $value] ?? '';
                        }
                    }

                    $values[$column] = $value;
                }

                if ($values !== []) {
                    ksort($values);
                    $lastColumn = max(array_keys($values));
                    $rows[] = [$rowNumber, array_replace(array_fill(0, $lastColumn + 1, ''), $values)];
                    if (count($rows) > self::MAX_DATA_ROWS + 1) {
                        throw new RuntimeException('A spreadsheet can contain at most 500 job rows per upload.');
                    }
                }
            }

            return $rows;
        } finally {
            $zip->close();
        }
    }

    private function parseXml(string|false $contents): SimpleXMLElement
    {
        if ($contents === false || $contents === '') {
            throw new RuntimeException('The Excel workbook is missing required worksheet data.');
        }

        $xml = simplexml_load_string($contents, SimpleXMLElement::class, LIBXML_NONET | LIBXML_COMPACT);
        if ($xml === false) {
            throw new RuntimeException('The Excel workbook contains invalid XML.');
        }

        return $xml;
    }

    private function normalizeHeader(string $header): string
    {
        $header = preg_replace('/^\xEF\xBB\xBF/', '', trim($header)) ?? '';
        $normalized = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '_', $header) ?? '', '_'));

        return self::HEADER_ALIASES[$normalized] ?? $normalized;
    }

    private function hasValues(array $values): bool
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    private function columnIndex(string $letters): int
    {
        $column = 0;
        foreach (str_split($letters) as $letter) {
            $column = ($column * 26) + (ord($letter) - ord('A') + 1);
        }

        return $column - 1;
    }
}
