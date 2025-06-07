<?php
require_once __DIR__ . '/AppController.php';
require_once __DIR__ . '/../utils/LoginSecurity.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/RoleRepository.php';
require_once __DIR__ . '/../repository/TeacherProfileRepository.php';
require_once __DIR__ . '/../repository/LanguageRepository.php';

use utils\LoginSecurity;
use repository\UserRepository;
use repository\RoleRepository;
use repository\TeacherProfileRepository;
use repository\LanguageRepository;

class AdminController extends AppController {

    private $roleRepo;
    private $teacherProfileRepo;
    private $languageRepo;

    public function __construct()
    {
        parent::__construct();
        $this->roleRepo     = new RoleRepository();
        $this->userRepo     = new UserRepository();
        $this->languageRepo = new LanguageRepository();
        $this->teacherProfileRepo = new TeacherProfileRepository();
    }

    public function admin() {
        LoginSecurity::requireLogin();
        if (LoginSecurity::getUserRole() !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            echo "403 Forbidden";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $userId = (int)($_POST['user_id'] ?? 0);
            if ($action === 'delete' && $userId) {
                $this->userRepo->deleteUser($userId);
                $_SESSION['admin_message'] = "User #{$userId} removed.";
                header('Location: /index.php?page=admin');
                exit;
            } elseif ($action === 'toggleRole' && $userId) {
                $newRole = $_POST['new_role'] ?? '';
                if (in_array($newRole, ['student','teacher','admin'], true)) {
                    $this->roleRepo->changeUserRole($userId, $newRole);
                    $_SESSION['admin_message'] = "User #{$userId} role set to {$newRole}.";
                } else {
                    $_SESSION['admin_message'] = "Invalid role specified.";
                }
                header('Location: /index.php?page=admin');
                exit;
            }
        }

        $users = $this->userRepo->getAllUsers();
        if (isset($_SESSION['admin_message'])) {
            $this->messages[] = $_SESSION['admin_message'];
            unset($_SESSION['admin_message']);
        }
        $this->render('adminusers', [
            'users' => $users
        ]);
    }

    public function adminviewuser() {
        LoginSecurity::requireLogin();
        if (LoginSecurity::getUserRole() !== 'admin') {
            header('HTTP/1.1 403 Forbidden');
            echo "403 Forbidden";
            exit;
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            die("Niepoprawne ID użytkownika.");
        }

        $repo = new UserRepository();
        $user = $repo->getUserById($id);
        if (!$user) {
            die("Użytkownik o ID {$id} nie istnieje.");
        }

        if ($user->getRole() === 'teacher') {
            $tpRepo = new TeacherProfileRepository();
            $teacherData = $tpRepo->getTeacherProfile($id);
            $this->render('profileteacher', [
                'user'        => $user,
                'teacherData' => $teacherData
            ]);
        } else {
            $this->render('profilestudent', [
                'user' => $user
            ]);
        }
    }
}
