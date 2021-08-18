
# Sports REST API

## About the project

### This project is for REST API calls:

#### Used laravel framework for the application
    - Please copy .env file and make Db changes

#### Following are the steps to follow:
- Composer install the package
- Migrate the database by using - ```php artisan migrate```


## User Authentication

- Authentication is done using Laravel passport package 
- Run the following commands to install the package at your end
- ```php artisan passport:install```
- ```php artisan passport:keys```


## Admin functionality

#### To create admin user there are 2 methods

- Run the seeder command to add default admin:
    - ```php artisan db:seed --class=UserSeeder```
    - **name: admin, email: admin@gmail.com, password:password**
- Add admin user using API call:
    - To add more admin users
    - ```https://{base_name}/api/register ```
    - **name, email, password, password_confirmation, role**
    - Add field ```Role: 1``` to make admin
    
#### Admin Login : 
```http://{base_name}/api/login:```
- Fields: **email, password**


#### After Authentication only the admin user has access to the following API calls:

- ```https://{base_name}/api/player/create```
    - Fields: **first_name, player_image_uri, last_name, team_id**
- ```https://{base_name}/api/player/update```
    - Fields: **id, (first_name, player_image_uri, last_name, team_id) - not mandatory**
- ```https://{base_name}/api/player/disable```
    - Fields: **id**
- ```https://{base_name}/api/player/delete```
    - Fields: **id**
- ```https://{base_name}/api/team/create```
    - Fields: **name, logo_uri**
- ```https://{base_name}/api/team/update```
    - Fields: **id, (name, logo_uri)- not mandatory**
- ```https://{base_name}/api/team/disable```
    - Fields: **id**
- ```https://{base_name}/api/team/delete``` 
    - Fields: **id**


## Public Rest Api's

#### As a normal user there are certain API's which can be accessed publicly:

- ```https://{base_name}/api/team/list```
- ```https://{base_name}/api/team/players/{param}```
    - The **param** can be **team ID** or **team name**
- ```https://{base_name}/api/player/{param}```
    - The **param** can be player **ID**, **first_name**, **last_name**
    

## Exception handling
- Exception are handled as per laravel default functionality and with a custom json response if required can be handled in a more custom way eg: sending email to admin or sending to bugsng 

### Unit test cases
- Test cases are not done for now




