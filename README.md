<p align="center"><a href="https://oholcurse.com" target="_blank"><img src="https://oholcurse.com/assets/uploads/images/new-logo-transparent.png" width="400" alt="OHOLCurse Logo"></a></p>


## About OHOLCurse

The website OHOLCurse is a resource for players of the game "One Hour One Life" (OHOL). It offers tools and information such as searchable player profiles, leaderboards, a name generator, and various resources like guides and community links. The site provides data entries from recent gameplay, reports on in-game events, and support options through donations.

## Architecture

OHOLCurse has been developed using the PHP framework [Laravel](https://laravel.com/docs) and styled using the [Tailwind CSS](https://tailwindcss.com/docs/font-size) framework. Some parts of the websites relies on the [Livewire](https://livewire.laravel.com/docs/quickstart) package for dynamic page interactivity.

These three resources combined allows rapid prototyping of and publishing of new functionality on OHOLCurse.

## OHOLCurse Donators

To the people who have donated to support the continual development of this project, I thank you from the bottom of my heart.

### Features

- Daily updated player profiles with statistics on lives, records and interactions
- Multiple choice profile searching, including extensive curse name search
- Dashboard with control over contacts and latest interactions
- Private messaging functionality between registered users
- Daily, weekly and all-time leaderboard records
- News section with comment sections per article
- Various game related tools like name generator
- Links to community resources
- Customizable profile pages
- Timezone corrected date and time on interaction pages and leaderboard



## How to setup up your own version
## 1. Clone the repository
Find a location on your computer where you want to store the project. A directory made for projects is generally a good choice.

Launch a bash console there and clone the project.

`git clone https://github.com/organization/project.git`

## 2. cd into the project
You will need to be inside the project directory that was just created, so cd into it.

`cd project_name`

## 3. Install composer dependencies
Whenever you clone a new Laravel project you must now install all of the project dependencies. This is what actually installs Laravel itself, among other necessary packages to get started.

`composer install`

## 4. Install NPM dependencies
Similarly to composer, npm manages javascript, css, and node packages, so make sure to install those dependencies also.

`npm install`

## 5. Copy the .env file
.env files are not generally committed to source control for security reasons. But there is a .env.example which is a template of the .env file that the project requires.

So you should make a copy of the .env.example file and name it .env so that you can setup your local deployment configuration in the next few steps.

`cp .env.example .env`

## 6. Generate an app encryption key
Laravel requires you to have an app encryption key which is generally randomly generated and stored in your .env file. The app will use this encryption key to encode various elements of your application from cookies to password hashes and more.

Laravel’s command line tools thankfully make it easy to generate this. Run this command in the terminal to generate that key.

`php artisan key:generate`

## 7. Create an empty database for the application
Create an empty database for your project using the database tools you prefer (phpmyadmin, datagrip, or any other mysql client).

## 8. In the .env file, add database information to allow Laravel to connect to the database
You will want to allow Laravel to connect to the database that you just created in the previous step. To do this, you must add the connection credentials in the .env file and Laravel will handle the connection from there.

In the .env file fill in the **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, and **DB_PASSWORD** options to match the credentials of the database you just created. This will allow you to run migrations in the next step.

## 9. Branding & Name
In the .env file feel free to customize the values for **MIX_APP_BRANDING** and **MIX_APP_NAME**.
Note that **MIX_APP_BRANDING** may be an empty string if no branding is desired.

## 10. Migrate the database
Once your credentials are in the .env file, now you can migrate your database. This will create all the necessary tables in your database.

`php artisan migrate`


# During Development

## Compiling assets
To compile all sass and js assets using webpack, run the following command.

`npm run dev`

You can also run the following command that will continue running in your terminal and watch all relevant files for changes. Webpack will then automatically recompile your assets when it detects a change.

`npm run watch`

## Local development server
To run a local development server you may run the following command. This will start a development server at **http://localhost:8000**.

`php artisan serve`
