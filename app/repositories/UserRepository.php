<?php
class UserRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Save new user
    public function save(User $user) {
        $this->db->query(
            "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)"
        );
        $this->db->bind(':username', $user->getUsername());
        $this->db->bind(':email', $user->getEmail());
        $this->db->bind(':password', $user->getPassword());

        return $this->db->execute();
    }

    // Find user by email (return User object or null)
    public function findByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($row) {
            return new User(
                $row['username'],
                $row['email'],
                $row['password'],
                $row['id']
            );
        }
        return null;
    }

    // Find user by ID
    public function findById($id) {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        $row = $this->db->single();

        if ($row) {
            return new User(
                $row['username'],
                $row['email'],
                $row['password'],
                $row['id']
            );
        }
        return null;
    }
}
