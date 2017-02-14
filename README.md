EmakinaFoodAcademy
==================

## Installation
 First clone the repository
 
```
git clone https://github.com/optii/EmakinaFoodAcademy.git
```
Then install the dependencies and fill in the parameters

```
composer install 
```
Finally set up the database
```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```
# BO

You can access the back office view the following link:

[http://localhost/EmakinaFoodAcademy/admin](http://localhost/EmakinaFoodAcademy/admin)

## Create User

To create a user execute the following command:
```
php bin/console dandam:user:create
```
To add roles to a user execute the following command 
```
php bin/console dandam:role:add  
```
Available roles are:

| ROLE NAME  | 
| ------------- | 
| ROLE_SERVER  | 
| ROLE_EDITOR  |
| ROLE_REVIEWER  | 
| ROLE_CHEF  |

## Menu Check

```
php bin/console dandam:menu:check
```
