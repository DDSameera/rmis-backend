# API Document

### Server IP (localhost)
``http://127.0.0.1:8000``

# 1. POST - User Registeration
Path : ``/api/v1/user/register``

Accept Headers : ``application/json``

Request Body : ``JSON``

**Description**

1. fname - First Name  (_required_)
2. mname - Middle Name  (_required_)
3. lname - Last Name  (_required_)
4. mobile - Mobile Number  (_required_)
5. email - **Unique** Email Address   (_required_)
6. role - **admin or user** (_required_)
7. password -  Min 6 Characters,Password should contain alphabetic characters,numbers,& symbol (_required_) -
8. password_confirmation  - (_required_) (Confirm your password)


**Example:**
```
    http://127.0.0.1:8000/api/v1/user/register   
```


```
{
"fname" : "Sameera",
"mname" : "Dananjaya",
"lname" : "Wijerathna",
"mobile" : "0718761292",
"email":"digix.sameera@yahoo.com",
"role":"admin",
"password" : "Majority600!",
"password_confirmation" :  "Majority600!"
}
```

Response : ``JSON``  Status : ``201`` Description : ``Created``

```
{
    "success": true,
    "message": "User Created Successfully",
    "data": {
        "user": {
            "full_name": "Sameera Dananjaya Wijerathna",
            "email": "digix.sameera@gmail.com",
            "mobile": "0718761292",
            "role": "admin"
        }
    }
}
```
___

# 2. POST - User Login

Path : ``/api/v1/user/login``

Accept Headers : ``application/json``

Request Body : ``JSON``

Example:
```
http://127.0.0.1:8000/api/v1/user/login
```
Description:
1. email : User Registered Email Address (_required_)
2. password : Min 6 , Registered Password (_required_)
3. deviceName  - (Options: mobile,desktop) (_required_)
```
{
    "email" : "digix.sameera@gmail.com",
    "password": "Majority600!",
    "deviceName" : "desktop"
}
```

Response : ``JSON``  Status : ``200`` Description : ``OK``

```
{
    "success": true,
    "message": "User Logged Successfully",
    "data": {
        "token": "19|uwKEzVq4v7uvYFGfptv5z0J0ZLkGSCRqcOsUtaV8",
        "user": {
            "full_name": "Sameera Dananjaya Wijerathna",
            "email": "digix.sameera@gmail.com",
            "mobile": "0718761292",
            "role": "admin"
        }
    }
}
```
___
# 3. POST - Create Applicant


Path : ``api/v1/applicant/``

Accept Headers : ``application/json``

Request Body : ``JSON``

Example :

```
http://127.0.0.1:8000/api/v1/applicant/
```

```
{
   "onboarding_percentage" : 40,
   "count_applications" : 22,
   "count_accepted_applications" : 11

}
```

Response : ``JSON``  Status : ``201`` Description : ``CREATED``

```
{
    "success": true,
    "message": "Applicant Information has been saved successfully ",
    "data": {
        "user": {
            "user_id": 21,
            "onboarding_percentage": 40,
            "count_applications": 22,
            "count_accepted_applications": 11
        }
    }
}
```

___
# 4. PUT or PATCH - Update Applicant


Path : ``api/v1/applicant/{user_id}``

Accept Headers : ``application/json``

Request Parameter : ``user_id``

Request Body : ``JSON``

Example :

```
http://127.0.0.1:8000/api/v1/applicant/21
```

```
{
    "onboarding_percentage":1,
    "count_applications":2,
    "count_accepted_applications":30
    

}
```

Response : ``JSON``  Status : ``200`` Description : ``OK``

```
{
    "success": true,
    "message": "Applicant Information has been updated successfully ",
    "data": {
        "updated_data": {
            "user_id": 21,
            "onboarding_percentage": 1,
            "count_applications": 2,
            "count_accepted_applications": 30
        }
    }
}
```
___
# 5. GET - Show Applicant


Path : ``/api/v1/applicant/{user_id}``

Accept Headers : ``application/json``

Request Parameter : ``user_id``

Response : ``JSON``  Status : ``200`` Description : ``OK``

Example :

```
http://127.0.0.1:8000/api/v1/applicant/1
```

```
{
    "success": true,
    "message": "Applicant Information has been found.",
    "data": {
        "applicant": {
            "user_id": 1,
            "onboarding_percentage": 618,
            "count_applications": 218,
            "count_accepted_applications": 40
        }
    }
}
```
___

# 6. GET - List of All Applicants

Path : ``api/v1/applicant{?page=1}``

Accept Headers : ``application/json``

Request Parameter : ``?page=1``

Response : ``JSON``  Status : ``200`` Description : ``OK``

Paginated Results :

Examples:
```
http://127.0.0.1:8000/api/v1/applicant/
http://127.0.0.1:8000/api/v1/applicant?page=2
http://127.0.0.1:8000/api/v1/applicant?page=3
http://127.0.0.1:8000/api/v1/applicant?page=4
http://127.0.0.1:8000/api/v1/applicant?page=5
```

```
{
    "current_page": 1,
    "data": [
        {
            "user_id": 1,
            "onboarding_percentage": 618,
            "count_applications": 218,
            "count_accepted_applications": 40,
            "created_at": "1974-09-21"
        },
        {
            "user_id": 2,
            "onboarding_percentage": 65,
            "count_applications": 67,
            "count_accepted_applications": 25,
            "created_at": "2004-06-24"
        },
        {
            "user_id": 3,
            "onboarding_percentage": 264,
            "count_applications": 117,
            "count_accepted_applications": 38,
            "created_at": "1981-11-14"
        }
    ],
    "first_page_url": "http://127.0.0.1:8000/api/v1/applicant?page=1",
    "from": 1,
    "last_page": 4,
    "last_page_url": "http://127.0.0.1:8000/api/v1/applicant?page=4",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/applicant?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/applicant?page=2",
            "label": "2",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/applicant?page=3",
            "label": "3",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/applicant?page=4",
            "label": "4",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v1/applicant?page=2",
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": "http://127.0.0.1:8000/api/v1/applicant?page=2",
    "path": "http://127.0.0.1:8000/api/v1/applicant",
    "per_page": 3,
    "prev_page_url": null,
    "to": 3,
    "total": 10
}
```

___
# 7. DELETE - Delete Applicant


Path : ``/api/v1/applicant/{user_id}``

Accept Headers : ``application/json``

Request Parameter : ``user_id``

Response : ``JSON``  Status : ``200`` Description : ``OK``

Example :

```
http://127.0.0.1:8000/api/v1/applicant/2
```

```
{
    "success": true,
    "message": "Applicant Information has been deleted successfully ",
    "data": {
        "user_id": "2"
    }
}
```
___

