<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare('INSERT INTO biraconsultant_settings (key, value) VALUES (:k, :v)');
$stmt->execute([':k' => 'maintenance_mode', ':v' => '1']);
echo "Inserted\n";
?>