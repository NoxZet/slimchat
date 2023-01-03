<?php

namespace App\Entity;
use PDO;

abstract class BaseEntity {
	abstract public function persist(PDO $pdo);

	public function flush(PDO $pdo) {}
}