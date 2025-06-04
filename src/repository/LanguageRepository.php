<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class LanguageRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getLanguages(): array {
        $res = pg_query($this->db, "SELECT language_id, name FROM languages ORDER BY name");
        return $res
            ? pg_fetch_all($res, PGSQL_ASSOC)
            : [];
    }
}
