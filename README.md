# README #

This document is the guide for setting up working environment.

## Software requirements Version requirement are:

- docker
- docker-compose

## Build docker images and load docker images to container:
Run the following commands to build docker images and load docker images to container:
- docker-compose build
- docker-compose up

# Manual test:
- Open web site at http://localhost:8080/
- On home page, select kuivuri.xml file from directory "web/data" and hit upload. Then, hit "Yes, confirm"

# View data in MySQL, run 

- docker exec -it <mysql_docker_container_name> /bin/bash  
- Example: Open terminal:
+ Run:
docker exec -it resourcebooking_devmysql_1 /bin/bash
+ Then run:
mysql -uroot
>> use amino

