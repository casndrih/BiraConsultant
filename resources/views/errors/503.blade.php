<?php
/**
 * Simple 503 Service Unavailable view for maintenance mode.
 * You can customize this as needed – it will be shown to all non‑admin users
 * when the `maintenance_mode` setting is turned on.
 */
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100 dark:bg-gray-900">
<head>
    <meta charset="UTF-8">
    <title>Site Under Maintenance</title>
    <style>
        body {font-family: system-ui, sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0;}
        .container {text-align: center;}
        h1 {font-size: 2rem; margin-bottom: 0.5rem;}
        p {font-size: 1rem; color: #555;}
    </style>
</head>
<body>
<div class="container">
    <h1>503 – Service Unavailable</h1>
    <p>The site is currently undergoing maintenance. Please check back later.</p>
</div>
</body>
</html>
