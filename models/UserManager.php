<?php
class UserManager extends AbstractManager {

    public function findByEmail(string $email): ?User {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['id'],
                $data['pseudo'],
                $data['email'],
                $data['password'],
                $data['avatar'],
                $data['created_at'],
                $data['updated_at']
            );
        }
        return null;
    }

    public function getUserById(int $id): ?User {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User(
                $data['id'],
                $data['pseudo'],
                $data['email'],
                $data['password'],
                $data['avatar'],
                $data['created_at'],
                $data['updated_at']
            );
        }
        return null;
    }

    public function registerUser(User $user): bool {
        $data = [
            'pseudo'     => $user->getPseudo(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'avatar'     => $user->getAvatar(),
            'created_at' => $user->getCreatedAt(),
            'updated_at' => $user->getUpdatedAt()
        ];
        return $this->add('users', $data);
    }

    public function updateUserInfo(User $user): bool {
        $data = [
            'pseudo'     => $user->getPseudo(),
            'email'      => $user->getEmail(),
            'password'   => $user->getPassword(),
            'avatar'     => $user->getAvatar(),
            'updated_at' => $user->getUpdatedAt()
        ];
        return $this->update('users', $data, $user->getId());
    }
}
