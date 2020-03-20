# Ecommerce-app
Create project
````
composer create-project symfony/website-skeleton
````
Run server
````
symfony server:start
````
Make entity
````
php bin/console make:entity
````
Make Controller
````
php bin/console make:controller
````
Set up and Configure Database
````dotenv
###> doctrine/doctrine-bundle ###
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
###< doctrine/doctrine-bundle ###
````
Create a DB 
````
php bin/console doctrine:database:create
````
Create a migration and execute for application it
````
php bin/console make:migration
php bin/console doctrine:migrations:migrate
````
Install SDK 
````
composer require cloudinary/cloudinary_php
````
Set your Cloudinary credentials
````dotenv
#Cloudinary Details
CLOUD_NAME=YOUR_CLOUD_NAME
API_KEY=YOUR_API_KEY
API_SECRET=YOUR_API_SECRET
````
Create package.json, webpack.config.js and add node_modules to .gitignore
````
composer require symfony/webpack-encore-pack
````
Add react dependencies
````
yarn add --dev react react-dom prop-types babel-preset-react
````
Install axios package
````
yarn add axios reactstrap --save
````
