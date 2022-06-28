# Idea-Inker
# Getting started
### Install Vendor
Make a composer install

    composer install
### Database
Create the database with

    php bin/console d:d:c
or
    
    symfony console d:d:c
push the database schema

    php bin/console d:s:u --force
or

    symfony console d:s:u --force
### Generate JWT certificate
    
    bin/console lexik:jwt:generate-keypair
