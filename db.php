<?php
$databaseUrl = getenv("DATABASE_URL");

if (!$databaseUrl) {
    die("DATABASE_URL not set");
}

$db = parse_url($databaseUrl);

$host = $db["host"];
$port = $db["port"];
$user = $db["user"];
$pass = $db["pass"];
$name = ltrim($db["path"], "/");

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$name",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
