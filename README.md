
# Teste de PHP

Nome: Ryan Alves Martins

Teste: teste-php-2 (vagas/candidatos)
## Instalação

#### Utilizando Docker-compose
iniciando o projeto
```bash
docker-compose up -d
```
parando o projeto
```bash
docker-compose stop
```
removendo o projeto
```bash
docker-compose down
```

#### Utilizando Docker
iniciando o projeto
```bash
docker network create alphacode-network

```

```bash
docker volume create alphacode_mysql_data
```

```bash
docker run -d \
  --name alphacode-mysql \
  -e MYSQL_ALLOW_EMPTY_PASSWORD=yes \
  -e MYSQL_USER=alphacode \
  -e MYSQL_DATABASE=alphacode_teste \
  -e MYSQL_ROOT_PASSWORD=123456 \
  -p 8001:3306 \
  --network alphacode-network \
  -v alphacode_mysql_data:/bitnami/mysql \
  mysql:8.0.36-debian

```

```bash
docker run -d \
  --name alphacode-codeigniter \
  -p 8000:8000 \
  --network alphacode-network \
  -e CODEIGNITER_PROJECT_NAME=alphacode \
  -e DB_HOST=mysql \
  -e DB_PORT=8001 \
  -e DB_USERNAME=alphacode \
  -e DB_PASSWORD=123456 \
  -e DB_DATABASE=alphacode_teste \
  -e ALLOW_EMPTY_PASSWORD=yes \
  -v ${PWD}:/app \
  bitnami/codeigniter:latest

```

parando o projeto

```bash
docker stop alphacode-codeigniter alphacode-mysql
```

removendo o projeto

```bash
docker stop alphacode-codeigniter alphacode-mysql
docker rm alphacode-codeigniter alphacode-mysql -v
docker volume rm alphacode_mysql_data
docker network rm alphacode-network
```