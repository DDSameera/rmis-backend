## RMIS - Backend

RMIS stands for Recruitment Management Information System - Please follow up these steps  for testing in a fresh environment

#1.0 PreRequests
- ****PHP 8.0,MySQL 8.0,Apache 2.4****
- WAMP Server Latest ([Download](https://www.wampserver.com/en/))
  or XAMPP Server Latest
- MySQL Workbench Latest ([Download](https://dev.mysql.com/downloads/workbench/)) or Phpmyadmin 5.0
- Postman Latest ([Download](https://www.postman.com/downloads/))
- GIT Installation in your local environment ([How to intsall GIT in windows](https://phoenixnap.com/kb/how-to-install-git-windows))
- Composer Latest Version ([Download](https://getcomposer.org/download/ ))
- PHP Unit Test ([Installation Guide](https://perials.com/installing-phpunit-windows/))
- Visual Studio Code  -  [Download](https://code.visualstudio.com/) 

###### Set Up Windows Environment Path Variable
- Go to Start > Type "Edit Environment Variable for your account"
- Add Following Paths 
    - `C:\wamp64\bin\php\php7.4.9`
    - `C:\wamp64\bin\mysql\mysql8.0.21\bin`
    - `C:\PhpUnitTest\vendor\bin`

#2.0 Getting Start

1. ### Clone Project
    1) Open Windows Command Prompt 
    2) Go to WAMP Folder <code> cd  C:/wamp64/www </code>
    3) Run this Command to clone project
       `git clone https://github.com/DDSameera/rmis-backend.git` 
    4) Run this command & Go to Project Folder `cd rmis-backend`
    5) Run this command to Install Dependencies `composer install'
    6) Open this project in Visual Studio Code Editor 
    
          
2. ### Preparation 
    1) Rename <code>.env.example</code> to <code>.env</code>
    2) Create MYSQL database  ``rmis-backend``
    3) Configure **MYSQL Data Base**
      
       ```
       DB_HOST=127.0.0.1
       DB_PORT=3306
       DB_DATABASE=rmis-backend
       DB_USERNAME=xxxxxxx
       DB_PASSWORD=xxxxxxx
       ```
     
    3). Customize API Settings (Recommend : Keep Default Settings)

      ```
        API_RATE_LIMIT=100
        API_MAX_LOGIN_ATTEMPTS=10
        API_MAX_LOGIN_DELAY=1
        API_TOKEN_EXPIRE=15
      ```
<ul>
<li>API_RATE_LIMIT - Maximum Number of API Request Send to Server</li>
<li>API_MAX_LOGIN_ATTEMPTS - Maximum Login Attempts
<li>API_MAX_LOGIN_DELAY - If Maximum Login attempts reached, then wait 1 min</li>
<li>API_TOKEN_EXPIRE - Authorized Token will expire after 15 min</li>
</ul>

3. ### Final Setup
    i). Go to Visual Studio Code > Menu > Terminal > New Terminal
   
   ii) .Run these Artisan Codes

    - `php artisan key:generate`
    - `php artisan migrate:fresh --seed`
    - `php artisan optimize`
    - `php artisan config:cache`
    - `php artisan config:clear`

4. ### Completed
    - Run `php artisan serve`
    - Type this address on Postman Client ``http://127.0.0.1:8000/``

