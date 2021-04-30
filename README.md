### Requirements

PHP 7.1

### Installation
Copy .env.example to .env

set EXCHANGE_RATES_API_KEY value

Install composer dependencies

```
composer install
```

### Run script

```
php index.php app:calculate-commissions data/input.csv
```

### Run tests

```
composer run test
```