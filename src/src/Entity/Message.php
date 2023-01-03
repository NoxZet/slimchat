<?php

namespace App\Entity;
use PDO;

class Message extends BaseEntity {
	function __construct(protected ?int $id = null, protected string $author, protected string $content) {}

	public function getId(): ?int {
		return $this->id;
	}

	public function getAuthor(): string {
		return $this->author;
	}

	public function getContent(): string {
		return $this->content;
	}

	public function persist(PDO $pdo) {
		if (!is_null($this->id)) {
			throw new \Exception();
		}
		$statement = $pdo->prepare('INSERT INTO messages (`author`, `content`) VALUES (:author, :content)');
		$statement->bindValue(':author', $this->author);
		$statement->bindValue(':content', $this->content);
		$statement->execute();
		$this->id = $pdo->lastInsertId();
	}
}