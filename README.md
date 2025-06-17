# Activity Management System

This is a system for managing activities. Primary management is done via the Filament admin panel. A basic frontend is included for display. An API provides access to activity data.

## How to Set Up

Follow these steps to deploy Activity Management System:

1.  **Clone the Repository**

    ```bash
    git clone https://github.com/lewenbraun/management-activities
    cd management-activities
    ```

2.  **Configure Environment**

    Copy the sample environment file:

    ```bash
    cp .env.example .env
    ```

3.  **Start Docker Containers**

    Launch the application using Docker Compose:

    ```bash
    docker compose up -d
    ```

4.  **Generate Application Key**

    Generate the Laravel application key:

    ```bash
    docker compose exec ma-backend php artisan key:generate
    ```

5.  **Run Migrations**

    Initialize the database schema with:

    ```bash
    docker compose exec ma-backend php artisan migrate
    ```

6.  **Preparing storage**

    Create storage simlink:

    ```bash
    docker compose exec ma-backend php artisan storage:link
    ```

7.  **Run Seeder**

    Initialize the database schema with:

    ```bash
    docker compose exec ma-backend php artisan db:seed
    ```

## **Pages**

**Admin panel:** [http://localhost/admin/](http://localhost/admin/)

**Main page:** [http://localhost/](http://localhost/)

**Scribe documentation:** [http://localhost/docs](http://localhost/docs)

## **Admins and users data after seeders launch**

**Super admin:**

All rights and permissions (also management of roles and rights):

```bash
login: superadmin@example.com
password: password
```

**Regular admin:**

Read-only rights:

```bash
login: admin@example.com
password: password
```

**Regular user:**

No rights or permissions:

```bash
login: user@example.com
password: password
```
