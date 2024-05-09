# lbaw2124

## Feup Tech
The main goal of the Feup-Tech project is the development of an information system with a web interface to support an online technology store created for the use of members of the FEUP community.

### Members: 
- António Ribeiro up201906761@edu.fe.up.pt
- Diogo Pereira up201906422@edu.fe.up.pt
- Joana Mesquita up201907878@edu.fe.up.pt
- Margarida Ferreira up201905046@edu.fe.up.pt


### 1. Installation

Final version of the source code: https://git.fe.up.pt/lbaw/lbaw2122/lbaw2124

#### To make sure all dependencies are installed:

> composer install

> php artisan clear-compiled

> php artisan optimize

#### To start the website localy:

 - make sure .env configs match the following

```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_SCHEMA=lbaw2124
DB_DATABASE=lbaw2124
DB_USERNAME=postgres
DB_PASSWORD=pg!password
```
- run the following commands:
> docker-compose up - start the docker container with postgres 

> php artisan db:seed - populate the database with the included sql file 

> php artisan serve - serve the website into the configurated host

#### To start the website from the production server:

- make sure .env configs match the following:

```
DB_CONNECTION=pgsql
DB_HOST=https://git.fe.up.pt/lbaw/lbaw2122/lbaw2124
DB_PORT=5432
DB_SCHEMA=lbaw2124
DB_DATABASE=lbaw2124
DB_USERNAME=lbaw2124
DB_PASSWORD=ORhzmEgI
```
- run the following commands:
> docker build -t git.fe.up.pt:5050/lbaw/lbaw2122/lbaw2124

> docker push git.fe.up.pt:5050/lbaw/lbaw2122/lbaw2124

<br>

### 2. Usage

URL to the product: http://lbaw2124.lbaw.fe.up.pt

#### 2.1. Administration Credentials

| Username | Password|
|-|-|
|ipsum@hotmail.edu|KCN92EMV1HG|

#### 2.2. User Credentials

|Type| Username | Password|
|-|-|-|
|Normal User|imperdiet.ullamcorper@protonmail.couk|VSO36ISH4DT|

<br>