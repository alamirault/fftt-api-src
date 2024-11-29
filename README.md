# alamirault/fftt-api-src

This project is the source code of [alamirault/fftt-api](https://github.com/alamirault/fftt-api).


## How to contribute ?

Run docker environment

```bash
docker compose up -d
```

Go inside container 

```bash
docker exec -it fftt-api-src-php-1 bash
```

Install dependencies

```bash
composer i
```

Run tests (phpunit, php-cs-fixer, phpstan)
```bash
composer tests
```