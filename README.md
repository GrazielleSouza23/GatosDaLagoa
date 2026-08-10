# 🐱 Gatos da Lagoa

Sistema web institucional para a **ONG Gatos da Lagoa Taquaral**, desenvolvido em PHP e atualmente em processo de evolução arquitetural.

O projeto começou como uma aplicação PHP tradicional e está sendo reorganizado para uma estrutura mais moderna, baseada em **MVC**, com separação entre controllers, models, views, configurações e arquivos públicos.

> 💡 **Este projeto é a evolução do sistema original da Gatos da Lagoa**, mantendo a identidade visual e as funcionalidades principais enquanto sua arquitetura interna é modernizada.

---

## 📌 Sobre o projeto

O **Gatos da Lagoa** é um sistema voltado para divulgação e gerenciamento das ações da ONG, incluindo:

- 🏠 Informações institucionais
- 📅 Eventos
- 🖼️ Galeria de imagens
- 🐱 Histórias de sucesso
- 💰 Doações
- 🤝 Voluntariado
- 🔐 Área administrativa
- 🔌 API REST

A versão atual representa uma evolução do projeto original, com foco em:

- organização do código;
- separação de responsabilidades;
- segurança;
- manutenção;
- escalabilidade;
- futuras expansões.

---

## 🛠️ Tecnologias utilizadas

| **Tecnologia** | **Utilização** |
|---|---|
| **PHP 8.2+** | Linguagem principal |
| **MySQL** | Banco de dados |
| **HTML5** | Estrutura das páginas |
| **CSS3** | Estilização |
| **JavaScript** | Interações no navegador |
| **Composer** | Gerenciamento de dependências e autoload |
| **MVC** | Organização arquitetural |
| **API REST** | Comunicação com recursos do sistema |
| **Apache** | Ambiente de produção compatível |
| **PHP Development Server** | Desenvolvimento local |

---

# 📂 Estrutura do projeto

```text
GatosDaLagoa/
│
├── app/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Api/
│   │   └── Site/
│
│   ├── Core/
│   ├── Helpers/
│   ├── Models/
│
│   └── Views/
│       ├── admin/
│       ├── layouts/
│       └── site/
│
├── config/
│   ├── autoload.php
│   └── config.php
│
├── database/
│   ├── criar_banco_e_tabelas.sql
│   ├── criar_banco_mysql.sql
│   └── criar_tabelas_mysql.sql
│
├── public/
│   ├── api/
│   ├── assets/
│   ├── .htaccess
│   └── index.php
│
├── storage/
│   └── logs/
│
├── .gitignore
├── composer.json
├── composer.lock
└── README.md
```

### 🔐 Por que existe a pasta `public/`?

A pasta **`public/`** funciona como o **diretório público da aplicação**.

O objetivo é evitar que arquivos internos, configurações, controllers, models e outras partes da aplicação sejam diretamente acessíveis pelo navegador.

A estrutura segue, portanto, uma separação semelhante a:

```text
Código interno
    ↓
app/
config/
database/
storage/

Código público
    ↓
public/
```

---

# 🏗️ Arquitetura

O projeto utiliza uma arquitetura baseada no padrão **MVC — Model, View, Controller**.

A aplicação também possui uma camada **`Core`**, responsável pelos componentes fundamentais do sistema.

---

## 📦 Models

Os **Models** são responsáveis pela representação e manipulação dos dados da aplicação.

Localização:

```text
app/Models/
```

Exemplos:

```text
Administrador.php
Configuracao.php
Evento.php
Galeria.php
HistoriaSucesso.php
RedeSocial.php
TopicoAdocao.php
```

---

## 🎨 Views

As **Views** são responsáveis pela apresentação das informações ao usuário.

Localização:

```text
app/Views/
```

Organização:

```text
app/Views/
├── admin/
├── layouts/
└── site/
```

As views públicas ficam em:

```text
app/Views/site/
```

As views administrativas ficam em:

```text
app/Views/admin/
```

Os layouts compartilhados ficam em:

```text
app/Views/layouts/
```

---

## 🎮 Controllers

Os **Controllers** recebem as requisições e coordenam a execução das funcionalidades da aplicação.

Eles estão divididos em três grupos:

### Site

Responsáveis pelas páginas públicas:

```text
app/Controllers/Site/
```

### Admin

Responsáveis pelas funcionalidades administrativas:

```text
app/Controllers/Admin/
```

### API

Responsáveis pelos endpoints da API REST:

```text
app/Controllers/Api/
```

---

## ⚙️ Core

A pasta **`Core`** contém componentes fundamentais da aplicação.

Localização:

```text
app/Core/
```

Entre eles estão:

- Router
- Database
- Auth
- Request
- Response
- Controller base
- Model base
- ApiController

---

# 📦 Composer

O projeto utiliza o **Composer** para gerenciamento de dependências e carregamento automático das classes.

O arquivo principal é:

```text
composer.json
```

O projeto utiliza **autoload PSR-4** para o namespace:

```text
App\
```

Apontando para:

```text
app/
```

Para instalar as dependências:

```bash
composer install
```

Para atualizar as dependências:

```bash
composer update
```

> ⚠️ Durante o desenvolvimento, prefira **`composer install`** quando estiver trabalhando com o **`composer.lock`**, pois ele mantém as versões definidas no projeto.

---

# ⚙️ Configuração do ambiente

Informações sensíveis, como credenciais do banco de dados, **não devem ser armazenadas diretamente no código-fonte nem enviadas para o GitHub**.

O projeto utiliza um arquivo:

```text
.env
```

para configurações locais e sensíveis.

O **`.env`** está incluído no **`.gitignore`**:

```text
.env
.env.local
.env.production
.env.development
```

Portanto, cada ambiente deve possuir sua própria configuração.

> 🔒 **Nunca envie senhas, credenciais do banco de dados, chaves de API ou outras informações sensíveis para o GitHub.**

---

# 🗄️ Banco de dados

Os scripts relacionados ao banco de dados estão localizados em:

```text
database/
```

O arquivo principal para criação do banco e das tabelas é:

```text
database/criar_banco_e_tabelas.sql
```

Também existem scripts mantidos para compatibilidade e referência:

```text
database/criar_banco_mysql.sql
database/criar_tabelas_mysql.sql
```

A estrutura do banco será evoluída conforme novas funcionalidades forem implementadas.

---

# 🚀 Instalação

## 1. Clonar o repositório

```bash
git clone https://github.com/GrazielleSouza23/GatosDaLagoa.git
```

Depois:

```bash
cd GatosDaLagoa
```

---

## 2. Instalar as dependências

Execute:

```bash
composer install
```

O Composer irá utilizar o arquivo:

```text
composer.lock
```

para instalar as dependências definidas para o projeto.

---

## 3. Configurar o ambiente

Crie um arquivo **`.env`** na raiz do projeto:

```text
GatosDaLagoa/
└── .env
```

Exemplo de configuração:

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=gatos_da_lagoa
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ O conteúdo do **`.env`** é específico do ambiente e **não deve ser enviado para o GitHub**.

---

## 4. Configurar o banco de dados

Crie o banco de dados MySQL utilizando:

```text
database/criar_banco_e_tabelas.sql
```

Depois, configure as credenciais de conexão no ambiente local.

---

# 💻 Execução local

O servidor PHP deve ser iniciado a partir da pasta:

```text
public/
```

Isso ocorre porque **`public/`** é o diretório público da aplicação.

### Windows

No PowerShell:

```powershell
cd "C:\caminho\para\GatosDaLagoa\public"
```

Depois:

```bash
php -S localhost:8000
```

Se o servidor iniciar corretamente, será exibida uma mensagem semelhante a:

```text
PHP 8.2.x Development Server (http://localhost:8000) started
```

Depois, acesse:

```text
http://localhost:8000/
```

---

## 🔄 Alterando a porta

A porta **`8000`** não é obrigatória.

Se ela estiver ocupada, você pode utilizar outra:

```bash
php -S localhost:8080
```

Nesse caso:

```text
http://localhost:8080/
```

> 💡 O caminho da pasta pode ser diferente em cada computador. O importante é executar o servidor a partir da pasta **`public`** do projeto.

---

# 🌐 Páginas públicas

As principais rotas públicas atualmente disponíveis são:

| **Rota** | **Página** |
|---|---|
| **`/`** | 🏠 Página inicial |
| **`/quem-somos`** | 🐱 Quem Somos |
| **`/eventos`** | 📅 Eventos |
| **`/galeria`** | 🖼️ Galeria |
| **`/doacoes`** | 💰 Doações |

Exemplos:

```text
http://localhost:8000/
http://localhost:8000/quem-somos
http://localhost:8000/eventos
http://localhost:8000/galeria
http://localhost:8000/doacoes
```

---

# 🔐 Área administrativa

A aplicação possui uma área administrativa para gerenciamento do conteúdo.

A tela de login está disponível em:

```text
/admin/login
```

Exemplo:

```text
http://localhost:8000/admin/login
```

A estrutura administrativa inclui funcionalidades como:

- 🔑 Autenticação
- 📊 Dashboard
- 📅 Gerenciamento de eventos
- 🖼️ Gerenciamento da galeria
- 🐱 Gerenciamento de histórias de sucesso
- ⚙️ Configurações
- 👤 Perfil do administrador

Os controllers administrativos ficam em:

```text
app/Controllers/Admin/
```

E as views:

```text
app/Views/admin/
```

---

# 🔌 API REST

O projeto possui uma API REST que faz parte da evolução da arquitetura do sistema.

Os controllers da API estão localizados em:

```text
app/Controllers/Api/
```

Entre eles estão:

```text
AuthApiController.php
ConfiguracaoApiController.php
EventoApiController.php
GaleriaApiController.php
HistoriaApiController.php
RedeSocialApiController.php
TopicoApiController.php
```

O ponto de entrada público da API está localizado em:

```text
public/api/index.php
```

> 🚧 A API continua em desenvolvimento e poderá receber novos endpoints conforme o projeto evoluir.

---

# 🔒 Segurança e arquivos ignorados

O projeto utiliza **`.gitignore`** para evitar que arquivos desnecessários ou sensíveis sejam enviados ao repositório.

Entre os arquivos ignorados estão:

```text
.env
.env.local
.env.production
.env.development
```

Também são ignorados:

```text
/vendor/
```

Logs:

```text
/storage/logs/*.log
```

Arquivos de sistema:

```text
.DS_Store
Thumbs.db
```

Configurações de IDE:

```text
.vscode/
.idea/
```

Arquivos temporários:

```text
*.tmp
*.cache
```

---

# 🌿 Git e GitHub

O projeto utiliza **Git** para controle de versão e **GitHub** para hospedagem do código.

## Repositório

**Gatos da Lagoa — GitHub**

https://github.com/GrazielleSouza23/GatosDaLagoa

---

## 📚 Histórico do projeto

O projeto atual é uma evolução do sistema originalmente desenvolvido e publicado no mesmo repositório.

A versão original utilizava uma estrutura PHP tradicional, com arquivos organizados diretamente em diretórios como:

```text
admin/
assets/
includes/
pages/
```

A nova versão está sendo reorganizada para:

```text
app/
config/
database/
public/
storage/
```

Essa mudança tem como objetivo melhorar a organização e preparar o sistema para futuras expansões.

---

# 🧑‍💻 Fluxo de desenvolvimento

Durante o desenvolvimento local, o fluxo recomendado é:

### 1. Entrar na pasta do projeto

```powershell
cd "C:\caminho\para\GatosDaLagoa\public"
```

### 2. Atualizar o projeto

```bash
git pull origin main
```

### 3. Instalar dependências

```bash
composer install
```

### 4. Iniciar o servidor

```bash
cd public
php -S localhost:8000
```

### 5. Desenvolver e testar

Acessar:

```text
http://localhost:8000/
```

### 6. Verificar alterações

Na raiz do projeto:

```bash
git status
```

### 7. Adicionar alterações

```bash
git add .
```

### 8. Criar um commit

```bash
git commit -m "Descrição da alteração"
```

### 9. Enviar para o GitHub

```bash
git push origin main
```

---

# 🎯 Objetivos da evolução

A evolução do projeto busca:

- 🧹 Organizar melhor o código
- 🏗️ Aplicar arquitetura MVC
- 📦 Utilizar Composer
- 🔄 Implementar autoload PSR-4
- 🔌 Desenvolver uma API REST
- 🔐 Melhorar a segurança
- 🧩 Separar responsabilidades
- 🛠️ Facilitar a manutenção
- 📈 Preparar o sistema para futuras funcionalidades
- 🎨 Manter e aprimorar a identidade visual original
- 🐱 Continuar atendendo às necessidades da ONG Gatos da Lagoa Taquaral

---

# 🚧 Status do projeto

> **🟡 Em desenvolvimento**

O projeto está passando por uma migração gradual da estrutura PHP tradicional para uma arquitetura mais organizada, baseada em **MVC**, com **API REST**, **Composer**, separação de camadas e diretório público.

As funcionalidades existentes estão sendo migradas e testadas progressivamente.

Novas funcionalidades e melhorias serão adicionadas conforme o desenvolvimento avançar.

---

# ❤️ Autoria

Projeto desenvolvido para a:

**ONG Gatos da Lagoa Taquaral**

Repositório mantido por:

**Grazielle Souza**