Instructions for running the project
---------------------------------------


1. Place the cloned repository in local server
2. Configure database credential in .env
3. Run the following commands

    php artisan migrate
    php artisan db:seed

4. Flow of data insertion

    {base_url}/create - For adding a student
    {base_url}/students - For viewing the students in table with edit/delete
    {base_url}/marks/add - Adding marks for a student
    {base_url}/marks/list - listing the marks in table with edit/delete