# Api Gerenciador de Tarefas

API desenvolvida em Laravel para gerenciamento de tarefas, permitindo criar, listar, atualizar e excluir tarefas de forma eficiente.

---

## Sobre o projeto

A **Api Gerenciador de Tarefas** é uma aplicação backend construída com Laravel, com interface utilizando Blade e TailwindCSS.

Ela permite:

* Criar tarefas
* Listar tarefas
* Atualizar tarefas
* Excluir tarefas
* Criar comentários para uma tarefa
* Excluir comentários de uma tarefa


---

## Tecnologias utilizadas

* PHP 8.3
* Laravel 13
* MySQL
* Blade
* TailwindCSS
* Docker & Docker Compose
* Ambiente de desenvolvimento Linux/ubuntu

---

## Estrutura

```
app/
 ├── Http/
 │   ├── Controllers/
 │   │   ├── API/
 │   │   │   ├── AuthController.php
 │   │   │   ├── CommentController.php
 │   │   │   ├── TaskController.php
 │   │   └── Web/
 │   │       ├── AuthController.php
 │   │       ├── CommentController.php
 │   │       ├── TaskController.php
 │   ├── Requests/
 │   │   ├── FilterTaskRequest.php
 │   │   ├── GenerateTokenAuthRequest.php
 │   │   ├── RegisterAuthRequest.php
 │   │   ├── StoreCommentRequest.php
 │   │   ├── StoreTaskRequest.php
 │   │   ├── UpdateTaskRequest.php
 │   ├── Resources/
 │   │   ├── CommentResource.php
 │   │   ├── TaskResource.php
 ├── Models/
 │   ├── Comment.php
 │   ├── Task.php
 │   ├── User.php
 ├── Policies/
 │   ├── CommentPolicy.php
 │   ├── TaskPolicy.php
 ├── Services/
 │   ├── CommentService.php
 │   ├── TaskService.php

resources/views/
 ├── auth/
 │   ├── login.blade.php
 │   ├── register.blade.php
 ├── layouts/
 │   ├── app.blade.php
 ├── tasks/
 |   ├── form.blade.php
 │   ├── index.blade.php
```

## Subindo o Projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/JardanDuarte/api-tarefas.git
cd api-tarefas
```

### 2. Subindo os containers

Levando em consideração que você já tenha o docker instalado em sua maquina.

Na raiz do projeto, execute:

```bash
docker-compose up -d --build
```
---

### 3. Configuração do .env
Copie o arquivo `.env`:

```bash
cp .env.example .env
```

Dentro do `.env`, use:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

### 4. Instalar dependências e rodar migrations + seeds no container

```bash
docker exec -it laravel_app_task bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
```

---

### Acessos
* API: http://localhost:8000/api/v1
* Aplicação(Frontend): http://localhost:8000
* phpMyAdmin: http://localhost:8080

---

## Autenticação via cURL

### Registrar usuário
Essa requisição ja retorna um token valido caso seja necessário você pode criar um token usando a requisição generateToken

```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Seu Nome",
    "email": "email@email.com",
    "password": "12345678"
    "password_confirmation": "12345678"
  }'
```

---

### Gerar token

```bash
curl -X POST http://localhost:8000/api/v1/generateToken \
  -H "Content-Type: application/json" \
  -d '{
    "email": "email@email.com",
    "password": "12345678"
  }'
```

Copie o token retornado para usar nas próximas requisições.

---

## Tarefas (Tasks)

### Listar tarefas

```bash
curl -X GET http://localhost:8000/api/v1/tasks \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

## Filtros disponíveis

### Filtrar por status

```bash
curl -X GET "http://localhost:8000/api/v1/tasks?status=pendente" \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Filtrar por data de criação

```bash
curl -X GET "http://localhost:8000/api/v1/tasks?created_at=2026-01-01" \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Paginação

```bash
curl -X GET "http://localhost:8000/api/v1/tasks?per_page=5&page=1" \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Criar tarefa

```bash
curl -X POST http://localhost:8000/api/v1/tasks \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Nova tarefa",
    "description": "Descrição da tarefa",
    "status": "pendente"
  }'
```

---

### Mostrar tarefa por id

```bash
curl -X GET http://localhost:8000/api/v1/tasks/1 \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Atualizar tarefa

```bash
curl -X PUT http://localhost:8000/api/v1/tasks/1 \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Título atualizado",
    "description": "Nova descrição",
    "status": "concluida"
  }'
```

---

### Deletar tarefa

```bash
curl -X DELETE http://localhost:8000/api/v1/tasks/1 \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

## Comentários

### Listar comentários de uma tarefa

```bash
curl -X GET http://localhost:8000/api/v1/tasks/1/comments \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Criar comentário

```bash
curl -X POST http://localhost:8000/api/v1/tasks/1/comments \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "content": "Comentário da tarefa"
  }'
```

---

### Deletar comentário

```bash
curl -X DELETE http://localhost:8000/api/v1/tasks/1/comments/1 \
  -H "Authorization: Bearer SEU_TOKEN"
```

---

### Logout (Caso queira deletar o token)

```bash
curl -X POST http://localhost:8000/api/v1/logout \
  -H "Authorization: Bearer SEU_TOKEN"
```
---

## Rodando os testes da api

O projeto possui testes automatizados. Para executar acesse o container docker

```bash
docker exec -it laravel_app_task bash
```
Execute os comando abaixo individualmente.

```bash
php artisan test tests/Feature/TaskControllerTest.php
php artisan test tests/Feature/CommentControllerTest.php
php artisan test tests/Feature/AuthControllerTest.php
```
---

# frontend (Blade)

## Telas

### Login

* `http://localhost:8000/login`
* Autenticação do usuário

---

### Listagem de tarefas

* `http://localhost:8000/`
* Lista todas as tarefas do usuário
* Exibe:

  * Título
  * Status
  * Descrição
  * Comentários vinculados
* Ações disponíveis:

  * Editar tarefa
  * Deletar tarefa
* Filtro por status
* Paginação de resultados

---

### Criar tarefa

* `http://localhost:8000/tasks/create`
* Formulário para criação de tarefa
* Campos:

  * Título
  * Descrição
  * Status
* Permite adicionar **múltiplos comentários** antes de salvar

---

### Editar tarefa

* `http://localhost:8000/tasks/{id}/edit`
* Atualiza dados da tarefa:

  * Título
  * Descrição
  * Status
* Permite adicionar **novos comentários dinamicamente**
* Exibe lista de comentários existentes
* Permite deletar comentários

# Funcionalidades

* Autenticação completa
* CRUD de tarefas
* Status (pendente, em andamento, concluída)
* Filtros
* Paginação
* Comentários por tarefa
* Criação de comentário junto com tarefa

---

## Decisões técnicas

### Arquitetura da api

Foi adotada uma arquitetura baseada em separação de responsabilidades:

* **Controllers** → apenas orquestram as requisições
* **Services** → contêm a lógica de negócio
* **Policies** → controlam autorização
* **FormRequests** → validação desacoplada
* **Resources** → padronização das respostas
* **Handler global** → tratamento centralizado de erros

---

### Segurança

* Autenticação via Laravel Sanctum
* Isolamento de dados por usuário
* Uso de Policies para controle de acesso

---

### API Design

* Padrão REST
* Versionamento (`/api/v1`)
* Status codes corretos (200, 201, 204, 422, 403, 404)
* Paginação e filtros

---

### Banco de dados

* Relacionamento:

  * User → Tasks (1:N)
  * Task → Comments (1:N)

---

### Melhorias futuras

* Swagger / OpenAPI para documentação automática
* Testes automatizados no frontend
* Cache para otimização de performance
* Soft deletes para tarefas e comentários
* Roles/Permissões (admin, etc)
* Renovação automatica de token
* Deixar o frontend com o desing bonito e responsivo
* Notificações (toast)

---
### Autor

Jardan Duarte