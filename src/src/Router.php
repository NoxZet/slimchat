<?php
namespace App;

use Slim\Http\Request;
use Slim\Http\Response;

class Router {
	public function __construct(protected Database $database, protected \Slim\App $slimApp) {
		$this->createRoutes();
	}

	protected function createRoutes() {
		$self = $this;
		$this->slimApp->post('/user', function (Request $request, Response $response, array $args) use ($self) {
			$body = $request->getParsedBody();
			if (is_array($body) && isset($body['username']) && isset($body['password'])) {
				$self->database->persist(new Entity\User(
					$body['username'], password_hash($body['password'], PASSWORD_DEFAULT)
				));
			} else {
				$response->withStatus(400);
			}

			return $response;
		});
	}
}