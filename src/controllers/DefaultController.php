<?php
require_once 'src/controllers/AppController.php';
require_once 'src/utils/LoginSecurity.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/repository/SelectionRepository.php';
require_once 'src/repository/SearchRepository.php';
require_once 'src/repository/TeacherProfileRepository.php';
require_once 'src/repository/RoleRepository.php';
require_once 'src/repository/LanguageRepository.php';

use utils\LoginSecurity;
use repository\UserRepository;
use repository\SelectionRepository;
use repository\SearchRepository;
use repository\TeacherProfileRepository;
use repository\RoleRepository;
use repository\LanguageRepository;

class DefaultController extends AppController {

    public function index() {
        $this->render('index');
    }

    public function login() {
        $this->render('login');
    }

    public function signup() {
        $this->render('signup');
    }

    public function profile() {
        LoginSecurity::requireLogin();
        $repo = new UserRepository();
        $tpRepo  = new TeacherProfileRepository();
        $selectionRepo = new SelectionRepository();
        $user = $repo->getUserById(LoginSecurity::getUserId());
    
        if ($user->getRole() === 'teacher') {
            $teacherData = $tpRepo->getTeacherProfile($user->getId());
            return $this->render('profileteacher', [
                'user'        => $user,
                'teacherData' => $teacherData
            ]);
        } else {
            $selectedTeachers = $selectionRepo->getSelectedTeachersByStudentId($user->getId());

            return $this->render('profilestudent', [
                'user'             => $user,
                'selectedTeachers' => $selectedTeachers
        ]);
        }
    }

    public function findTeacher() {
        LoginSecurity::requireLogin();
        $repo      = new UserRepository();
        $searchRepo = new SearchRepository();
        $lanRepo = new LanguageRepository();
        $languages = $lanRepo->getLanguages();

        $langId   = (int)($_GET['language_id'] ?? 0);
        $query    = trim($_GET['q'] ?? '');
        $teachers = [];

        if ($query !== '') {
            $teachers = $searchRepo->searchTeachersByQuery($query);
        } elseif ($langId > 0) {
            $teachers = $searchRepo->searchTeachersByLanguageId($langId);
        }

        return $this->render('findteacher', [
            'languages'    => $languages,
            'selectedLang' => $langId,
            'teachers'     => $teachers
        ]);
    }

    public function becomeTeacher() {
        LoginSecurity::requireLogin();
        $userId = LoginSecurity::getUserId();
        $repo   = new UserRepository();
        $tpRepo  = new TeacherProfileRepository();
        $roleRepo  = new RoleRepository();
        $lanRepo  = new LanguageRepository();

        $languages = $lanRepo->getLanguages();
        $this->messages = [];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bio      = trim($_POST['bio'] ?? '');
            $langs    = $_POST['languages'] ?? [];
            $priceRaw = $_POST['price'] ?? '';
            $price    = is_numeric($priceRaw) ? (float)$priceRaw : 0.0;
    
            if (empty($bio) || empty($langs) || $price <= 0) {
                $this->messages[] = "Fill out your bio, select at least one language and provide a valid price.";
            } else {
                $photoPath = null;
                if (!empty($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $tmp     = $_FILES['photo']['tmp_name'];
                    $ext     = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $name    = uniqid('teach_') . '.' . $ext;
                    $destDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    $dest = $destDir . $name;
                    move_uploaded_file($tmp, $dest);
                    $photoPath = '/public/uploads/' . $name;
                }

                $offers = [];
                foreach ($langs as $langId) {
                    $offers[(int)$langId] = $price;
                }

                $ok = $tpRepo->upsertTeacherProfileAndOffers($userId, $bio, $photoPath, $offers);
                if ($ok) {
                    $roleRepo->assignUserRole($userId, 2);
                    header('Location: /index.php?page=profile');
                    exit;
                }
                $this->messages[] = "Something went wrong, try again.";
            }

            $selectedLangs = array_map('intval', $langs);
        } else {
            $profile = $tpRepo->getTeacherProfile($userId) ?? [];
            $offers  = $tpRepo->getTeacherOffersArray($userId);
    
            $bio           = $profile['bio']   ?? '';
            $photoPreview  = $profile['photo'] ?? '';
            $selectedLangs = array_keys($offers);
            $price         = count($offers) ? reset($offers) : '';
        }

        return $this->render('becometeacher', [
            'languages'     => $languages,
            'messages'      => $this->messages,
            'bio'           => $bio,
            'photoPreview'  => $photoPreview ?? '',
            'selectedLangs' => $selectedLangs ?? [],
            'price'         => $price ?? '',
        ]);
    }
    

    public function mystudents() {
        LoginSecurity::requireLogin();

        $currentUserId = LoginSecurity::getUserId();
        $currentRole = LoginSecurity::getUserRole();
        $selectionRepo = new SelectionRepository();

        $teacherId = isset($_GET['teacher_id']) ? (int)$_GET['teacher_id'] : 0;

        if ($currentRole === 'admin' && !empty($_POST['remove_student_id']) && !empty($_POST['teacher_id'])) {
            $removeStudentId = (int)$_POST['remove_student_id'];
            $removeTeacherId = (int)$_POST['teacher_id'];
            $selectionRepo->removeStudentFromTeacher($removeStudentId, $removeTeacherId);
            header("Location: /index.php?page=mystudents&teacher_id=$removeTeacherId");
            exit;
        }

        if ($currentRole === 'teacher' && (!$teacherId || $teacherId !== $currentUserId)) {
            $teacherId = $currentUserId;
        }

        if (!$teacherId) {
            $students = [];
        } else {
            $students = $selectionRepo->getStudentsByTeacherId($teacherId);
        }

        return $this->render('mystudents', [
            'students' => $students,
            'teacherId' => $teacherId,
            'currentRole' => $currentRole,
        ]);
    }
    public function viewuser() {
    LoginSecurity::requireLogin();

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        die('Invalid teacher ID');
    }

    $repo = new UserRepository();
    $tpRepo  = new TeacherProfileRepository();
    $selectionRepo = new SelectionRepository();
    $user = $repo->getUserById($id);

    if (!$user || $user->getRole() !== 'teacher') {
        http_response_code(404);
        die('Teacher not found');
    }

    $profile = $tpRepo->getTeacherProfile($id);
    $offers  = $tpRepo->getTeacherOffers($id);

    $selectedTeachers = [];
    if (LoginSecurity::getUserRole() === 'student') {
        $currentUser = LoginSecurity::getUserId();
        $selectedTeachers = array_map(
            fn($row) => (int)$row['teacher_id'],
            $selectionRepo->getSelectedTeachersByStudentId($currentUser)
        );
    }

    return $this->render('viewteacher', [
        'user'             => $user,
        'profile'          => $profile,
        'offers'           => $offers,
        'selectedTeachers' => $selectedTeachers
    ]);
}

    public function chooseteacher() {
        LoginSecurity::requireLogin();

        if (LoginSecurity::getUserRole() !== 'student') {
            http_response_code(403);
            die('Forbidden');
        }
    
        $studentId = LoginSecurity::getUserId();
        $teacherId = (int)($_POST['teacher_id'] ?? 0);
        if ($teacherId <= 0) {
            http_response_code(400);
            die('Invalid teacher ID');
        }
    
        $selectionRepo = new SelectionRepository();
        $ok   = $selectionRepo->addStudentToTeacher($studentId, $teacherId);

        header('Location: /index.php?page=viewuser'
             . '&id='     . $teacherId
             . '&chosen=1'
        );
        exit;
    }
    
    public function myteachers() {
        LoginSecurity::requireLogin();
        if (LoginSecurity::getUserRole() !== 'student') {
            http_response_code(403);
            die('Forbidden');
        }
    
        $studentId = LoginSecurity::getUserId();
        $selectionRepo = new SelectionRepository();

        $teachers = $selectionRepo->getMyTeachers(LoginSecurity::getUserId());
        return $this->render('myteachers', ['teachers' => $teachers]);

    }

    public function profilestudent() {
    LoginSecurity::requireLogin();

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        die('Invalid student ID');
    }

    $repo = new UserRepository();
    $user = $repo->getUserById($id);

    if (!$user || $user->getRole() !== 'student') {
        http_response_code(404);
        die('Student not found');
    }

    return $this->render('profilestudent', [
        'user' => $user
    ]);
}

public function removeTeacher() {
    \utils\LoginSecurity::requireLogin();
    header('Content-Type: application/json');
    if (\utils\LoginSecurity::getUserRole() !== 'student') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Forbidden']);
        return;
    }

    $studentId = \utils\LoginSecurity::getUserId();
    $teacherId = (int)($_POST['teacher_id'] ?? 0);
    if ($teacherId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Invalid teacher ID']);
        return;
    }

    $selRepo = new \repository\SelectionRepository();
    $ok = $selRepo->removeStudentFromTeacher($studentId, $teacherId);

    // usuń też czat
    $chatRepo = new \repository\ChatRepository();
    $chatRepo->deleteChat($studentId, $teacherId);

    echo json_encode(['success' => $ok]);
}
}
