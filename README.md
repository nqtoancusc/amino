# README #

This is PHP MVC framework developed by Toan Nguyen

The following is the guide for setting up working environment.

## Software requirements Version requirement are:
- docker
- docker-compose

## Build docker images and load docker images to container:
Run the following commands to build docker images and load docker images to container:
- docker-compose build
- docker-compose up

# Manual test:
- Open web site at http://localhost/

# View data in MySQL, run 

- docker exec -it <mysql_docker_container_name> /bin/bash  
- Use database from terminal:
mysql -uroot
>> use mydb
