## Teste Backend 123 Milhas

Introdução
Atualmente utilizamos diversas APIs para buscar voos, e após receber os resultados é
necessário fazer o agrupamento entre as idas e voltas.
Nesse teste você irá desenvolver uma API que faz esse agrupamento de voos.
Disponibilizamos uma API onde você irá consultar os vôos a serem agrupados. No total são
15 voos, categorizados em 2 tipos de tarifa.
Requisitos

Entregas

- Será necessário entregar todo o código gerado no teste
- Como é uma API você também pode anexar sua rota.
- Entregue uma documentação com todos os passos para executar seu projeto.
- Disponibilize o código e a documentação em um link no github, ou em um arquivo
zip para download.

[opcional]: ​ Utilizar o GitHub para fazer a entrega é um diferencial
[opcional]: ​ Utilizar o Swagger, Postman ou algo similar para documentar sua rota é um
diferencial
[opcional]:​ Disponibilizar o teste na internet, para que possa ser testado via navegador ou
postman é um diferencial
Pontos de Avaliação

- Toda a estrutura da sua API; (Rota, HTTP response, HTTP Status, etc..)
- Nomenclatura e padronização das suas variáveis, funções, classes;
- Separação de responsabilidades;
- Lógica e otimização de processamento;

# Visão geral
A API retorna voos agrupados em voos de ida e volta, tarifa e preços, após uma consulta a outra API externa.

A API foi construída utilizando o framework Laravel 8.x.

# Requisitos
Composer

PHP 7.4

Laravel 8.x
# Instalação

## Clone este repositório
$ git clone https://github.com/baetaDev/123Milhas.git

## Instale as dependências
$ composer install

## Execute a aplicação em modo de desenvolvimento
$ php artisan serve

# Rota da API
## Tipo GET

<h1 align="center">
    <a href="http://127.0.0.1:8000/api/flights">🔗 http://127.0.0.1:8000/api/flights</a>
</h1>

## Documentação da rota

<h1 align="center">
    <a href="https://documenter.getpostman.com/view/14679845/U16jLk56">🔗 https://documenter.getpostman.com/view/14679845/U16jLk56
</a>
</h1>
