<?php
namespace App;
use PDO;

class Database {
	public function __construct(protected PDO $pdo) {
		$this->createTables();
	}

	public function createTables() {
		$this->pdo->query( <<<SQLquery
			CREATE TABLE IF NOT EXISTS users (
				username TEXT NOT NULL UNIQUE,
				password TEXT NOT NULL,
				token TEXT
			);
			SQLquery);

		$this->pdo->query( <<<SQLquery
			CREATE TABLE IF NOT EXISTS messages (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				author TEXT NOT NULL,
				content TEXT NOT NULL,
				FOREIGN KEY(author) REFERENCES users(username)
			);
			SQLquery);
	}

	public function persist(Entity\BaseEntity $entity) {
		$entity->persist($this->pdo);
	}

	public function flush(Entity\BaseEntity $entity) {
		$entity->flush($this->pdo);
	}

	protected function bindUser(array|bool $result) {
		if ($result === false) {
			return null;
		} else {
			return new Entity\User($result['username'], $result['password'], $result['token']);
		}
	}

	public function getUser(string $username) {
		$statement = $this->pdo->prepare('SELECT `username`, `password`, `token` FROM users WHERE `username` = :username');
		$statement->bindValue(':username', $username);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		return $this->bindUser($result);
	}

	public function getUserByToken(string $token) {
		$statement = $this->pdo->prepare('SELECT `username`, `password`, `token` FROM users WHERE `token` = :token');
		$statement->bindValue(':token', $token);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		return $this->bindUser($result);
	}
}