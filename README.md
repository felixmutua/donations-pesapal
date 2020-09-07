#  Disaster Donations Application

 The donations page uses AJAX to call to the backend, running on
the same or another machine, to retrieve and save donations
information.

The backend is a simple php/laravel application that takes in transaction data over
an exposed RESTful API (JSON payload expected), and translates into
calls to a backend MySQL DB instance.


## Modify the Application Before Running

The app can largely be used as-is. Edit [`config.php`](./donate.js) to
change the `required parameter` variables  to the hostname where the backend
application (also in this repo) is running.


## Build and Run the Application

Run the application standalone with

```
npm install
php artisan serve
```

in this directory. The application listens both on port `8000` 
. Alternatively, build and run the application in a Docker
container:


## Test the Application

Use a web browser to navigate to
[`locahost:8080`](http://localhost:8000) and confirm that the
donations homepage loads. (Or, of course, whichever host you're
running it on.)

Navigate to the donations page try out that functionality, driving
AJAX calls to issue `GET` and `POST` requests to the backend
application. You can view the console to check these network calls are
functioning correctly.

For Admin  to fetch transactions. use credentials inside  [`UserSeeder.php`]() to generate
a jwt token and fetch donation payments.
On successful auth all processed transactions will be returned

## Improvements
Validation of input
Use cron jobs to process payments etc
Improve the admin dashboard



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
