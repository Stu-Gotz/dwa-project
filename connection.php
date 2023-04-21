<!-- Database Connection -->
<?php
const DB_HOST = '127.0.0.1';
const DB_NAME = 'auth';
const DB_USER = 'root';
const DB_PASSWORD = '';

function db(): PDO
{
    static $pdo;

    if (!$pdo) {
        return new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
            DB_USER,
            DB_PASSWORD,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
    return $pdo;
}

?>

