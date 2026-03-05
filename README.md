# SprintDesk API

API REST desenvolvida em **Laravel** para um mini sistema de **gestão de projetos e tickets**.

Este projeto foi desenvolvido como parte de um **teste técnico para desenvolvedor web**, demonstrando a criação de uma API organizada seguindo boas práticas de desenvolvimento com Laravel.

---

# Tecnologias Utilizadas

- PHP 8+
- Laravel
- MySQL
- Eloquent ORM
- RESTful API
- Composer

---

# Estrutura do Sistema

O sistema possui duas entidades principais:

## Projects

Representa projetos cadastrados no sistema.

Campos:

- id
- name
- description
- created_at
- updated_at

---

## Tickets

Representa demandas ou tarefas associadas a um projeto.

Campos:

- id
- project_id
- title
- description
- status
- created_at
- updated_at

---

## Relacionamento

```
Project 1 ------ N Tickets
```

Um **projeto pode possuir vários tickets**, mas cada **ticket pertence a apenas um projeto**.

---

# Requisitos

Para executar o projeto localmente é necessário ter instalado:

- PHP 8+
- Composer
- MySQL
- Laravel CLI (opcional)

---

# Como rodar o projeto

## 1. Clonar o repositório

```bash
git clone <url-do-repositorio>
```

Entrar na pasta do projeto:

```bash
cd sprintdesk
```

---

## 2. Instalar dependências

```bash
composer install
```

---

## 3. Criar arquivo de configuração

```bash
cp .env.example .env
```

---

## 4. Configurar banco de dados

Editar o arquivo `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sprintdesk
DB_USERNAME=root
DB_PASSWORD=
```

Criar o banco no MySQL:

```
sprintdesk
```

---

## 5. Gerar chave da aplicação

```bash
php artisan key:generate
```

---

## 6. Rodar migrations

```bash
php artisan migrate
```

---

## 7. Rodar seeders

Para popular o banco com projetos e tickets de exemplo:

```bash
php artisan db:seed
```

Isso executará o `DatabaseSeeder`, que por sua vez chama:

- `ProjectSeeder`
- `TicketSeeder`

Se quiser recriar o banco do zero com dados de teste:

```bash
php artisan migrate:fresh --seed
```

---

## 8. Iniciar servidor

```bash
php artisan serve
```

A API ficará disponível em:

```
http://127.0.0.1:8000
```

---

# Endpoints da API

## Projects

### Listar projetos

```
GET /api/projects
```

Possui **paginação automática** (10 projetos por página) controlada via:

```
GET /api/projects?page=1
GET /api/projects?page=2
```

---

### Filtro por nome

```
GET /api/projects?q=nome
```

Exemplo:

```
/api/projects?q=teste
```

---

### Criar projeto

```
POST /api/projects
```

Body JSON:

```json
{
    "name": "Projeto Teste",
    "description": "Projeto criado para teste"
}
```

---

### Buscar projeto por ID

```
GET /api/projects/{id}
```

---

### Atualizar projeto

```
PUT /api/projects/{id}
```

---

### Deletar projeto

```
DELETE /api/projects/{id}
```

---

# Tickets

### Listar tickets de um projeto

```
GET /api/projects/{id}/tickets
```

Este endpoint também é **paginado** (10 tickets por página), por exemplo:

```
GET /api/projects/1/tickets?page=1
```

---

### Criar ticket

```
POST /api/projects/{id}/tickets
```

Body JSON:

```json
{
    "title": "Corrigir bug",
    "description": "Erro na tela de login",
    "status": "open"
}
```

---

### Status permitidos

```
open
in_progress
done
```

---

### Buscar ticket

```
GET /api/tickets/{id}
```

---

### Atualizar ticket

```
PATCH /api/tickets/{id}
```

---

### Deletar ticket

```
DELETE /api/tickets/{id}
```

---

# Regras de Negócio

## 1. Ticket deve pertencer a um projeto existente

Não é possível criar tickets para projetos inexistentes.

---

## 2. Exclusão de projetos

A API **impede a exclusão de projetos que possuam tickets associados**.

### Motivação

- preservar histórico de demandas
- evitar perda acidental de dados
- manter integridade referencial

Se um projeto possuir tickets, a API retorna:

```
HTTP 409 - Conflict
```

Com uma mensagem explicativa.

---

# Validações

## Projects

- `name`: obrigatório, string, máximo 255 caracteres
- `description`: opcional, string

## Tickets

- `title`: obrigatório no cadastro, string, máximo 255 caracteres
- `description`: opcional, string
- `status`: opcional, deve ser um dos valores:
  - `open`
  - `in_progress`
  - `done`

---

# Testes da API

A API pode ser testada utilizando ferramentas como:

- Postman
- Insomnia
- cURL

---

## Exemplos usando cURL

### Criar projeto

```bash
curl -X POST http://127.0.0.1:8000/api/projects \
  -H "Content-Type: application/json" \
  -d '{"name":"Projeto Teste","description":"Primeiro projeto"}'
```

### Listar projetos (paginação)

```bash
curl http://127.0.0.1:8000/api/projects?page=2
```

### Listar projetos filtrando por nome

```bash
curl "http://127.0.0.1:8000/api/projects?q=teste"
```

### Criar ticket em um projeto

```bash
curl -X POST http://127.0.0.1:8000/api/projects/1/tickets \
  -H "Content-Type: application/json" \
  -d '{"title":"Corrigir bug","description":"Erro na tela de login","status":"open"}'
```

### Atualizar apenas o status de um ticket

```bash
curl -X PATCH http://127.0.0.1:8000/api/tickets/1 \
  -H "Content-Type: application/json" \
  -d '{"status":"in_progress"}'
```

### Tentar deletar projeto com tickets associados (retorna 409)

```bash
curl -X DELETE http://127.0.0.1:8000/api/projects/1 -i
```

---

# Autor

Desenvolvido por

**Amauri Rodrigues dos Santos Junior**
