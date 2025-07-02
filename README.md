# 🛡️ Projeto de Autenticação com Laravel Breeze

Este projeto consiste em uma aplicação web simples desenvolvida com Laravel 12, utilizando Laravel Breeze para autenticação, permitindo registro, login, edição de perfil e alteração de senha.

---

## 🚀 Tecnologias Utilizadas

- PHP 8.2
- Laravel 12
- Laravel Breeze
- MySQL
- Blade + Bootstrap 5
- PHPUnit (para testes)

---

## ⚙️ Requisitos

Antes de começar, certifique-se de ter instalado:

- PHP >= 8.2
- Composer
- MySQL
- Node.js e NPM (opcional, para compilar assets)
- Docker + Docker Compose (opcional)

---

## 💻 Instalação Local

```bash
# Clone o repositório
git clone https://github.com/neiagp/sistema-autenticacao.git
cd sistema-autenticacao

# Instale as dependências PHP
composer install

# Copie o arquivo .env
cp .env.example .env

# Gere a chave da aplicação
php artisan key:generate

# Configure o banco no .env (MySQL ou SQLite)
# Exemplo MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# Rode as migrations e seeders
php artisan migrate --seed

# Inicie o servidor
php artisan serve
```

---

## 👥 Acesso à Aplicação

Após iniciar o servidor, acesse:

http://localhost:8000

Use um dos usuários seed criados:

Email: user_zero@sistemaautenticacao.com.br
Senha: senha@12345678

Email: user_um@sistemaautenticacao.com.br
Senha: senha@123

Email: user_dois@sistemaautenticacao.com.br
Senha: senha@456

---

## 🧪 Rodando os Testes

# Rodar todos os testes
```bash
php artisan test
```

# Rodar um teste específico
```bash
php artisan test tests/Feature/AutenticacaoTeste.php
```

---

## 🧬 Estrutura de Testes Implementados
- Registro de usuário
- Validação de senha fraca
- Verificação de e-mail duplicado
- Login válido e inválido
- Acesso negado a usuários não autenticados
- Edição de perfil
- Alteração de senha
- Logout