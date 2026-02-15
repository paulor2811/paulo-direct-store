# Paulo Direct Store

Uma aplicação de e-commerce desenvolvida com Laravel.

## Requisitos

- PHP 8.2+
- Composer
- Node.js & NPM
- PostgreSQL
- Docker (Opcional, mas recomendado)

## Instalação

1.  **Clone o repositório**
    ```bash
    git clone git@github.com:paulor2811/paulo-direct-store.git
    cd paulo-direct-store
    ```

2.  **Instale as dependências do PHP**
    ```bash
    composer install
    ```

3.  **Instale as dependências do Frontend**
    ```bash
    npm install
    ```

4.  **Configure o ambiente**
    Copie o arquivo de exemplo `.env` e configure suas variáveis de ambiente (banco de dados, etc).
    ```bash
    cp .env.example .env
    ```

5.  **Gere a chave da aplicação**
    ```bash
    php artisan key:generate
    ```

6.  **Configure o Banco de Dados**
    Certifique-se de que o PostgreSQL esteja rodando e as credenciais no `.env` estejam corretas. Em seguida, rode as migrações:
    ```bash
    php artisan migrate --seed
    ```

7.  **Link Simbólico para Storage**
    Para que as imagens dos produtos funcionem corretamente:
    ```bash
    php artisan storage:link
    ```

8.  **Compile os assets**
    ```bash
    npm run build
    ```

## Executando

Para iniciar o servidor de desenvolvimento:

```bash
php artisan serve
```

E em outro terminal, para assets em tempo real:

```bash
npm run dev
```

## Funcionalidades

-   Vitrine de Produtos
-   Carrinho de Compras
-   Integração com WhatsApp
-   **Painel Administrativo (`/admin/dashboard`)**:
    -   Gestão de Produtos (CRUD)
    -   Upload de Múltiplas Imagens
    -   Exclusão Segura
