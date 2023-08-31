# TrueTalent Admin & APIs
** Project Description

## Requirements
- PHP 5.3 and above.
- Laravel - ?
- MySQL - ?
- Built-in libcurl support.

## Installation
- Edit .env file: add DB information
- ```composer install```
- ```php artisan key:generate``` [optional]
- ```php artisan migrate``` [for dB generation]
- ```php artisan passport:keys```
- ```php artisan db:seed --class=AuthTableSeeder```
- ```php artisan serve``` [Run Local Server: 127.0.0.0]

## Folder Structure
In this example, we have some files and folder that you need to concentrate about, as follows:

- ### Controllers
  - Controller communicate with DAO and DAO will call the Models
  - Follow the Base Controller naming conventions.
  - Always extends then `BaseController`
- ### Logger
    - `logInfo`, `logWarning`, `logError` : Use logger for your debugging and error handling.
- ### Routes
    - Use comment to create sections and always group the routes and follow the same naming conventions.
    - Always add `->name('{{unique_value}}')` to define & use the route in blade.php
- ### Models
    - It represents the database schema.
- ### DataTables
    - **Never** hardcode datatable column name in blade.php
- ### Utils
    - Use utils classes and whenever required, always add new function in specific class and call it.

## Constants

##### **[Strictly DON'T HARDCODE ANY STRING]**


## Rules

- Strictly DON'T HARDCODE ANY STRINGS.
- Always use try catch and in catch block use `Logger::logError($exception)`
- Nomenclature for Constants -> {{featureName_functionality}} e.g. -> CLIENTS_DASHBOARD, CLIENTS_ADD_NEW
- Always commit code, before you signoff for the day and take frequent pulls.
- Third Party Library: Always create a wrapper class for third party library and use that wrapper class in main code
- Never commit on `master` branch.


## Deployment Architecture
- master -> production
- pp-master -> preprod
- feature-master -> test/dev

