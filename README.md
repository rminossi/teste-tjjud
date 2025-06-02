# Projeto TJJud - Sistema de Gerenciamento de Livros

## Descrição

Este projeto é um sistema web para cadastro, consulta e gerenciamento de livros, autores e assuntos, desenvolvido em Laravel. Possui interface web e API RESTful, além de recursos de relatórios e relacionamento entre entidades.

---

## Recursos do Sistema

- Cadastro, edição, exclusão e listagem de **Livros**
- Cadastro, edição, exclusão e listagem de **Autores**
- Cadastro, edição, exclusão e listagem de **Assuntos**
- Relacionamento N:N entre livros, autores e assuntos
- Relatórios customizados (ex: livros por autor)
- API RESTful para integração externa
- Testes automatizados (Feature e Unit)

---

## Pré-requisitos

- [Docker](https://www.docker.com/) e [Docker Compose](https://docs.docker.com/compose/)
- (Opcional) [Git](https://git-scm.com/)
- **Para rodar testes unitários:**
  - O container/app PHP deve ter as extensões `pdo_sqlite` e `sqlite3` instaladas e habilitadas.

---

## Configuração e Execução com Docker

### 1. Clone o repositório

```bash
git clone https://github.com/rminossi/teste-tjjud.git
cd teste-tjjud
```

### 2. Copie o arquivo de ambiente

```bash
cp .env.example .env
```

### 3. Suba os containers

```bash
docker-compose up -d
```

### 4. Instale as dependências do Composer

```bash
docker-compose exec app composer install
```

### 5. Gere a chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```

### 6. Execute as migrations e seeders

```bash
docker-compose exec app php artisan migrate --seed
```

### 7. (Opcional) Instale as dependências do NPM (para assets front-end)

```bash
docker-compose exec app npm install
docker-compose exec app npm run dev
```

---

## Acessando o Sistema

- **Web:** [http://localhost:8000](http://localhost:8000)
- **API:** [http://localhost:8000/api](http://localhost:8000/api)

---

## Execução dos Testes

### Testes automatizados (PHPUnit)

Para rodar todos os testes (unitários e de feature):

```bash
docker-compose exec app php artisan test --env=testing
```

### Testes Unitários dos FormRequests

Os testes unitários dos FormRequests garantem que as validações de cada request estão corretas. Eles estão localizados em `tests/Unit/`.

Para rodar apenas os testes unitários:

```bash
docker-compose exec app php artisan test --testsuite=Unit
```

### Requisitos para os testes

- O ambiente de testes utiliza SQLite em memória por padrão. Certifique-se de que o PHP do container possui as extensões `pdo_sqlite` e `sqlite3` habilitadas.
- Se aparecer o erro `could not find driver (Connection: sqlite)`, instale as extensões no container:

```bash
docker-compose exec app apt-get update
docker-compose exec app apt-get install -y php-sqlite3
```

Depois, reinicie o container:

```bash
docker-compose restart app
```

---

## Estrutura dos Containers

- **app:** Container da aplicação Laravel (PHP)
- **db:** Banco de dados MySQL/PostgreSQL
- **(opcional) nginx:** Servidor web (caso configurado)

---

## Comandos Úteis

- **Acessar o container da aplicação:**
  ```bash
  docker-compose exec app bash
  ```
- **Rodar migrations:**
  ```bash
  docker-compose exec app php artisan migrate
  ```
- **Rodar seeders:**
  ```bash
  docker-compose exec app php artisan db:seed
  ```
- **Limpar cache:**
  ```bash
  docker-compose exec app php artisan cache:clear
  docker-compose exec app php artisan config:clear
  docker-compose exec app php artisan route:clear
  ```

---

## Observações

- Nunca rode testes automatizados em banco de produção.
- Para ambiente de testes, utilize um banco de dados separado (configurado em `.env.testing`).
- As rotas de API estão disponíveis em `/api/livros`, `/api/autores`, `/api/assuntos`.
- Os testes unitários dos FormRequests estão em `tests/Unit/` e cobrem todas as validações de entrada da API.

---
