1.  **Prerequisites**

    - PHP 8.2 or later
    - Composer

2.  **Get the code**

    Clone the repository or download the source.

3.  **Install dependencies**

    Run the provided installation script:

    ```bash
    bash install.sh
    ```

    This will execute:

    - `composer install`
    - `php artisan key:generate`
    - `php artisan migrate --seed`

    Alternatively, run the steps manually:

    ```bash
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed
    ```

4.  **Environment configuration**

    If not using the script, copy `.env.example` to `.env` and adjust values as needed. The database defaults to SQLite (`database/database.sqlite`), and the seeder populates it with sample clients and invoices.

5.  **Run development server**

    ```bash
    php artisan serve --host=0.0.0.0
    ```

    The application will be available at `http://localhost:8000`.

6.  **Run tests**

    ```bash
    php artisan test
    ```

7.  **Troubleshooting**

    - **Storage permissions**: ensure `storage/` and `bootstrap/cache/` are writable by the web server.
    - **Database**: if the SQLite file is missing, run `touch database/database.sqlite` and rerun migrations.