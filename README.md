# Assignment-API

this a an assignment for Reaktor trainee program application (API part done in laravel)

this is a self developed API made for the react app https://github.com/salwaachek1/Assignment.git
this part (API) does the calculations and return (winning ratio,number of games played, most played element,list of games played) to the front part (react app)


## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/7.x/#installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker). 

Clone the repository

    git clone https://github.com/salwaachek1/Assignment-API.git

Switch to the repo folder

    cd Assignment-API

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file

Generate a new application key

    php artisan key:generate
 
Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

# Testing API

Run the laravel development server

    php artisan serve

The api can now be accessed at

    http://localhost:8000/api
