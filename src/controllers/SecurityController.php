<?php
require_once 'src/controllers/AppController.php';
require_once 'src/models/User.php';
require_once 'src/models/Signup.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/utils/LoginSecurity.php';

use models\User;
use models\Signup;
use repository\UserRepository;
use utils\LoginSecurity;

class SecurityController extends AppController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('login');
        }

        $email    = trim($_POST['email']);
        $password = $_POST['password'];
        $repo     = new UserRepository();

        if ($repo->comparePassword($email, $password)) {
            $user = $repo->getUser($email);
            LoginSecurity::login($user->getId(), $user->getEmail(), $user->getRole());
            header('Location: /index.php?page=profile');
            exit;
        }

        $this->messages[] = 'Invalid email or password.';
        return $this->render('login', ['messages' => $this->messages]);
    }

    public function logout() {
        LoginSecurity::logout();
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->render('signup');
        }

        $name     = trim($_POST['name']);
        $surname  = trim($_POST['surname']);
        $country  = trim($_POST['country']);
        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $signup = new Signup($name, $surname, $country, $email, $hashedPassword, 1);

        if ($signup->register()) {
            header('Location: /index.php?page=login&success=1');
            exit;
        }

        $this->messages[] = "This email is already registered.";
        return $this->render('signup');
    }
}
