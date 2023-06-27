# Instruções de Instalação - ged_laravel

Este arquivo fornece instruções passo a passo para instalar e configurar a aplicação Laravel "ged_laravel". Siga as etapas abaixo para configurar o ambiente de desenvolvimento.

## Pré-requisitos

Certifique-se de ter os seguintes requisitos instalados em seu sistema:

- PHP (versão 7.4 ou superior)
- Composer
- Servidor de banco de dados MySQL

## Passos de Instalação

1. Clone o repositório do GitHub:

   ```bash
    git clone https://github.com/MatheusPierozan/ged-laravel-master
   ```

2. Navegue até o diretório do projeto:

    ```bash
    cd ged_laravel
    ```

3. Faça uma cópia do arquivo .env.example e renomeie para .env:

    ```bash
    cp .env.example .env
    ```

4. Gere a chave de criptografia da aplicação:

    ```bash
    php artisan key:generate
    ```

5. Crie o banco de dados ged_laravel

6. No arquivo .env(passo 3) configure as informações do banco de dados.

7. Execute as migrações do banco de dados:

    ```bash
    php artisan migrate
    ```

8. Execute as sementes (seeders) do banco de dados:

    ```bash
    php artisan db:seed
    ```
