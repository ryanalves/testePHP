
## Teste de PHP

Nome: Ryan Alves Martins

Teste: teste-php-2 (vagas/candidatos)
### Instalação (Docker)
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
  -p 3306:3306 \
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
  -e CODEIGNITER_PORT_NUMBER=8000 \
  -e CODEIGNITER_DATABASE_HOST=alphacode-mysql \
  -e CODEIGNITER_DATABASE_PORT_NUMBER=3306 \
  -e CODEIGNITER_DATABASE_NAME=alphacode_teste \
  -e CODEIGNITER_DATABASE_USER=root \
  -e CODEIGNITER_DATABASE_PASSWORD=123456 \
  -v ${PWD}:/app \
  bitnami/codeigniter:4.4.5
```

```bash
docker exec -it alphacode-codeigniter bash -c "cd alphacode && composer install"
docker exec -it alphacode-codeigniter bash -c "cd alphacode && php spark migrate"
docker exec -it alphacode-codeigniter bash -c "cd alphacode && php spark db:seed BootstrapSeeder"
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

### Acesso
email: admin@mail.com
senha: 123456