<?php
require_once 'src/controllers/AppController.php';
require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/SearchController.php';
require_once 'src/controllers/AdminController.php';
require_once 'src/controllers/ChatController.php';
require_once 'Routing.php';

Routing::get('index',         'DefaultController');

// autoryzacja
Routing::get('login',         'DefaultController');
Routing::post('login',        'SecurityController');
Routing::get('logout',        'SecurityController');
Routing::get('signup',        'DefaultController');
Routing::post('signup',       'SecurityController');

// navbar
Routing::get('search',        'SearchController');

// profil
Routing::get('profile',       'DefaultController');
Routing::get('profilestudent','DefaultController');

// wyszukiwarka
Routing::get('findteacher',   'DefaultController');
Routing::get('becometeacher', 'DefaultController');
Routing::post('becometeacher','DefaultController');
Routing::post('chooseteacher','DefaultController');

// relacje
Routing::get('mystudents',    'DefaultController');
Routing::get('myteachers',    'DefaultController');

// admin
Routing::get('admin',         'AdminController');
Routing::post('admin',        'AdminController');
Routing::get('viewuser',      'DefaultController');
Routing::get('adminviewuser', 'AdminController');

// chat
Routing::get('chat',          'ChatController');
Routing::post('chat',         'ChatController');
Routing::get('mychats',       'ChatController');
Routing::get('chat-messages', 'ChatController', 'messages');
Routing::post('chat-message', 'ChatController', 'postMessage');
Routing::post('remove-teacher', 'DefaultController', 'removeTeacher');

$page = $_GET['page'] ?? 'index';
Routing::run($page);