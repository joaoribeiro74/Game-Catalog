# 🎮 GameHub

GameHub é uma aplicação web construída com Laravel que oferece uma plataforma para listar e avaliar jogos de videogame. Os usuários podem criar listas personalizadas de jogos, registrar avaliações com notas e acompanhar suas coleções pessoais de forma simples.

## Para que serve?

- Gerenciar listas personalizadas: Crie e gerencie listas de jogos, como favoritos, jogos a concluir, ou coleções temáticas.
- Avaliar jogos: Registre notas para cada jogo e visualize seu histórico.
- Visualizar seu perfil de usuário com suas coleções de jogos.

## Público-alvo

Jogadores que querem organizar e gerenciar suas coleções de jogos de forma prática e que queiram avaliar os jogos em que já passaram um tempo.

## Funcionalidades
- Cadastro e autenticação de usuários.
- Criação, edição e exclusão de listas de jogos.
- Visualização exclusiva por usuário.
- Integração com API externa.
- Avaliação de jogos.
- Gerenciamento de avatar do usuário.

## Print da Tela Principal

## 🛠️ Execução do Projeto

1. Certifique-se de que o laravel está instalado no seu computador. Siga o guia oficial:
https://laravel.com/docs/12.x

2. Clone o repositório:
```bash
git clone https://github.com/joaoribeiro74/Game-Catalog.git
cd Game-Catalog
```

3. Instale as dependências:
```bash
npm install && npm run build
composer install
```

4. Criar a variáveis de ambiente:
```
cp .env.example .env
```

5. Gere a chave da aplicação Laravel:
```
php artisan key:generate
```

6. Crie o arquivo do banco de dados SQLite:
```
mkdir database
touch database/database.sqlite
```

7. Rode as migrations e seeders:
```
php artisan migrate --seed
```

8. Rode o servidor local:
```
composer run dev
```