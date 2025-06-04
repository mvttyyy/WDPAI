<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class AuthRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function emailExists(string $email): bool {
        $res = pg_query_params($this->db,
            "SELECT 1 FROM auth WHERE email = $1",
            [$email]
        );
        return (bool)$res && pg_num_rows($res) > 0;
    }

    public function insertAuth(string $email, string $passwordHash): ?int {
        $res = pg_query_params($this->db,
            "INSERT INTO auth(email,password) VALUES($1,$2) RETURNING id",
            [$email, $passwordHash]
        );
        if ($res && pg_num_rows($res) === 1) {
            return (int)pg_fetch_result($res, 0, 'id');
        }
        return null;
    }
}
