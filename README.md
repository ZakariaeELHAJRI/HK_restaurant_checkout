# HK Caisse API Usage Guide

This repository contains the HK Caisse API for managing user data. Follow the instructions below to set up and use the API.

## Installation

1. Clone the repository:
  ```bash
   git clone https://github.com/Mecreal/hk_caisse.git --branch Code-PHP
  ```

2. Configure the Database:
- Open the `api/config/database.php` file.
- Update the database configuration values according to your database setup.

3. Import Database Schema:
- In your phpMyAdmin interface, import the `db_schema.sql` file provided in the repository. This will set up the necessary database structure.

4. Change the $base_path to your own path folder 
- In index.php don't forget to change the $base_path /api_orders/api/  with your own path


## Inserting Test Data

To insert test data into the database, follow these steps:

1. **Creating a New User:**
- Open the `api/controllers/UserController.php` file.
- Comment out the authentication check on line 14 to temporarily disable authentication. The line should look like this:
  ```php
  // RoleMiddleware::authorizeRoles(array("gestionnaire"));
  ```
- Save the file.


2. **Inserting a New User:**
- Use a tool like Postman to send a POST request to the API endpoint to create a new user.
- Send a JSON payload containing user data, including username, email, password, telephone, and role.
- The API will insert the new user into the database.

3. **Enabling Authentication:**
- After successfully creating the initial user, uncomment the line you commented out in step 1 to re-enable authentication.

## Usage

The HK Caisse API is now set up and ready for use. You can perform various operations using the provided API endpoints to manage user data.

For detailed API documentation and available endpoints, refer to the [API documentation](link-to-api-docs). Later...

## Contributing

If you'd like to contribute to this project, feel free to submit a pull request or open an issue.

