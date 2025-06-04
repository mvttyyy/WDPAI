<?php
namespace repository;

require_once __DIR__ . '/../utils/Database.php';
require_once __DIR__ . '/AuthRepository.php';
require_once __DIR__ . '/RoleRepository.php';
require_once __DIR__ . '/SelectionRepository.php';
require_once __DIR__ . '/TeacherProfileRepository.php';
require_once __DIR__ . '/LanguageRepository.php';

use models\User;
use repository\AuthRepository;
use repository\RoleRepository;
use repository\SelectionRepository;
use repository\TeacherProfileRepository;
use repository\LanguageRepository;
use utils\Database;

class UserRepository {
    private $db;
    private $authRepo;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->authRepo = new AuthRepository();
        $this->roleRepo = new RoleRepository();
    }

    public function getUser(string $email): ?User {
        $sql = "
            SELECT
              u.id,
              u.name,
              u.surname,
              u.country,
              a.email,
              a.password  AS password_hash,
              r.name      AS role
            FROM users u
            JOIN auth a   ON a.id = u.auth_id
            JOIN roles r  ON r.id = u.role_id
            WHERE a.email = \$1
        ";
        $res = pg_query_params($this->db, $sql, [$email]);
        if ($row = pg_fetch_assoc($res)) {
            return new User(
                $row['email'],
                $row['password_hash'],
                $row['role'],
                (int)$row['id'],
                $row['name'],
                $row['surname'],
                $row['country']
            );
        }
        return null;
    }

    public function getUserById(int $id): ?User {
        $sql = "
            SELECT
              u.id,
              u.name,
              u.surname,
              u.country,
              a.email,
              a.password  AS password_hash,
              r.name      AS role
            FROM users u
            JOIN auth a   ON a.id = u.auth_id
            JOIN roles r  ON r.id = u.role_id
            WHERE u.id = \$1
        ";
        $res = pg_query_params($this->db, $sql, [$id]);
        if ($row = pg_fetch_assoc($res)) {
            return new User(
                $row['email'],
                $row['password_hash'],
                $row['role'],
                (int)$row['id'],
                $row['name'],
                $row['surname'],
                $row['country']
            );
        }
        return null;
    }
    
    public function register(
        string $name,
        string $surname,
        string $country,
        string $email,
        string $passwordHash,
        int $roleId
    ): bool {
        if ($this->authRepo->emailExists($email)) {
            return false;
        }

        $authId = $this->authRepo->insertAuth($email, $passwordHash);
        if ($authId === null) {
            return false;
        }

        $res = pg_query_params(
            $this->db,
            "INSERT INTO users (name, surname, country, role_id, auth_id)
             VALUES ($1, $2, $3, $4, $5)",
            [$name, $surname, $country, $roleId, $authId]
        );

        return (bool)$res;
    }

    public function comparePassword(string $email, string $plainPassword): bool {
        $user = $this->getUser($email);
        return $user && password_verify($plainPassword, $user->getPasswordHash());
    }

    public function getAllUsers(): array {
        $sql="SELECT u.id,u.name,u.surname,u.country,a.email,r.name AS role
               FROM users u
               JOIN auth a ON a.id=u.auth_id
               JOIN roles r ON r.id=u.role_id
               ORDER BY u.id";
        $res=pg_query($this->db,$sql);
        return $res?pg_fetch_all($res,PGSQL_ASSOC):[];
    }

    public function deleteUser(int $id): bool {
        return (bool)pg_query_params($this->db,"DELETE FROM users WHERE id=\$1",[$id]);
    }
}
