# Dany Manager

## Prerequisites

Install the following tools before starting the project:

- PHP 8.1 or newer, with the `openssl` and `pdo_mysql` extensions enabled
- Composer
- Node.js and npm
- MySQL or MariaDB

Check that the commands are available in a new terminal:

```shell
php --version
composer --version
node --version
npm --version
```

## Installation

From the project root, install the JavaScript and PHP dependencies:

```shell
npm install
composer install
```

Create the database by running [taskMangerCreationScript.sql](src/configs/taskMangerCreationScript.sql) in MySQL or MariaDB. The script creates the `taskManager` database and its tables.

Then update the database connection values in [appsettings.json](src/configs/appsettings.json):

```json
{
	"database": {
		"host": "localhost",
		"port": 3306,
		"database": "taskManager",
		"username": "root",
		"password": ""
	}
}
```

Use the credentials configured by the local MySQL/MariaDB installation. The values above are only an example for a local installation using the default `root` account.

## Start the project

Run the PHP server in one terminal:

```shell
npm run php
```

The application is available at <http://localhost:8000>.

In a second terminal, start the Tailwind CSS watcher:

```shell
npm run css
```
