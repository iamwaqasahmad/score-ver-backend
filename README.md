## Requriments
1. Docker

## Installation 
1. Clone and cd to the repo
1. <code>composer install</code> in the repo
1. <code>./vendor/bin/sail up</code>
1. <code> Docker ps </code> to find the constainer name
1. <code> docker exec -it CONTAINER_NAME bash </code>
1. <code> php artisan migrate </code>
1. <code>php artisan db:seed </code>

## API endpoints
1. /login  <code> Post</code> 
2. /register <code> Post</code> 
3. /logout <code> Post</code> 
4. /games <code>  Get</code> 
5. /games <code> Post</code> 
6. /games/{id} <code> Put</code> 
7. /games/{id} <code> Get</code> 
8. /games/{id} <code> Delete </code> 
