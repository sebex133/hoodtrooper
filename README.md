# Hoodtrooper by Sebastian Pytel
Hoodtrooper - local travelers homeland

Tag your favourite places on Google Maps and share with people!

Interactive graphics project: https://app.moqups.com/MIgWDVdRnJ/view/page/a892192c8

Install:
1. Git clone repository
2. Run ```composer install``` (PHP 7.2.5 required)
3. Set up the database in config/packages/doctrine.yaml
4. Run ```php bin/console doctrine:migrations:migrate```

Functionalities:
1. View places on map
2. Listing places in table with filters (image, your places)
3. Register and login
4. Add new places
5. Add comments to places
6. Manage places and comments

Database tables:
1. Users - nickname, sex, age, avatar, references to places and opinions
2. Places - title, description, opinion, private place check, coordinates (from map), image, references to users and opinions
3. Comments to places - comment, references to places and users

Used technologies:
- Symfony 5.0.8
- Google Maps API Javascript
- Bootstrap 4.5 – UI framework
- jQuery (asynchronous operations on notes)
- Webpack - building frontend assets

Made with ❤ using Symfony 5.0.8, Google Maps API Javascript, Boostrap 4.5, jQuery and Webpack

© Copyright by Sebastian Pytel 2020 - proud student of Opole University of Technology
