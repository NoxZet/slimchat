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
		$this->slimApp->post('/users', function (Request $request, Response $response, array $args) use ($self) {
			$body = $request->getParsedBody();
			if (is_array($body) && isset($body['username']) && isset($body['password'])) {
				$self->database->persist(new Entity\User(
					$body['username'], password_hash($body['password'], PASSWORD_DEFAULT)
				));
			} else {
				return $response->withStatus(400);
			}

			return $response;
		});

		$this->slimApp->get('/users/{username}', function (Request $request, Response $response, array $args) use ($self) {
			// Verify the request contents syntactically
			$body = $request->getParsedBody();
			if (isset($args['username'])) {
				$username = $args['username'];
				// Verify the user exists
				$user = $self->database->getUser($username);
				if ($user) {
					// Return public data about the user (only the name)
					$response->getBody()->write(json_encode([
						'username' => $user->getUsername(),
					]));
				} else {
					return $response->withStatus(404);
				}
			} else {
				return $response->withStatus(400);
			}

			return $response;
		});

		$this->slimApp->post('/users/{username}/token', function (Request $request, Response $response, array $args) use ($self) {
			// Verify the request contents semantically
			$body = $request->getParsedBody();
			if (is_array($body) && isset($args['username']) && isset($body['password'])) {
				$username = $args['username'];
				// Verify the user exists
				$user = $self->database->getUser($username);
				if ($user) {
					// Check the password against the retrieved User entity
					if (password_verify($body['password'], $user->getPassword())) {
						// Create a token and save it on the entity
						$token = bin2hex(random_bytes(32));
						$self->database->flush($user->setToken($token));
						$response->getBody()->write(json_encode([
							'token' => $token,
						]));
					} else {
						return $response->withStatus(401);
					}
				} else {
					return $response->withStatus(404);
				}
			} else {
				return $response->withStatus(400);
			}

			return $response;
		});

		$this->slimApp->post('/messages', function (Request $request, Response $response, array $args) use ($self) {
			$body = $request->getParsedBody();
			$authHeader = $request->getHeader('Authorization');
			// Verify the request semantically
			if (count($authHeader) !== 1) {
				return $response->withStatus(403);
			} else if (is_array($body) && isset($body['content'])) {
				// Get user from authorization token
				$user = $self->database->getUserByToken($authHeader[0]);
				if ($user) {
					$self->database->persist(new Entity\Message(null, $user->getUsername(), $body['content']));
				} else {
					return $response->withStatus(403);
				}
			} else {
				return $response->withStatus(400);
			}

			return $response;
		});

		$this->slimApp->get('/messages/{id}', function (Request $request, Response $response, array $args) use ($self) {
			// Verify the request contents syntactically
			$body = $request->getParsedBody();
			if (isset($args['id']) && ctype_digit($args['id'])) {
				// Get the message
				$message = $self->database->getMessage((int)$args['id']);
				if ($message) {
					// Return public data about the message
					$response->getBody()->write(json_encode([
						'id' => $message->getId(),
						'author' => $message->getAuthor(),
						'content' => $message->getContent(),
					]));
				} else {
					return $response->withStatus(404);
				}
			} else {
				return $response->withStatus(400);
			}

			return $response;
		});

		$this->slimApp->get('/messages', function (Request $request, Response $response, array $args) use ($self) {
			// Verify the request contents syntactically
			$body = $request->getParsedBody();
			$messages = $self->database->getMessages((int)$request->getQueryParam('limit', 5), (int)$request->getQueryParam('offset', 0));
			// Return public data for every message
			$response->getBody()->write(json_encode(array_map(function ($message) {
				return [
					'id' => $message->getId(),
					'author' => $message->getAuthor(),
					'content' => $message->getContent(),
				];
			}, $messages)));

			return $response;
		});
	}
}