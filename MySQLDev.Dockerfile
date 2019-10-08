FROM mysql:5.7.25
ENV MYSQL_ALLOW_EMPTY_PASSWORD='yes'
COPY ./web/sql/tables.sql /docker-entrypoint-initdb.d/
EXPOSE 3306