<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class TeacherProfileRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getTeacherProfile(int $userId): array {
        $res1 = pg_query_params(
            $this->db,
            "SELECT bio, photo FROM teacher_profiles WHERE user_id = \$1",
            [$userId]
        );
        $row1 = pg_fetch_assoc($res1) ?: ['bio' => '', 'photo' => null];

        $res2 = pg_query_params(
            $this->db,
            "SELECT l.name AS language_name, tof.price
               FROM teacher_offers tof
               JOIN languages l ON l.language_id = tof.language_id
              WHERE tof.teacher_id = \$1
              ORDER BY l.name",
            [$userId]
        );
        $offers = [];
        while ($r = pg_fetch_assoc($res2)) {
            $offers[] = [
                'language_name' => $r['language_name'],
                'price'         => (float)$r['price'],
            ];
        }

        return [
            'bio'    => $row1['bio'],
            'photo'  => $row1['photo'],
            'offers' => $offers,
        ];
    }

    public function upsertTeacherProfileAndOffers(
        int    $userId,
        string $bio,
        ?string $photoPath,
        array  $offers
    ): bool {
        pg_query($this->db, 'BEGIN');

        $sql = "
          INSERT INTO teacher_profiles (user_id, bio, photo)
          VALUES (\$1, \$2, \$3)
          ON CONFLICT (user_id) DO UPDATE
            SET bio   = EXCLUDED.bio,
                photo = COALESCE(EXCLUDED.photo, teacher_profiles.photo)
        ";
        $res = pg_query_params($this->db, $sql, [$userId, $bio, $photoPath]);
        if (!$res) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }

        $del = pg_query_params(
            $this->db,
            "DELETE FROM teacher_offers WHERE teacher_id = \$1",
            [$userId]
        );
        if (!$del) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }

        foreach ($offers as $langId => $price) {
            $ins = pg_query_params(
              $this->db,
              "INSERT INTO teacher_offers (teacher_id, language_id, price) VALUES (\$1, \$2, \$3)",
              [$userId, $langId, $price]
            );
            if (!$ins) {
                pg_query($this->db, 'ROLLBACK');
                return false;
            }
        }

        pg_query($this->db, 'COMMIT');
        return true;
    }

    public function getTeacherOffers(int $teacherId): array {
        $sql = "
          SELECT
            l.name   AS language_name,
            t.price
          FROM teacher_offers t
          JOIN languages l ON t.language_id = l.language_id
          WHERE t.teacher_id = \$1
        ";
        $res = pg_query_params($this->db, $sql, [$teacherId]);
        if (!$res) {
            return [];
        }
        return pg_fetch_all($res, PGSQL_ASSOC) ?: [];
    }

    public function getTeacherOffersArray(int $teacherId): array {
        $res = pg_query_params(
            $this->db,
            "SELECT language_id, price FROM teacher_offers WHERE teacher_id = \$1",
            [$teacherId]
        );
        if (!$res) {
            return [];
        }
        $out = [];
        while ($row = pg_fetch_assoc($res)) {
            $out[(int)$row['language_id']] = (float)$row['price'];
        }
        return $out;
    }
}
