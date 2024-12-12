Here's the updated installation guide for your Laravel and Vue.js project with the addition of cloning the project from the specified GitHub repository:

1. **Prerequisites Installation**:
    - Ensure that PHP 8.2 and Composer are installed on your machine.
    - Make sure Node.js (v20.18.1) is installed.

2. **Clone the Repository**:
    - Clone your project from GitHub:
      ```bash
      git clone https://github.com/navdipv/laravel_vue_curd.git
      ```
    - Navigate to the cloned project directory:
      ```bash
      cd laravel_vue_curd
      ```

3. **Install PHP and JavaScript Dependencies**:
    - Install Composer and NPM dependencies:
      ```bash
      composer install
      npm install
      ```

4. **Environment Configuration**:
    - Copy the `.env.example` file to create a new `.env` file:
      ```bash
      copy .env.example .env
      ```
    - Adjust the `.env` file as needed, including database configurations and other environment-specific settings.

5. **Generate Application Key**:
    - Generate a new application key:
      ```bash
      php artisan key:generate
      ```

6. **Optimize Configuration**:
    - Run the optimize command to cache the bootstrap files:
      ```bash
      php artisan optimize
      ```

7. **Create a Virtual Host**:
    - Configure a virtual host in your web server with the URL `http://laravel_vue_app.localhost`.
    - Ensure your server is configured to point to the `public` directory of your Laravel project.

8. **Run Database Migrations**:
    - Perform the database migrations to set up your database schema:
      ```bash
      php artisan migrate
      ```

9. **Compile Assets**:
    - Compile your Vue.js components and other assets:
      ```bash
      npm run dev
      ```

10. **Testing the Application**:
    - Open your browser and visit `http://laravel_vue_app.localhost` to see if the application loads properly.

11. **Troubleshooting**:
    - If you encounter errors during the installation, check the Laravel and NPM logs for details. Ensure that all dependencies match the versions specified in your `package.json` and `composer.json`.

This guide includes all the steps necessary to set up and run your Laravel and Vue.js project from the GitHub repository. If you have any further questions or need additional assistance, feel free to ask!