# Installation native

Every components which are necessary to run `es-sandbox` could be installed native in your system.

## Requirements:
- Php 5.6 or 7
- [EventStore](https://geteventstore.com/downloads/)
- [MySQL](http://dev.mysql.com/doc/refman/5.7/en/installing.html)
- [Composer](https://getcomposer.org/)

## Installation
1. Clone repository:  
    ```
    git clone git@github.com:lzakrzewski/es-sandbox.git
    ```
2. Go to project directory:  
    ```
    cd es-sandbox
    ```
3. Install dependencies with `composer`:  
    ```
    composer install
    ```
4. Composer will ask you about configuration of `MySQL` and `Event-Store`, so provide it. Example:
```yaml
parameters:
    secret: ThisTokenIsNotSoSecretChangeIt
    event_store_host: 172.17.0.124
    event_store_port: 2113
    event_store_user: admin
    event_store_password: changeit
    database_host: 172.17.0.123
    database_port: 3306
    database_name: es-sandbox
    database_user: root
    database_password: changeit
```
5. Setup database `MySQL`
```
composer setup-database
```