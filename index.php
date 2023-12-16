<?php

require_once 'vendor/autoload.php';

use Bitress\CrudWrapper\CrudWrapper;

// Database connection details
$DB_HOST = 'your_host';
$DB_NAME = 'your_database_name';
$DB_USER = 'your_username';
$DB_PASS = 'your_password';


$crud = new CrudWrapper($DB_HOST, $DB_NAME, $DB_USER, $DB_PASS);

// Example of using the CRUD wrapper to insert data
$userData = [
    'username' => 'john_doe',
    'email'    => 'john@example.com',
    'password' => 'hashed_password',
];

$newUserId = $crud->create('users', $userData);
echo "New user created with ID: $newUserId\n";

// Example of using the CRUD wrapper to read data
$users = $crud->read('users', ['username' => 'john_doe']);
print_r($users);

// Example of using the CRUD wrapper to update data
$updateData = ['email' => 'john_new@example.com'];
$updateConditions = ['username' => 'john_doe'];
$crud->update('users', $updateData, $updateConditions);

// Example of using the CRUD wrapper to delete data
$deleteConditions = ['username' => 'john_doe'];
$crud->delete('users', $deleteConditions);

