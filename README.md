 ## setup instructions

 1. Clone the Repository
    Open your terminal and run the following command:
    git clone https://github.com/Vijay-Thorakkattu/TaskFlow.git

2. Navigate to the Project Directory
   cd TaskFlow

3. Composer is installed using the commands 

   composer install

4. Create .env file in project.

5. Configure the Database
    Open the .env file and update the database connection details. Replace the placeholders with your actual database information:

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

6. To set up the database tables, run the migrations using Artisan:
   php artisan migrate

7.  Start the Laravel Development Server
    You can now start the Laravel development server by running the following command:
    php artisan serve

8. Configure Queue

   For background job processing, ensure that the queue is properly configured.
   Update the .env file with the following:
   QUEUE_CONNECTION=database

9. Set Up SMTP for Email testing

   update the .env file with your SMTP credentials:

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=c325f3ed7f3214  # Your SMTP username
    MAIL_PASSWORD=da911f3ecb6c16  # Your SMTP password
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=vijayvly8@gmail.com  # Your email address

10. Run the queue worker in new artisan terminal: 
   php artisan queue:work

12. Run Scheduled Tasks
    To execute scheduled tasks , run the following Artisan command in a separate terminal:
    php artisan schedule:run

13. Verify API Endpoints
    Ensure that the API routes are working correctly. Below are some example key API endpoints you can test:

    Important: You must pass an access token for authorization in all endpoints except for the register and login URLs.

    For all API requests, make sure to set the Accept: application/json header.

    Registration:
    POST http://127.0.0.1:8000/api/register

    Login:
    POST http://127.0.0.1:8000/api/login

    Create Task:
    POST http://127.0.0.1:8000/api/tasks

    Assign Task to User:
    POST http://127.0.0.1:8000/api/tasks/{task_id}/assign

    Complete Task:
    POST http://127.0.0.1:8000/api/tasks/{task_id}/complete

    Filter Tasks:
    GET http://127.0.0.1:8000/api/tasks?assigned_to=1&status=pending

    List Tasks:
    GET http://127.0.0.1:8000/api/tasks-list (API Resource Transformations)

    Get Specific Task:
    GET http://127.0.0.1:8000/api/tasks/{task_id} (API Resource Transformations)

    -End- 






  
