<?php
namespace models;

class User {
    private int    $id;
    private string $email;
    private string $passwordHash;
    private string $role;
    private string $name;
    private string $surname;
    private string $country;

    public function __construct(
        string $email,
        string $passwordHash,
        string $roleName,
        int    $id,
        string $name = '',
        string $surname = '',
        string $country = ''
    ) {
        $this->email        = $email;
        $this->passwordHash = $passwordHash;
        $this->role         = $roleName;
        $this->id           = $id;
        $this->name         = $name;
        $this->surname      = $surname;
        $this->country      = $country;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSurname(): string {
        return $this->surname;
    }

    public function getCountry(): string {
        return $this->country;
    }

    public function getFullName(): string {
        return trim("{$this->name} {$this->surname}");
    }
}
