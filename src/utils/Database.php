<?php
namespace utils;

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            $host   = getenv('DB_HOST')   ?: 'db';
            $dbname = getenv('DB_NAME')   ?: 'studywise';
            $user   = getenv('DB_USER')   ?: 'postgres';
            $pass   = getenv('DB_PASS')   ?: 'postgres';

            $connStr = sprintf(
              'host=%s dbname=%s user=%s password=%s',
              $host, $dbname, $user, $pass
            );

            $conn = @pg_connect($connStr);
            if (!$conn) {
                throw new \RuntimeException(
                  "Can't connect to the database: $connStr"
                );
            }
            self::$connection = $conn;
        }
        return self::$connection;
    }
}
