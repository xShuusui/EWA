#========================================================================================
# Start options for the Docker services.
#========================================================================================

# Start all server components in detached mode.
server-up:
	docker-compose -f ./docker/docker-compose.ewa-server.yml up -d

# Stop all server components.
server-stop:
	docker-compose -f ./docker/docker-compose.ewa-server.yml stop

#========================================================================================

# List all docker containers.
container-ls:
	docker container ls -a

# List all docker images.
image-ls:
	docker image ls -a

# List all docker networks.
network-ls:
	docker network ls

# List informations to access the database.
info-database:
	@echo To access mariadb run:
	@echo - docker exec -it ewa-database /bin/bash
	@echo - mysql -u dbuser -p shop

#========================================================================================


