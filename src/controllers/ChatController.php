<?php
require_once 'src/controllers/AppController.php';
require_once 'src/repository/ChatRepository.php';
require_once 'src/utils/LoginSecurity.php';

use repository\ChatRepository;
use utils\LoginSecurity;

class ChatController extends AppController {
    public function chat() {
        LoginSecurity::requireLogin();
        $userId = LoginSecurity::getUserId();
        $role   = LoginSecurity::getUserRole();

        $otherId = (int)($_GET['user_id'] ?? 0);
        if ($otherId <= 0) {
            die('Nieprawidłowy użytkownik');
        }

        if ($role === 'student') {
            $studentId = $userId;
            $teacherId = $otherId;
        } elseif ($role === 'teacher') {
            $studentId = $otherId;
            $teacherId = $userId;
        } else {
            die('Czat tylko dla studentów i nauczycieli');
        }

        $repo   = new ChatRepository();
        $chatId = $repo->getOrCreateChat($studentId, $teacherId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $msg = trim($_POST['message'] ?? '');
            if ($msg !== '') {
                $repo->addMessage($chatId, $userId, $msg);
            }
            header("Location: /index.php?page=chat&user_id=$otherId");
            exit;
        }

        $messages = $repo->getChatMessages($chatId);
        $this->render('chat', [
            'messages' => $messages,
            'otherId'  => $otherId,
            'userId'   => $userId
        ]);
    }

    public function mychats() {
        LoginSecurity::requireLogin();
        $userId = LoginSecurity::getUserId();
        $role   = LoginSecurity::getUserRole();

        $repo  = new ChatRepository();
        $chats = $repo->getUserChatOverview($userId, $role);

        $db  = pg_connect("host=db dbname=studywise user=postgres password=postgres");
        $res = pg_query_params($db, "SELECT user_chat_count($1) AS chat_count", [$userId]);
        $chatCount = ($res && $row = pg_fetch_assoc($res))
                   ? (int)$row['chat_count']
                   : 0;

        $this->render('mychats', [
            'chats'     => $chats,
            'userId'    => $userId,
            'role'      => $role,
            'chatCount' => $chatCount
        ]);
    }

    public function messages() {
        $chatId = (int)($_GET['chat_id'] ?? 0);
        $msgs   = (new ChatRepository())->getMessages($chatId);
        header('Content-Type: application/json');
        echo json_encode($msgs);
        exit;
    }

    public function postMessage() {
        $data = json_decode(file_get_contents('php://input'), true);
        $ok   = (new ChatRepository())->addMessage(
            (int)$data['chat_id'],
            (int)$data['user_id'],
            $data['text']
        );
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
        exit;
    }
}