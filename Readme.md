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

Access the site at `http://localhost/`

To remove docker containers, run

```
make docker-down
```