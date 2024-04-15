# Project Name

Project name is TheFireFly.
It is based on the assignment provided by TheFirefly.
It is a basic curd application with queuing, notification,custom artisan command with other more features.
I have used AdminLTE 3.2.0 for dashboard.
Notification can be seen by clicking the notification icon which also provides the count for notification.
Notification are also sent through the mail.
Mysql is used for database.

## Table of Contents

-   [Installation](#installation)
-   [Usage](#usage)
-   [Testing](#testing)
-   [Code_of_Conduct](#code of Conduct)
-   [Contributing](#contributing)
-   [License](#license)

## Installation

1. Clone the repository:

    ```bash
    git clone <repository-url>
    ```

2. Install dependencies:

    ```bash
    cd project-folder
    composer install
    ```

3. Copy the `.env.example` file to `.env` and configure the database settings:

    ```bash
    cp .env.example .env
    ```

    Update the `.env` file with your database credentials(Mysql database is used).

    note: need to add the email credentails for sending email,notification

4. Generate the application key:

    ```bash
    php artisan key:generate
    ```

5. Run migrations:

    ```bash
    php artisan migrate
    ```

6. Run server:

    ```bash
    php artisan serve
    php artisan queue:work
    php artisan schedule:work
    ```

## Testing

    ```bash
    php artisan test
    ```
    ```bash
    php artisan test --filter=PurgeOldItems //for testing the custom artisan command run it seprate to refresh the database so as to assert that the task has been deleted from the table
    ```

    test the command
    important!!! => first remove the validation constrain => 'after:' . Date::tomorrow() from the TaskController so that we can add the task with past date to test the ageout task deletion command app:purge-old-items {age}

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
