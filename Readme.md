# Starting the project

To run application, you must have docker and docker-compose installed

For initial setup and project (docker) start, run

```
make config
make docker-up
make init
```

To create and start docker containers, run

```
make docker-up
```

To remove docker containers, run

```
make docker-down
```

# API usage

The site is available at `http://localhost/`

API endpoints that require body parameters must have their body type be JSON and the body must be a valid JSON object containing named parameters.

## /users (POST)

Creates a new user

Accepts two mandatory string parameters in a body: username, password

## /users/{username} (GET)

Displays public info of a user (currently only username)

## /users/{username}/token (POST)

Creates a new authorization token, invalidating any previously generated one. Returns the token in an object in a JSON body.

Accepts one mandatory string parameter: password

## /messages (POST)

Creates a new message.

Requires authorization header. The key of the header must be "Authorization" and the value must be a token previously generated with `/users/{username}/token` endpoint.

Accepts one mandatory string parameter: content

## /messages (GET)

Retrieves the latest messages as a JSON array of objects.

Accepts two optional query parameters: limit (maximum 10, default 5), offset (default 0)

## /messages/{id} (GET)

Retrieves the message with the given id. Returns it as a JSON object with id, author and content.

## Example usage

- POST http://localhost/users

- JSON body `{"username": "foo", "password": "bar"}`

- POST http://localhost/users/foo/token

- JSON body `{"password": "bar"}`

- POST http://localhost/messages

- Header: `Authentication: <token returned by the previous call>`

- JSON body `{"content": "This is a test message"}`

- GET http://localhost/messages?limit=2