## Mini-Upwork App

### Install
1. git clone https://github.com/alexeydemin/mini-upwork
2. cd mini-upwork
3. docker-compose up -d
4. docker-compose run app php artisan migrate
5. docker-compose run app php artisan db:seed
5. docker-compose run app php artisan horizon

### Use
1. http://0.0.0.0/register and get your bearer token
2. `php artisan coin:credit` to credit coins to all users 
3. Use the bearer token to access the API:
   - Vacancy
     - GET http://0.0.0.0/api/vacancies - List vacancies
     - GET http://0.0.0.0/api/vacancies/1 - Show specific vacancy 
     - POST http://0.0.0.0/api/vacancies - Create vacancy, e.g. `{"title": ".NET programmer", "description":".NET programmer vacancy description"}`
     - PATCH http://0.0.0.0/api/vacancies/1 - Edit vacancy, e.g. `{"title": "PHP programmer", "description":"PHP programmer vacancy description"}`
     - DELETE http://0.0.0.0/api/vacancies/1 - Delete vacancy
   - Response
     - POST http://0.0.0.0/api/responses - Create response `{"vacancy_id":1, "text": "I want this job!"}`
     - DELETE http://0.0.0.0/api/responses/1 - Delete response 
   - Likes
     - POST http://0.0.0.0/api/likes/users/1 - Like a user
     - POST http://0.0.0.0/api/likes/vacancies/1 - Like a vacancy
     - GET http://0.0.0.0/api/likes/users - Get liked users
     - GET http://0.0.0.0/api/likes/vacancies - Get liked vacancies
 

Or just export `./mini-upwork-insomnia` file to your Insomnia API Client

### Test
docker-compose run app php artisan test
