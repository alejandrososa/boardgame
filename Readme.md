Getting Started with Boardgame
------------------------------

We want to play a simple board game. There will be 2 players, playing in the same browser. 
No need to make it a real multiplayer game, also not for authentication or structured storage.

### Prerequisites

You're going to need:

 - **PHP 7.1**

### Installation

Clone this repository with `git clone https://github.com/alejandrososa/boardgame.git`

```
cd boardgame && composer install
```

### Running the app

Initialize and start the app. You can either do this locally in root of project:

```
 php -S localhost:8080 -t public/
```

### Running tests

Initialize and start the app. You can either do this locally in root of project:

```
 cp phpunit.xml.dist phpunit.xml
 php ./vendor/phpunit/phpunit/phpunit --configuration phpunit.xml
```
You can now see the docs at http://localhost:8080. Whoa! That was fast!