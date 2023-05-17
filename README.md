# TaskTrackerJWT

The only difference between TaskTrackerJWT and TaskTrackerApi is their different authentication method, TaskTrackerJWT use JWT(Json Web Token) with access token and refresh token for user authentication while TaskTrackerApi use token based user authentication.

TaskTrackerJWT is an advanced RESTful API built using the CodeIgniter 3 framework. It provides a comprehensive task management system with user authentication and authorization using JWT authentication.

![Screenshot-20230513172045-1471x872.png](showcase%2FScreenshot-20230513172045-1471x872.png)

## Features

- Authentication
    - JWT(Json Web Token) 
    - Access token and Refresh Token with db whitelisting and expiration time limit
- User Management:
    - Sign up: Allows users to register for an account.
    - Sign in: Authenticates users and provides them with a bearer token upon successful login.
    - Sign out: Invalidates the token, logging out the user and preventing further access.
    - Fetch user info: Retrieves user details based on the provided token.
    - Update user info: Allows users to modify their account information.

- Task Management:
    - Add task: Enables users to create new tasks associated with their account.
    - Update task: Allows users to modify existing tasks.
    - Delete task: Deletes a specified task.
    - View tasks: Retrieves a list of tasks associated with the user's account.

## Installation

1. Clone the repository:

```bash
git clone https://github.com/your-username/TaskTrackerApi.git
```

2. Configure the database settings in application/config/database.php.
3. Import the db into your mysql databasae from /db directory
4. Include this in all request header
```
Content-Type : application/json
```

## Authentication

After User authentication you will receive an access_token and a refresh_token in the response body. Include the access token on header for restricted api endpoints

Example:
```
HTTP_AUTHORIZATION : Bearer [YOUR ACCESS TOKEN]
```

When you will receive a token expired message or status 69 on response body, which means your token has been expired after a limited time. Now you have to get a new access_token using the refresh_token itself.

Example:

POST /authentication/refresh_token

```
HTTP_AUTHORIZATION : Bearer [YOUR REFRESH TOKEN]
```

Now you will receive a new access_token and refresh_token on response body. Now restore those token and use in future request.

Repeat the cycle.

>N.B: If refresh token is also expired then you have to login again to claim new tokens 

## API Endpoints

## Sign up
POST /authentication/processSignUp
```
"username": "johndoe",
"name": "Jhon Doe",
"password": "password123"
```

## Sign in
POST /authentication 

```
"username": "johndoe",
"password": "password123"
```

## Sign Out
DELETE /authentication

## Get Current User Info
GET /user

## Update Current User Info
PUT /user
```
"username": "johndoe",
"name": "Jhon Doe",
"old_password": "password123",
"new_password": "password456"
```

# Get task lists
GET /task

## Create a new task
POST /task
```
"title": "Buy groceries",
"Description": "Buy milk, eggs, and bread",
"Status": "pending"
```

## Get information about a specific task
GET /task/task/1

## Update a specific task
PUT /task/task/1

```
"task_name": "Buy groceries",
"task_description": "Buy milk, eggs, and bread",
"task_status": "completed"
```

# Delete a specific task
DELETE /task/1

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE.txt` for more information.
