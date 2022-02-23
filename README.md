## About Laravel

This is an simple application that works in conjunction with Postman Collection tests and Newman CLI. This provides an import interface to store the test results and will email the results.

- [Running collections on the commandline](https://learning.postman.com/docs/running-collections/using-newman-cli/command-line-integration-with-newman/#:~:text=Newman%20is%20a%20command%2Dline,directly%20from%20the%20command%20line.&text=Newman%20maintains%20feature%20parity%20with,the%20collection%20runner%20in%20Postman.).
- [Writing tests in Postman](https://learning.postman.com/docs/writing-scripts/test-scripts/#:~:text=You%20can%20add%20tests%20to,execute%20after%20the%20request%20runs.).

## How to run:

Copy .env.example to .env
**Don't forget to add your database info here**

Run Composer:

``composer install``

Run migrations & seed:

``php artisan migrate:refresh --seed``

## Setup

You'll need to automate Newman CLI in your CI/CD pipeline.  The output file should be
```your_app_name.branch.json```

```
npm newman run Api-Test-Tool.postman_collection.json --reporters cli,json --reporter-json-export your_app_name.branch.json
```
**Note: If using CI/CD software, use variables to replace the app name and branch in the above command.**

Next, CURL the output file to the import endpoint in this project:

```
curl -F file=@your_app_name.branch.json http://localhost:8000/api/import?email=youremailaddress@mail.com
```

The results will be stored in the projects defined database and a formatted email will be sent to the designated user.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
