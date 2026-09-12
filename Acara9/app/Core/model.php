<?php
namespace App\Core;
use PDO;
use PDOException;

class Model
{
    protected static ?PDO $db = null;
    public static function getDB(): PDO
    {
        if (self::$db === null) {
            $config = require __DIR__ . '/../../config/database.php';

            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                self::$db = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                die("Koneksi Database Gagal: " . $e->getMessage());
            }
        }

        return self::$db;
    }
}