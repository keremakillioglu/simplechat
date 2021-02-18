# Simple Chat API
A simple chat application backend developed with PHP & Slim 4 Framework.

## Installation and Setup
Application can be set up with `composer update` and `composer migrate` commands.  
Make configurations in the .env file.
It can be hosted via a Apache Server or `php -S` command

## Dependencies
* **slim/slim:** Framework
* **illuminate/database:**  Database Operations
* **robmorgan/phinx:** Database Migrations
* **firebase/php-jwt:** Generating JWT
* **tuupola/slim-jwt-auth:** Parsing and Authenticating JWT
* **ramsey/uuid:** UUID Creation
* **vlucas/valitron:** Request Validations
  
## API Details

### 1. Capabilities
User can register and login with JWT mechanism.  
After a successful login, a user can send messages to other users.  
Message recipient can mark a message as read.
* **A user can retrieve:**  
  The history of Received || Sent || All messages,  
  The history of Received || Sent || All messages with a specific user.

### 1. Authentication
Users cannot send messages if they are not registered to the system.
A server-side JSON Web Token (JWT) is generated for every login.
JWTs should be attached to each HTTP request Header to access API services.
These tokens expire when specified time passes.
The JWT token should be stored in the browser's local storage or cookies using JavaScript.
In order to logout, token should be deleted at the client side.
No refresh tokens are generated with this implementation. So there is no need to clear them.

### 2. Authorization
Only auth/login and auth/register endpoints are public.  
In order to send and receive a message, user should login.  
JWT Token should be added to the request header to be authorized. Example is as follows:
```
Authorization: Bearer JWTTOKEN
```

### 3. Project Structure

#### 3.a Config
This folder includes the configurations and files that are necessary to bootstrap the app.

#### 3.b Database
Database and migration files are located in db folder.

#### 3.c Application
All application related files are located in src folder.
* Controllers: Main tasks are handled in controllers
* Models: Eloquent ORM Models are mapped through Model classes: User, Message, Record
* Database: Migration template and stub is located here.
* Http: Contains an Exception folder to handle exceptions.  
  CustomResponse generates the same response for API Endpoints and Exceptions.
* Token is generated under Service Folder  

#### 3.d Models
User model contains id, uuid, username and timestamps.  
Message model contains id, sender_id, receiver_id, uuid and timestamps.  
Message or User ID's are never compromised publicly. Instead, UUID's are displayed when needed.
(e.g. URL for retrieving message by ID `/messages/bb75bec7-7399-46c3-9c6c-0f6079474052`)

#### 3.e Migrations
Tables are created and updated with migrations, using Phinx.  
Create migrations: `composer make-migration -- MigrationName`  
Run migrations: `composer migrate`

#### 3.f Exceptions and Logs
Exceptions are handled through a handler in Http/Exceptions. If API faces a problem, an APIException is thrown.
Logs are stored in logs directory

### Coding Style
PHP Coding Standards Fixer (PHP CS Fixer) tool is used to follow standards.  
PSR1 was followed for naming conventions:
* Class names are declared in StudlyCaps.
* Method names are  declared in camelCase.

#### Further Development
* Database validations can be reorganized. Ideally a validator can be made for each endpoint.   
  A Validator Interface can be created, and a concrete Validator can be created for each endpoint.
  Since this is a small project, it is not done that way.
* Unit tests can be added.
* App route can be changed.
