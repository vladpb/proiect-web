<?php

class UserManager
{
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function registerUser($username, $email, $password) {
        // Logic for registering a user
    }

    public function loginUser($username, $password) {
        // Logic for logging in a user
    }


}