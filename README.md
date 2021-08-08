## RMIS - Backend

RMIS stands for Recruitment Management Information System - Please follow up these steps  for testing in a fresh environment

## 1.0 PreRequests
- ****PHP 8.0,MySQL 8.0,Apache 2.4****
- WAMP Server Latest ([Download](https://www.wampserver.com/en/))
  or XAMPP Server Latest
- MySQL Workbench Latest ([Download](https://dev.mysql.com/downloads/workbench/)) or Phpmyadmin 5.0
- Postman Latest ([Download](https://www.postman.com/downloads/))
- GIT Installation in your local environment ([How to intsall GIT in windows](https://phoenixnap.com/kb/how-to-install-git-windows))
- Composer Latest Version ([Download](https://getcomposer.org/download/ ))
- PHP Unit Test ([Installation Guide](https://perials.com/installing-phpunit-windows/))

## Set Up Windows Environment Path Variable
- Go to Start > Type "Edit Environment Variable for your account"
- Add Following Paths 
    - `C:\wamp64\bin\php\php7.4.9`
    - `C:\wamp64\bin\mysql\mysql8.0.21\bin`
    - `C:\PhpUnitTest\vendor\bin`

## 2.0 Getting Start

## 1. Basic Setup
1. Open Windows Command Prompt 
2. Go to WAMP Folder <code> cd  C:/wamp64/www </code>
3. Run this Command to clone project
   `git clone https://github.com/DDSameera/rmis-backend.git` 
4. Open this project in PHPStorm 
5. Create New MYSQL Database "rmis"

## 2. Installation 
1. Open Terminal & Run command ``php artisan rmis:install``

<img src="https://raw.githubusercontent.com/DDSameera/rmis-backend/master/public/assets/images/pa_rm_install.gif"/>





          
### 2. Preparation 


