<?php

namespace App\Entity;
use PDO;

class User extends BaseEntity {
	function __construct(protected string $username, protected string $password, protected ?string $token = null) {}

	public function getUsername(): string {
		return $this->username;
	}

	public function getPassword(): string {
		return $this->password;
	}

	public function getToken(): ?string {
		return $this->token;
	}

	public function setToken(string $token): static {
		$this->token = $token;
		return $this;
	}

	public function persist(PDO $pdo) {
		$statement = $pdo->prepare('INSERT INTO users (`username`, `password`, `token`) VALUES (:username, :password, :token)');
		$statement->bindValue(':username', $this->username);
		$statement->bindValue(':password', $this->password);
		$statement->bindValue(':token', $this->token);
		$statement->execute();
	}

	public function flush(PDO $pdo) {
		$statement = $pdo->prepare('UPDATE users SET `token` = :token WHERE `username` = :username');
		$statement->bindValue(':token', $this->token);
		$statement->bindValue(':username', $this->username);
		$statement->execute();
	}
}