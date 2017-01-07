<?php 

namespace App\Repository;

use PDO;
use App\Models\User;

class UserRepository extends Repository
{
    public function find($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "User", users.* 
             FROM users 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Set the fetchmode to populate an instance of 'User'
        // This enables us to use the following:
        //     $user = $repository->find(1234);
        //     echo $user->firstname;
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch();
    }
    public function auth($email, $password)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "User", users.* 
             FROM users 
             WHERE email = :email and password = :password
        ');
        $stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $password);
        $stmt->execute();        

        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $stmt->fetch();
    }	
    public function findAll()
    {
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM users
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        
        // fetchAll() will do the same as above, but we'll have an array. ie:
        //    $users = $repository->findAll();
        //    echo $users[0]->firstname;
        return $stmt->fetchAll();
    }
    public function save(User $user)
    {
        // If the ID is set, we're updating an existing record
        if (isset($user->id)) {
            return $this->update($user);
        }
        $stmt = $this->getConnection()->prepare('
            INSERT INTO users 
                (email, firstname, lastname, birth_date, password) 
            VALUES 
                (:email, :firstname , :lastname, :birth_date, :password)
        ');
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':firstname', $user->firstname);
        $stmt->bindParam(':lastname', $user->lastname);
        $stmt->bindParam(':birth_date', $user->birth_date);
		$stmt->bindParam(':password', $user->password);
        return $stmt->execute();
    }
    public function update(User $user)
    {
        if (!isset($user->id)) {
            // We can't update a record unless it exists...
            throw new \LogicException(
                'Cannot update user that does not yet exist in the database.'
            );
        }
        $stmt = $this->getConnection()->prepare('
            UPDATE users
            SET email = :email,
                firstname = :firstname,
                lastname = :lastname,
                birth_date = :birth_date,
				password = :password
            WHERE id = :id
        ');
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':firstname', $user->firstname);
        $stmt->bindParam(':lastname', $user->lastname);
        $stmt->bindParam(':birth_date', $user->birth_date);
		$stmt->bindParam(':password', $user->password);
        $stmt->bindParam(':id', $user->id);
        return $stmt->execute();
    }
}