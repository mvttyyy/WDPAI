<?php
require_once 'src/controllers/AppController.php';
require_once 'src/utils/LoginSecurity.php';
require_once 'src/repository/UserRepository.php';
require_once 'src/repository/SearchRepository.php';

use utils\LoginSecurity;
use repository\UserRepository;
use repository\SearchRepository;

class SearchController extends AppController {

    public function search() {
        LoginSecurity::requireLogin();
        $searchRepo = new SearchRepository();
        $query   = trim($_GET['q'] ?? '');
        $teachers = [];

        if ($query !== '') {
            $teachers = $searchRepo->searchTeachersByQuery($query);
        }

        return $this->render('searchresults', [
            'query'    => $query,
            'teachers' => $teachers
        ]);
    }
}
