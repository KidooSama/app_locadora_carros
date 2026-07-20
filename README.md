# 🚗 Sistema de Locação de Veículos

![PHP](https://img.shields.io/badge/PHP-8.1-blue?logo=php)
![Laravel](https://img.shields.io/badge/Laravel-8-red?logo=laravel)
![Vue](https://img.shields.io/badge/Vue-2-brightgreen?logo=vue.js)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?logo=mysql)
![JWT](https://img.shields.io/badge/JWT-Auth-black)
![License](https://img.shields.io/badge/License-MIT-green)

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel">
</p>

## 📌 Sobre o projeto

Aplicação web completa para gerenciamento de locação de veículos, desenvolvida como projeto de portfólio para demonstrar domínio de **Back-end + Front-end integrados**.

O foco não é quantidade de funcionalidades, e sim código limpo, estrutura clara e fluxo real de cadastro, busca, edição, exclusão e finalização de locações.

## 🎯 Objetivo

- Praticar Laravel 8 com PHP 8.1
- Consolidar relacionamentos Eloquent e autenticação
- Integrar API Laravel + Vue 2 sem dependências desnecessárias
- Entregar um projeto didático, organizado e fácil de entender

## 🔁 Funcionalidades principais

### 🏢 Cadastros básicos

| Recurso | Ações |
|--------|-------|
| 🏭 Marcas | CRUD |
| 🚙 Modelos | CRUD |
| 🚗 Carros | CRUD |
| 👤 Clientes | CRUD |

### 📋 Aluguéis

| Recurso | Ações |
|--------|-------|
| ✅ Listagem de locações | Pesquisa + paginação |
| ➕ Nova locação | Seleciona cliente + carro disponível |
| ✏️ Alterar locação | Modifica dados da reserva |
| 🏁 Finalizar locação | Registra km final + data final realizada |

### 📊 Painel

- Médias e totais de negócio
- Locações próximas a devolver
- Visão rápida do status da frota

## 🧩 Stack utilizada

```text
Back-end
- Laravel 8
- PHP 8.1
- MySQL
- JWT Authentication
- Repositórios
- Form Requests

Front-end
- Vue 2
- Axios
- Bootstrap 4
- SASS / app.scss

Padrões
- API REST
- Vuex Store
- Componentes reutilizáveis
```

## 🗂️ Estrutura do projeto

```text
app/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Models/
└── repositories/

resources/js/
├── app.js
├── components/
│   ├── Marcas.vue
│   ├── Modelos.vue
│   ├── Carros.vue
│   ├── Clientes.vue
│   ├── Locacoes.vue
│   ├── Table.vue
│   ├── Card.vue
│   ├── Modal.vue
│   ├── Alert.vue
│   └── Home.vue
└── sass/

routes/
└── api.php
```

## 🔎 Regras de negócio importantes

- Carro precisa estar **disponível** para ser alugado
- Locação finalizada **não pode ser alterada**
- Finalização só aceita `km_final` >= `km_inicial`
- Ao finalizar, o carro volta a ficar disponível automaticamente
- Modelo com carros vinculados **não pode ser excluído**

## 🚀 Como rodar o projeto

```bash
# 1. Clone o repositório
git clone https://github.com/<seu-usuario>/app_locadora_carros.git
cd app_locadora_carros

# 2. Instale dependências
composer install
npm install

# 3. Copie o arquivo de ambiente
cp .env.example .env

# 4. Gere a key
php artisan key:generate

# 5. Configure o banco de dados no .env
DB_DATABASE=lc
DB_USERNAME=root
DB_PASSWORD=

# 6. Rode as migrations
php artisan migrate

# 7. Popule o banco com dados de exemplo
php artisan db:seed

# 8. Compile os assets
npm run dev

# 9. Inicie o servidor
php artisan serve
```

Acesse:
- Front-end: `http://127.0.0.1:8000`
- API: `http://127.0.0.1:8000/api/v1`

## 🔑 Usuário padrão

| Campo | Valor |
|------|------|
| Email | admin@teste.com |
| Senha | 123456 |

## 📦 Executar seeders

```bash
php artisan db:seed
```

Scripts úteis:
- `DatabaseSeeder` carrega `Marcas`, `Modelos`, `Clientes`, `Carros`, `Locações` e `User`

## ✅ Checklist do que já está funcionando

- [x] Cadastro de Marcas
- [x] Cadastro de Modelos
- [x] Cadastro de Carros
- [x] Cadastro de Clientes
- [x] Listagem com paginação
- [x] Pesquisa por campos
- [x] Aluguel de carros
- [x] Finalização de locação
- [x] Painel com métricas

## 📌 Melhorias planejadas

- [ ] Emails com Resend
- [ ] Utilização de cache com Redis
- [ ] Maior personalização com a UI
- [ ] Integração de login com google
- [ ] Deploy público para apresentação
- [ ] Filtros avançados
- [ ] Relatórios de faturamento

## 📸 Preview

> Em breve: GIFs ou prints das principais telas.

## 👨‍💻 Autor

Desenvolvido por Talles Emanuel  
Projeto com fins didáticos e portfólio.

## 📄 Licença

Este projeto está licenciado sob a MIT License.
