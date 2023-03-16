## Executing the project in the dev env with Docker Compose


### Before run the application on local environment, you need to startup docker and configure the application. Follow the steps bellow:
Start Docker Compose services
```
docker-compose up --build -d
```

Create the .env file within the .env.example content
```
docker-compose exec app cp .env.example .env
```

Install the PHP dependencies
```
docker-compose exec app composer install
```

Generate Laravel application Key
```
docker-compose exec app php artisan key:generate
```

Create the database tables structure
```
docker-compose exec app php artisan migrate --seed
```

### After these steps the application with run on http://localhost:9050

## BRAZIL

### API [HOMOLOGATION]

```
https://84z54slgkc.execute-api.us-east-1.amazonaws.com/v1
```

### API [PRODUCTION]

```
https://olmuhz0k9i.execute-api.us-east-1.amazonaws.com/v1
```

## MEXICO

### API [HOMOLOGATION]

```
https://fvljn7qryc.execute-api.us-east-1.amazonaws.com/v1
```

### API [PRODUCTION]

```
https://3aesx60fe4.execute-api.us-east-1.amazonaws.com/v1
```

### PROCESS OF TRACKING EMAILS ON AWS

1. CREDENTIALS SES AWS
2. CREATE SNS TOPIC
   NAME: tracking
   TYPE: Standard
3. CREATE SNS SUBSCRIPTION
   ENDPOINT: URL_API_ENDPOINT_CONTROLLER_SNS
4. CREATE CONFIGURATIONS IN SES TO CONNECT CONFIGURATIONS SET WITH SNS

### TUTORIAL EXAMPLE

```
https://www.codingdiv.com/laravel/how-to-track-emails-in-laravel/
```
