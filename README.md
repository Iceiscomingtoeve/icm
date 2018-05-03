# ICM
ICE Is Coming To EVE website to handle API key through ESI system.

## Installation
+ Copy the ``web/conf/config.sample.ini`` file to ``web/conf/config.ini``
+ Update the ``web/conf/config.ini``
+ Put all of those file in your web server (with PHP, see req.)
+ Run the composer command: ``composer install --no-dev`` in the ``web`` folder

Some of the feature are not implemented yet, so be patient, will come soon(TM).

## Requirements
+ PHP version: 7.0.0 minimum
+ MySQL version: 5.7 maximum
+ PhpBB version: 3.3 minimum

## Structure
+ base: folder for DataBase documentation and creation script
+ log: log folder for apache/nginx and PHP and SQL
+ web: root folder for the web application
  + class: contains every class according to their namespaces
    + Controller: Defines the core of every Controller.
    + Dispatcher: Handles how to print the response to the User (HTML, JSON, else)
    + EVEOnline: Handles the ESI communication through OAUth2
    + Model: Handles the link between the application and the DataBase
    + Pages: Structure defining the ``Page``/``Action`` with its specific Controller and Views
    + Utils: Some utility class and methods (often in public static)
    + View: Defines some standard Views
  + conf: contains ini file with config variables
  + html: contains the html, js, and css files (ONLY !)
  + inc: small scripts included at each call for each pages.

## Enhancements (ToDo list)
+ Bootstrap design (coz' SmartPhone is life)
+ Install a Redis server to save some data in it instead of file caching (less I/O is better)
+ Build a top layer of Eseye to handle properly requests and Exceptions to the view
+ Adds a way to multi insert in batch instead of wild looping ``INSERT INTO...``
+ Adds a SQL abstraction for the ``WHERE`` part
