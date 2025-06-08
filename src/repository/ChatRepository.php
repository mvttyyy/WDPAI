<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';

use utils\Database;

class ChatRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getOrCreateChat(int $studentId, int $teacherId): int {
        $sql = "SELECT id FROM chats WHERE student_id = $1 AND teacher_id = $2";
        $res = pg_query_params($this->db, $sql, [$studentId, $teacherId]);
        if ($row = pg_fetch_assoc($res)) {
            return (int)$row['id'];
        }
        $res = pg_query_params(
            $this->db,
            "INSERT INTO chats (student_id, teacher_id) VALUES ($1, $2) RETURNING id",
            [$studentId, $teacherId]
        );
        return (int)pg_fetch_result($res, 0, 'id');
    }

    public function getChatMessages(int $chatId): array {
        $sql = "SELECT sender_id, message, sent_at FROM chat_messages WHERE chat_id = $1 ORDER BY sent_at ASC";
        $res = pg_query_params($this->db, $sql, [$chatId]);
        return $res ? pg_fetch_all($res, PGSQL_ASSOC) : [];
    }

    public function addMessage(int $chatId, int $senderId, string $message): bool {
        $sql = "INSERT INTO chat_messages (chat_id, sender_id, message) VALUES ($1, $2, $3)";
        $res = pg_query_params($this->db, $sql, [$chatId, $senderId, $message]);
        return $res !== false;
    }

    public function getChatsForStudent(int $studentId): array {
    $sql = "SELECT c.id AS chat_id, t.id AS user_id, t.name, t.surname
            FROM chats c
            JOIN users t ON c.teacher_id = t.id
            WHERE c.student_id = $1";
    $res = pg_query_params($this->db, $sql, [$studentId]);
    return $res ? pg_fetch_all($res, PGSQL_ASSOC) : [];
    }

    public function getChatsForTeacher(int $teacherId): array {
        $sql = "SELECT c.id AS chat_id, s.id AS user_id, s.name, s.surname
                FROM chats c
                JOIN users s ON c.student_id = s.id
                WHERE c.teacher_id = $1";
        $res = pg_query_params($this->db, $sql, [$teacherId]);
        return $res ? pg_fetch_all($res, PGSQL_ASSOC) : [];
    }

    public function getUserChatOverview(int $userId, string $role): array {
        if ($role === 'student') {
            $sql = "SELECT * FROM user_chat_overview WHERE student_id = $1";
        } else if ($role === 'teacher') {
            $sql = "SELECT * FROM user_chat_overview WHERE teacher_id = $1";
        } else {
            return [];
        }
        $res = pg_query_params($this->db, $sql, [$userId]);
        return $res ? pg_fetch_all($res, PGSQL_ASSOC) : [];
    }

    public function deleteChat(int $studentId, int $teacherId): bool {
        $sql = "DELETE FROM chats WHERE student_id = \$1 AND teacher_id = \$2";
        $res = pg_query_params($this->db, $sql, [$studentId, $teacherId]);
        return $res !== false;
    }
}