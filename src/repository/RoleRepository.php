<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class RoleRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function changeUserRole(int $id, string $newRole): bool {
        $sql = "UPDATE users SET role_id=(SELECT id FROM roles WHERE name=\$2) WHERE id=\$1";
        $res = pg_query_params($this->db, $sql, [$id, $newRole]);
        return (bool)$res;
    }

    public function assignUserRole(int $userId, int $roleId): bool {
        $res = pg_query_params(
            $this->db,
            "UPDATE public.users SET role_id = \$1 WHERE id = \$2",
            [$roleId, $userId]
        );
        return $res !== false && pg_affected_rows($res) > 0;
    }
}
