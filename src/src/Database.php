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
	}

	public function persist(Entity\BaseEntity $entity) {
		$entity->persist($this->pdo);
	}

	public function flush(Entity\BaseEntity $entity) {
		$entity->flush($this->pdo);
	}
	public function getUser(string $username) {
		$statement = $this->pdo->prepare('SELECT `username`, `password` FROM users WHERE `username` = :username');
		$statement->bindValue(':username', $username);
		$statement->execute();
		$result = $statement->fetch(PDO::FETCH_ASSOC);
		if ($result === false) {
			return null;
		} else {
			return new Entity\User($result['username'], $result['password']);
		}
	}
}