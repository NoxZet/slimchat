<?php

namespace App\Entity;
use PDO;

abstract class BaseEntity {
	// Every entity has different structure to persist
	abstract public function persist(PDO $pdo);

	// By default, entity doesn't need to have any mutable data
	public function flush(PDO $pdo) {}
}