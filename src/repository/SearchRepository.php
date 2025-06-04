<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class SearchRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function searchTeachersByLanguageId(int $languageId): array {
        $sql = "
          SELECT 
            u.id              AS teacher_id,
            u.name || ' ' || u.surname          AS full_name,
            u.country,
            tp.photo,
            l.name            AS language_name,
            tof.price
          FROM teacher_offers tof
          JOIN teacher_profiles tp ON tp.user_id     = tof.teacher_id
          JOIN users u             ON u.id           = tp.user_id
          JOIN roles r             ON r.id           = u.role_id
          JOIN languages l         ON l.language_id  = tof.language_id
          WHERE r.name   = 'teacher'
            AND l.language_id = \$1
          ORDER BY full_name
        ";
        $res = pg_query_params($this->db, $sql, [$languageId]);
        $out = [];
        while ($row = pg_fetch_assoc($res)) {
            $out[] = [
              'teacher_id'    => (int)$row['teacher_id'],
              'full_name'     => $row['full_name'],
              'country'       => $row['country'],
              'photo'         => $row['photo'],
              'language_name' => $row['language_name'],
              'price'         => (float)$row['price'],
            ];
        }
        return $out;
    }

    public function searchTeachersByQuery(string $q): array {
        $pattern = '%' . $q . '%';
        $sql = "
            SELECT
              u.id                             AS teacher_id,
              u.name || ' ' || u.surname       AS full_name,
              u.country,
              l.name                           AS language_name,
              t.price,
              tp.photo
            FROM users u
            JOIN teacher_profiles tp ON u.id = tp.user_id
            JOIN teacher_offers t     ON u.id = t.teacher_id
            JOIN languages l          ON t.language_id = l.language_id
            WHERE u.role_id = 2  -- tylko nauczyciele
              AND (
                   u.name    ILIKE \$1
                OR u.surname ILIKE \$1
                OR l.name    ILIKE \$1
              )
        ";

        $res = pg_query_params($this->db, $sql, [$pattern]);
        if (!$res) {
            return [];
        }

        $teachers = [];
        while ($row = pg_fetch_assoc($res)) {
            $teachers[] = [
              'teacher_id'    => (int)$row['teacher_id'],
              'full_name'     => $row['full_name'],
              'country'       => $row['country'],
              'language_name'=> $row['language_name'],
              'price'         => (float)$row['price'],
              'photo'         => $row['photo'],
            ];
        }
        return $teachers;
    }
}
