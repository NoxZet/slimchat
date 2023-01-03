<?php

namespace App\Entity;
use PDO;

class User extends BaseEntity {
	function __construct(protected string $username, protected string $password) {}

	public function getUsername() {
		return $this->username;
	}

	public function getPassword() {
		return $this->password;
	}

	public function persist(PDO $pdo) {
		$statement = $pdo->prepare('INSERT INTO users (`username`, `password`) VALUES (:username, :password)');
		$statement->bindValue(':username', $this->username);
		$statement->bindValue(':password', $this->password);
		$statement->execute();
	}
}