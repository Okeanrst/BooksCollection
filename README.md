Zend Framework 2 - application
=======================

Introduction
------------
This is a simple, application using the ZF2 MVC layer and module
systems. This application provides a simple functional CRUD operation with collection books.

Installation
------------

Using Composer (recommended)
----------------------------
Clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/Okeanrst/BooksCollection.git
    cd BooksCollection
    php composer.phar self-update
    php composer.phar install

Web Server Setup
----------------

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/bookcollection/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/bookcollection/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

SQL Server Setup
----------------
DB name:'Books', user: 'booksreader', password => '123456789'

SQL schema /data/SQL/Books.sql

Registration disabled. User: 'admin', password: '123456'
