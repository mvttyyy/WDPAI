<?php
namespace models;

use repository\AuthRepository;
use repository\UserRepository;
use utils\Database;

class Signup {
    private $name;
    private $surname;
    private $country;
    private $email;
    private $passwordHash;
    private $roleId;
    private $db;

    public function __construct(
        string $name,
        string $surname,
        string $country,
        string $email,
        string $passwordHash,
        int $roleId
    ) {
        $this->name         = $name;
        $this->surname      = $surname;
        $this->country      = $country;
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->roleId       = $roleId;
        $this->db = Database::getConnection();
    }

    private function emailExists(): bool {
        $sql = "SELECT 1 FROM auth WHERE email = $1";
        $res = pg_query_params($this->db, $sql, [$this->email]);
        return $res && pg_num_rows($res) > 0;
    }

    public function register(): bool {
        pg_query($this->db, 'BEGIN');

        if ($this->emailExists()) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }

        $sqlAuth = "INSERT INTO auth (email, password) VALUES ($1, $2) RETURNING id";
        $resAuth = pg_query_params(
            $this->db,
            $sqlAuth,
            [$this->email, $this->passwordHash]
        );
        if (!$resAuth || pg_num_rows($resAuth) === 0) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }
        $authId = (int)pg_fetch_assoc($resAuth)['id'];

        $sqlUser = "INSERT INTO users (name, surname, country, auth_id, role_id)
                    VALUES ($1, $2, $3, $4, $5) RETURNING id";
        $resUser = pg_query_params(
            $this->db,
            $sqlUser,
            [$this->name, $this->surname, $this->country, $authId, $this->roleId]
        );
        if (!$resUser || pg_num_rows($resUser) === 0) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }
        $userId = (int)pg_fetch_assoc($resUser)['id'];

        $sqlProfile = "INSERT INTO student_profiles (user_id, created_at) VALUES ($1, now())";
        $resProfile = pg_query_params($this->db, $sqlProfile, [$userId]);
        if (!$resProfile) {
            pg_query($this->db, 'ROLLBACK');
            return false;
        }

        pg_query($this->db, 'COMMIT');
        return true;
    }
}
