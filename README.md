# Olejos Repo

## Setup Instructions

1. **Clone the repository:**

    ```sh
    git clone https://github.com/your-username/olejos-backend.git
    cd aladyn-backend
    ```

2. **Install dependencies:**

    ```sh
    composer install
    ```

3. **Copy the `.env` file:**

    ```sh
    cp .env.example .env
    ```

    or

    ```sh
    copy .env.example .env
    ```

4. **Generate the application key:**

    ```sh
    php artisan key:generate
    ```

5. **Configure the `.env` file:**

    - Update the database configuration to match your PostgreSQL setup:

        ```
        DB_CONNECTION=pgsql
        DB_HOST=127.0.0.1
        DB_PORT=5432
        DB_DATABASE=your_database_name
        DB_USERNAME=your_database_user
        DB_PASSWORD=your_database_password
        ```

    - Add or update the SMS API credentials:
        ```
        SMS_API_AUTH_TOKEN=your_sms_api_auth_token
        SMS_API_FROM_NUMBER=your_sender_number
        ```
    - You can use Test as value for the SMS_API_NUMBER if you use sms_api_linkmobility as your sms api provider for testing purposes.

6. **Run the database migrations:**

    ```sh
    php artisan migrate
    ```

7. **Seed the database:**

    ```sh
    php artisan db:seed
    ```

8. **Create a personal Access Token:**

    ```sh
    php artisan passport:client --personal
    ```

9. **Copy the `.env.testing` file for the testing environment:**

    ```sh
    cp .env.testing.example .env.testing
    ```

    or

    ```sh
    copy .env.testing.example .env.testing
    ```

10. **Generate the application key for the testing environment:**

    ```sh
    php artisan key:generate --env=testing
    ```

11. **Test the app:**

    ```sh
    php artisan test
    ```

12. **Start the queue worker:**

    To process queued jobs (e.g., sending SMS), run the following command:

    ```sh
    php artisan queue:work
    ```

13. **Serve the application:**

    ```sh
    php artisan serve
    ```


