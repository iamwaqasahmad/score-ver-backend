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
