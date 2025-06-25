# TodoList Backend API

Uma API RESTful para gerenciamento de tarefas (Todo List) construída com Laravel, implementando autenticação via Laravel Sanctum.

## 🚀 Características

- **Autenticação Segura**: Sistema de autenticação com Laravel Sanctum
- **API RESTful**: Endpoints bem estruturados seguindo padrões REST
- **Gerenciamento de Tarefas**: CRUD completo para tarefas
- **Estatísticas**: Endpoint com métricas de produtividade
- **Validação de Dados**: Validação robusta em todas as entradas

## 📋 Pré-requisitos

- PHP >= 8.1
- Composer
- SQLite (incluído no PHP)

## 🛠️ Instalação

### 1. Clone o repositório
```bash
git clone https://github.com/MatheusConstantino/todolist-backend.git
cd todolist-backend
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados
O projeto utiliza SQLite por padrão. Verifique se o arquivo `.env` está configurado assim:
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 5. Crie o arquivo do banco de dados
```bash
touch database/database.sqlite
```

### 6. Execute as migrações
```bash
php artisan migrate
```

### 7. Inicie o servidor
```bash
php artisan serve
```

A API estará disponível em `http://localhost:8000`

## 📚 Documentação da API

### Autenticação

#### Registro de Usuário
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "João Silva",
    "email": "joao@exemplo.com",
    "password": "senha123",
    "password_confirmation": "senha123"
}
```

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "joao@exemplo.com",
    "password": "senha123"
}
```

#### Logout
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Perfil do Usuário
```http
GET /api/auth/me
Authorization: Bearer {token}
```

### Gerenciamento de Tarefas

#### Listar Tarefas
```http
GET /api/todos
Authorization: Bearer {token}
```

#### Criar Tarefa
```http
POST /api/todos
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Minha nova tarefa",
    "description": "Descrição da tarefa",
    "priority": "high",
    "due_date": "2024-12-31"
}
```

#### Visualizar Tarefa
```http
GET /api/todos/{id}
Authorization: Bearer {token}
```

#### Atualizar Tarefa
```http
PUT /api/todos/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Tarefa atualizada",
    "description": "Nova descrição",
    "priority": "medium"
}
```

#### Marcar como Concluída
```http
PATCH /api/todos/{id}/complete
Authorization: Bearer {token}
```

#### Deletar Tarefa
```http
DELETE /api/todos/{id}
Authorization: Bearer {token}
```

#### Estatísticas
```http
GET /api/todos/statistics
Authorization: Bearer {token}
```

## 🏗️ Estrutura do Projeto

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── AuthController.php
│           └── TodoController.php
├── Models/
│   ├── User.php
│   └── Todo.php
routes/
└── api.php
```

## 🔒 Segurança

- Autenticação via Laravel Sanctum
- Validação de dados em todas as entradas
- Middleware de autenticação protegendo rotas sensíveis

## 🚀 Uso

1. Registre um usuário ou faça login
2. Use o token retornado no header `Authorization: Bearer {token}`
3. Acesse os endpoints de tarefas para gerenciar seus todos
4. Consulte as estatísticas para acompanhar sua produtividade

## 📄 Licença

Este projeto está licenciado sob a Licença MIT.

## 👥 Autor

- **Matheus Constantino** - [MatheusConstantino](https://github.com/MatheusConstantino)

---

⭐ TodoList Backend API - Gerencie suas tarefas de forma eficiente!
