# Narra

Manage your podcast and auto generate its RSS feed.

## Installation

Clone the repository by

```
git clone https://github.com/bruno-braga/narra.git
```

The current branch with all the feature is the develop branch so, after cloning the project ```cd narra && git checkout develop``` and then run

```
docker-compose up -d
```

All the containers will be pulled/built, it may take a few minutes but only on the first time, after that should be faster

After the containers are built

Create a .env file
```
    cp .env.example .env
```

Set the proper db password, host(by using dockers alias) and an encryption key by ```php artisan key:generate```.

Also dont forget to enter on the db container and create a database.

After that you can

```
docker-compose exec php php composer update
docker-compose exec php php composer install
```

Also this project uses vuejs so you need to 

```
docker-compose exec node npm install
docker-compose exec node npm run dev
```

After that you can access using localhost:8013/program to create a program and after that you can create an episode.
