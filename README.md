## Bank API

### Projeto feito com as seguintes tecnologias:
- PHP 8.3
- Laravel Framework 10
- MySQL 8
- Redis 7
- Nginx
- MySQL

## Estrutura de Containers
- api_php_fpm: Container responsável pela API
- api_php_cli: Container responsável por executar os commands e rotinas agendadas
- api_nginx: Container responsável pelo servidor Web NGINX
- api_mysql: Container responsável pelo banco de dados
- api_redis: Container responsável pelo servidor Redis (usado para filas)

## Preparando o Ambiente

### Para exeuctar esse projeto você precisa de:
 - Windows com WSL2 / Ubuntu / MacOs
 - Docker
 - docker-compose
 - git

## Após preparação do ambiente:
 - Clonar o projeto
 - fazer uma cópia do arquivo .env-exemple com o nome .env
 - preencher as variáveis de ambiente no arquivo .env
   - EXTERNAL_AUTH_URL  = url do autenticador externo
   - EXTERNAL_AUTH_KEY  = key de autenticação.
 - executar docker-compose up --build
 - aguardar a execução dos scripts do container do PHP


## Endpoints

### Collection Postman 
[BankApi.postman_collection.json](BankApi.postman_collection.json)

### Criar conta
POST
````
http://localhost:61000/api/account
````

````
{
    "name" : "Rafael Roque"
}
````

### Nova Transação
POST
````
http://localhost:61000/api/transaction
````
````
{
    {
    "sender" : 28,
    "receiver": 29,
    "amount" : 100
}
````

#### e para agendar uma transação:

````
{
    "sender" : 28,
    "receiver": 29,
    "amount" : 100,
    "scheduled_to": "2024-03-10"
}
````

## Comandos

### Criar nova conta
```
php artisan account:create {name}
```

#### Entrar no container:
```
- docker exec -it api_php_cli /bin/sh
```


### Para alimentar o banco de dados com dados fictícios:
- executar no container:
```
php artisan db:seed
```

### Para rodar a fila
- executar no container:
```
php artisan queue:work
```

### * O cron job que adiciona os itens na fila (roda as 5 da manhã) é executado automaticamente, nao sendo necessário nenhum comando *

### Para executar os testes automatizados
- executar no container:
```
php artisan tests
```

