# InteractiveBook
Here a short project in Symfony created by using a OpenAI API to generate interactive Book for a user 

## Installation and server start

1. Clone the repository
2. Run `composer install`
3. Run `npm install`
4. Run `npm run watch`
5. Run `symfony server:start`
6. Open your browser and go to `http://localhost:8000`
7. Enjoy the project
8. With XDEBUG Symfony : U sould use this line to activate the XDEBUG session `set XDEBUG_SESSION=1`.

## Project description

This project is a simple Symfony project that uses OpenAI API to generate a book for a user. The user can choose the title of the book and the number of chapters. The user can also choose the type of the book (fiction, non-fiction, mystery, etc.). The user can also choose the length of the book (short, medium, long). The user can also choose the language of the book (English, French, Spanish, etc.). The user can also choose the style of the book (romantic, thriller, horror, etc.). The user can also choose the tone of the book (formal, informal, etc.). The user can also choose the genre of the book (science fiction, fantasy, etc.).

## Project structure

The project is structured as follows:
```
    - The `src/Controller/BookController.php` file contains the controller that handles the requests for the book.
    - The `src/Controller/PageController.php` file contains the controller that handles the requests for the book page.
    - The `src/Controller/DefaultController.php` file contains the controller that handles the requests for the home page.
    - The `src/Controller/SecurityController.php` file contains the controller that handles the requests for the login page.
    - The `src/Controller/RegistrationController.php` file contains the controller that handles the requests for the registration page.
    - The `src/Controller/InfoController.php` file contains the controller that handles the requests for the info page like contact, about, etc...
```

## Contact 

For more information, please contact quentin.bastos@etu.univ-lyon1.fr or nathan.garcia@etu.univ-lyon1.fr

## Docker
```
    - docker-compose up -d
    - In docker "web" container : composer install
    - In docker "web" container : npm install
    - In docker "web" container : php bin/console doctrine:database:create
    - In docker "web" container : php bin/console doctrine:migrations:migrate
    
    docker-compose down   # Stop and remove the containers
    docker-compose up --build   # Rebuild the containers and start them
    
    REBUILD THE COMPOSER WHEN YOU ADD A NEW DEPENDENCY
```

### Install docker for development

    - install docker desktop
    - install wsl with : wsl --install in powershell
    - move the file to the wsl folder
    - run the docker-compose up -d
    - run the command to install the composer and npm dependencies
    - run the command to create the database
    - run the command to migrate the database
    - run the command to start the server
    - enjoy the project