# Employee Management Software API

Welcome to the Employee Management Software API! This project is designed to help you manage employee records, attendance, and more. Below, you'll find all the information you need to set up and run the project.

## Table of Contents

-   [Requirements](#requirements)
-   [Installation](#installation)
-   [Environment Configuration](#environment-configuration)
-   [Running the Application](#running-the-application)
-   [Features](#features)
-   [Testing](#testing)
-   [License](#license)

## Requirements

-   PHP 8.1 or higher
-   Composer
-   Docker & Docker Compose (for Laravel Sail)

## Installation

1. **Clone the repository**:

    ```bash
    git clone https://github.com/aimedidierm/employee-management-software-api.git
    cd employee-management-software-api
    ```

2. **Install dependencies**:

    ```bash
    ./vendor/bin/sail composer install
    ```

3. **Copy the environment file**:

    ```bash
    cp .env.example .env
    ```

4. **Generate an application key**:
    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

## Environment Configuration

1. Open the `.env` file and configure your database settings, email settings, and other environment variables as needed.

2. Set up Mailpit for email testing by adding the following to your `.env`:

    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=mailpit
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    ```

3. **Frontend URL**:
    ```env
    FRONTEND_APP_URL=http://your-vuejs-deployment-url
    ```

## Running the Application

1. **Start the application** using Laravel Sail:

    ```bash
    ./vendor/bin/sail up
    ```

2. **Access the API** at `http://localhost` or the specified port in your `docker-compose.yml`.

## Features

-   **Authentication System**: Users can register, log in, log out, and reset passwords using Laravel Sanctum. 🔒
-   **Employee CRUD**: Create, read, update, and delete employee records through the API with a VueJS frontend. 👥
-   **Attendance Management**: Record employee attendance with arrival and departure times. ⏰
-   **Email Notifications**: Employees receive email notifications when their attendance is recorded. 📧
-   **Attendance Reports**: Generate PDF and Excel reports of daily attendance data. 📊

## Testing

-   Run the tests using:
    ```bash
    ./vendor/bin/sail artisan test
    ```

### All features are fully tested to guarantee functionality! ✅
![tests](https://github.com/user-attachments/assets/804460cf-ba35-47d1-8470-565e575cb5c3)


## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

Thank you for checking out this project! If you have any questions or need assistance, feel free to reach out.
