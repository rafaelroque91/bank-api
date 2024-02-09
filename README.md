## Bank API

### Projeto feito com as seguintes tecnologias:
- PHP 8.3
- Laravel Framework 10
- MySQL 8
- Redis 7
- Nginx
- MySQL


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



