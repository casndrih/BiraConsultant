<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->prepare('UPDATE biraconsultant_settings SET value = :v WHERE key = :k')->execute([':v'=>'0', ':k'=>'maintenance_mode']);
?>