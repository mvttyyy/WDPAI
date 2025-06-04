<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class SelectionRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function addStudentToTeacher(int $studentId, int $teacherId): bool {
        $sql = "
        INSERT INTO selected_teachers (student_id, teacher_id)
        VALUES ($1, $2)
        ON CONFLICT DO NOTHING
        ";
        $result = pg_query_params($this->db, $sql, [$studentId, $teacherId]);
        return $result !== false;
    }

    public function getStudentsByTeacherId(int $teacherId): array {
        $sql = "
          SELECT
            u.id,
            u.name || ' ' || u.surname AS full_name,
            st.created_at
          FROM selected_teachers st
          JOIN users u ON st.student_id = u.id
          WHERE st.teacher_id = \$1
          ORDER BY u.name, u.surname
        ";
        $result = pg_query_params($this->db, $sql, [$teacherId]);
        return $result
            ? pg_fetch_all($result, PGSQL_ASSOC)
            : [];
    }

    public function getSelectedTeachersByStudentId(int $studentId): array {
        $sql = "
          SELECT
            u.id                               AS teacher_id,
            u.name || ' ' || u.surname         AS full_name,
            tp.photo                           AS photo,
            l.name                             AS language_name,
            t.price                            AS price
          FROM selected_teachers st
          JOIN users u           ON st.teacher_id = u.id
          JOIN teacher_profiles tp ON u.id = tp.user_id
          JOIN teacher_offers t  ON u.id = t.teacher_id
          JOIN languages l       ON t.language_id = l.language_id
          WHERE st.student_id = $1
        ";
        $result = pg_query_params($this->db, $sql, [$studentId]);
        return $result ? pg_fetch_all($result) : [];
    }

    public function getMyTeachers(int $studentId): array
    {
        $sql = '
            SELECT
                teacher_id,
                full_name,
                country,
                photo,
                languages,
                price_per_hour
            FROM public.user_selected_teachers
            WHERE student_id = $1
            ORDER BY full_name
        ';
        $res = pg_query_params($this->db, $sql, [$studentId]);
        return $res ? pg_fetch_all($res, PGSQL_ASSOC) : [];
    }

    public function removeStudentFromTeacher(int $studentId, int $teacherId): bool {
        $sql = "DELETE FROM selected_teachers WHERE student_id = $1 AND teacher_id = $2";
        $result = pg_query_params($this->db, $sql, [$studentId, $teacherId]);
        return $result !== false;
    }
}
