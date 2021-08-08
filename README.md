# Travel App API

### Tools

-   Laravel ^8.40

### Environment

-   PHP >= 7.3

### Setup Procedures

-   Clone repository.
-   Install php libraries: `composer install`
-   Create **.env** file: `cp .env.example .env`
-   Generate application key: `php artisan key:generate`
-   Fill up **.env** file with neccessary values:
    -   OPEN_WEATHER_API_KEY
    -   FOUR_SQUARE_ID
    -   FOUR_SQUARE_SECRET
-   Start php server: `php artisan serve`

### Available Endpoints

-   Weather check
-   Weather forecast
-   Venue categories
-   Venue search
-   Venue details
