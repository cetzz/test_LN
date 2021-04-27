Introduction
=====
This is a clone of swapi.dev API. It was made by looking at the responses, so everything here is my own code.<br>
It has a couple more features than the original, including a data scraper for the necessary resources, returning the id attribute , an amount property for each vehicle and starship, and endpoints to get, set, increase, and decrease those amounts.<br>
All the other features native to the swapi.dev API are there as well, like the Wookie encoder, the Searching functionalities, the pagination, etc.<br>
The resources implemented are Starships, Vehicles and Films.<br>
The other resources have not been implemented yet.<br>

## Dependencies
 - PHP 7.3.27 or greater
 - Slim 3
 

## Installing
Getting the website up and running is a simple operation that can be done in less than 10 minutes.

The installation procedure has been tested on Windows 10, other OS might not behave in the same way, feel free to look up the dependencies yourself if you feel like these instructions don't work out for you.

 - Install a XAMP (Any SQL-PHP server) and turn it on. Any XAMP server should work, but as an example I'll be using XAMPP.
 - In the root of your web server (in case of XAMPP, it should be something like C:\xampp\htdocs) clone the repository
 - Install Composer in the folder where the repository is (C:\xampp\htdocs\test_LN in this example) with the following command: `composer install`
 - Excecute the route public/init/ (http://localhost/test_LN/public/init/ in this example) . This will take care of initializing the database, creating the tables, and scraping the original SWAPI for the necessary data.
   **IMPORTANT!** A connection to the internet is necessary for /init/ to work, since it needs to scrape data from the original SWAPI, if the connection is slow, look at the log attribute in the response, if there are failed inserts, try again.
   **IMPORTANT!** If you have already used /init/ and you need to use it again, use the parameter 'delete' to allow /init/ to delete the current database (http://localhost/test_LN/public/init/?delete).
 - Update the conf.php with the base url of the API. There is an example already there, as LOCAL_URL. If it is the same, great!

And you are done!

## Base URL
We'll be using the route that we generated in the installation as an example.

The Base URL we'll be using is: 
`http://localhost/test_LN/public/`

The documentation below assumes you are prepending the Base URL to the endpoints in order to make requests.


   **IMPORTANT!** If you want to expose the API to a network, for example, your Wi-Fi network, you should change 'localhost' for your current IP. 

## Authentication
This API doesn't have any authentication, as it is an open API. But only GET is supported.

## Searching
Existing resources support a search parameter to filter the results. This allows you to make queries like:<br>
`http://localhost/test_LN/public/vehicles/?search=AT-AT`<br>
These searches are case-insensitive. Check out the individual resource documentation below to see which fields are supported for what resource.<br>

## Encodings
As the original SWAPI, this clone provides two encodings you can render the data with.
JSON
JSON is the standard data format provided by default.

Wookiee
The same format as JSON, but in the language of the Wookies, Shyriiwook<br>
To use the Wookie encoder, just append format=wookiee to your urls:<br>
`http://localhost/test_LN/public/vehicles/?search=AT-AT&format=wookie`<br>


## Resources
Since this was made as a test, I've implemented just the necessary.<br>
I implemented Starships and Vehicles as that was what was requested by the test, but I also implemented Films as it was a good way to practice table relationships, and the Root resource to show the routing.

**ROOT:**

The root resource provides information on the routing of the API and all its available endpoints. <br>
Example request:<br>
`http://localhost/test_LN/public/`
<br><br>
Example response:
```json
{
    "/films/": "/films[/]",
    "/films/id/": "/films/{id}[/]",
    "/starships/": "/starships[/]",
    "/starships/id/": "/starships/{id}[/]",
    "/starships/amount/get/": "/starships/amount/get[/]",
    "/starships/amount/set/": "/starships/amount/set[/]",
    "/starships/amount/increase/": "/starships/amount/increase[/]",
    "/starships/amount/decrease/": "/starships/amount/decrease[/]",
    "/vehicles/": "/vehicles[/]",
    "/vehicles/id/": "/vehicles/{id}[/]",
    "/vehicles/amount/get/": "/vehicles/amount/get[/]",
    "/vehicles/amount/set/": "/vehicles/amount/set[/]",
    "/vehicles/amount/increase/": "/vehicles/amount/increase[/]",
    "/vehicles/amount/decrease/": "/vehicles/amount/decrease[/]",
    "/init/": "/init[/]"
}
```

The [] brackets mean that it is not necessary, so /films/ can also be /films

The {} curly brackets mean that it is a variable, so if the id was 1, /vehicles/{id}/ would be /vehicles/1/

**INIT:**

This endpoint creates the database, tables, and scrapes the necessary data for this API to work from the original SWAPI automatically. It saves a lot of time on the installation, since you don't have to execute any sql files by yourself, and it comes with the added benefit of the fact that you don't need to use any database managing software to make this API work. 
<br>Example request:<br>
`http://localhost/test_LN/public/init/`
<br><br>
Example response:
```json
{
    "log": [
        "Database created correctly.",
        "6 films were inserted successfully, 0 failed.",
        "36 starships were inserted successfully, 0 failed.",
        "55 starship and film relationships were inserted successfully, 0 failed.",
        "39 vehicles were inserted successfully, 0 failed.",
        "49 vehicle and film relationships were inserted successfully, 0 failed.",
        "Initialization completed. 179 inserts were successful, 0 failed."
    ],
    "failedInserts": 0,
    "successfulInserts": 179
}
```

- Endpoints:
    The parameter is `delete` (empty).
    
    `/init/` --initializes the database, the tables and scrapes data<br>
    `/init/?delete` --same as the other one, but with permissions to delete the database - requires: delete<br>
    
    Attributes:<br>
    `log` An array of strings that contains the log. It tells you what happened in text. <br>
    `failedInserts` Number of failed inserts.<br>
    `successfulInserts` Number of successful inserts.  <br>
 

**Starships:**

The resource Starships provides information about the Starships.<br>
Example request:<br>
`http://localhost/test_LN/public/starships/1/`
<br><br>
Example response:
```json
{
    "id": "1",
    "name": "CR90 corvette",
    "model": "CR90 corvette",
    "manufacturer": "Corellian Engineering Corporation",
    "cost_in_credits": "3500000",
    "length": "150",
    "max_atmosphering_speed": "950",
    "crew": "30-165",
    "passengers": "600",
    "cargo_capacity": "3000000",
    "consumables": "1 year",
    "hyperdrive_rating": "2.0",
    "MGLT": "60",
    "starship_class": "corvette",
    "pilots": [
        "Feature not yet developed"
    ],
    "films": [
        "http://localhost/test_LN/public/films/1/",
        "http://localhost/test_LN/public/films/3/",
        "http://swapi.dev/api/films/6/"
    ],
    "created": "2014-12-10 14:20:33.000000",
    "edited": "2021-04-27 12:35:18.209125",
    "url": "http://localhost/test_LN/public/starships/1/",
    "amount": "0"
}
```

- Endpoints:<br>
    The parameters are `id` (number) and `page` (number).<br>
    
    `/starships/` --get all the starships resources, divided by pages of ten.<br/>
    `/starships/?page={page}` --get the specified page. - requires: page<br/>
    `/starships/{id}/` --get an individual starship by its ID. - requires: id<br>
    
    Attributes:<br>
    The attributes are identical to SWAPI's, but with an id and amount attributes added<br>
    <br>
    Search fields:<br>
    `name`<br>
    `model`<br>

- EXTRA ENDPOINTS:
    The /amount/ route lets you control the amount of starships there are of each single one. There is data validation in all of them, including things like if a decreased amount is smaller than the set amount, etc.<br>
    The parameters are `id` (number) and `amount` (number).
    <br>
    Example request:<br>
        `http://localhost/test_LN/public/starships/amount/increase/?id=1&amount=10`
        <br><br>
    Example response:
```json
{
    "success": true,
    "detail": "Amount increased"
}
```

   `/starships/amount/get/?id={id}` --get the amount of starships by its ID - requires: id<br>
   `/starships/amount/set/?id={id}&amount={amount}` --set the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
   `/starships/amount/increase/?id={id}&amount={amount}` --increase the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
   `/starships/amount/decrease/?id={id}&amount={amount}` --decrease the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
    
   Attributes:<br>
   Set, increase and decrease respond with a success attribute and the detail attribute, while get responds with the amount attribute.<br>

**Vehicles:**

The resource Vehicles provides information about the Vehicles. This one works identical to the Starship resource.<br>
Example request:<br>
`http://localhost/test_LN/public/vehicles/1/`
        <br><br>
    Example response:
```json
{
    "id": "1",
    "name": "Sand Crawler",
    "model": "Digger Crawler",
    "manufacturer": "Corellia Mining Corporation",
    "cost_in_credits": "150000",
    "length": "36.8 ",
    "max_atmosphering_speed": "30",
    "crew": "46",
    "passengers": "30",
    "cargo_capacity": "50000",
    "consumables": "2 months",
    "vehicle_class": "wheeled",
    "pilots": [
        "Feature not yet developed"
    ],
    "films": [
        "http://localhost/test_LN/public/films/1/",
        "http://localhost/test_LN/public/films/5/"
    ],
    "created": "2014-12-10 15:36:25.000000",
    "edited": "2021-04-27 12:35:19.987091",
    "url": "http://localhost/test_LN/public/vehicles/1/",
    "amount": "0"
}
```

- Endpoints:
    The parameters are `id` (number) and `page` (number).
    
    `/vehicles/` --get all the vehicles resources, divided by pages of ten.
    `/vehicles/?page={page}` --get the specified page. - requires: page
    `/vehicles/{id}/` --get an individual vehicle by its ID. - requires: id
    
    Attributes:<br>
    The attributes are identical to SWAPI's, but with an id and amount attributes added<br>
    
    Search fields:<br>
    `name`<br>
    `model`<br>

- EXTRA ENDPOINTS:
    The /amount/ route lets you control the amount of vehicles there are of each single one. There is data validation in all of them, including things like if a decreased amount is smaller than the set amount, etc.<br>
    The parameters are `id` (number) and `amount` (number).<br>
    <br>
    Example request:<br>
        `http://localhost/test_LN/public/vehicles/amount/increase/?id=1&amount=10`
                <br><br>
    Example response:
```json
{
    "detail": "Amount increased",
    "success": true
}
```

   `/vehicles/amount/get/?id={id}` --get the amount of vehicles by its ID - requires: id<br>
   `/vehicles/amount/set/?id={id}&amount={amount}` --set the amount of vehicles by its ID and an AMOUNT - requires: id,amount<br>
   `/vehicles/amount/increase/?id={id}&amount={amount}` --increase the amount of vehicles by its ID and an AMOUNT - requires: id,amount<br>
   `/vehicles/amount/decrease/?id={id}&amount={amount}` --decrease the amount of vehicles by its ID and an AMOUNT - requires: id,amount<br>
    
   Attributes:<br>
     Set, increase and decrease respond with a success attribute and the detail attribute, while get responds with the amount attribute.<br>

**Films:**

The resource Films provides information about the Films. <br>
Example request:<br>
`http://localhost/test_LN/public/films/`<br>
<br>
Example response:<br>
```json
{
    "id": "1",
    "title": "A New Hope",
    "episode_id": "4",
    "opening_crawl": "It is a period of civil war.\r\nRebel spaceships, striking\r\nfrom a hidden base, have won\r\ntheir first victory against\r\nthe evil Galactic Empire.\r\n\r\nDuring the battle, Rebel\r\nspies managed to steal secret\r\nplans to the Empire's\r\nultimate weapon, the DEATH\r\nSTAR, an armored space\r\nstation with enough power\r\nto destroy an entire planet.\r\n\r\nPursued by the Empire's\r\nsinister agents, Princess\r\nLeia races home aboard her\r\nstarship, custodian of the\r\nstolen plans that can save her\r\npeople and restore\r\nfreedom to the galaxy....",
    "director": "George Lucas",
    "producer": "Gary Kurtz, Rick McCallum",
    "release_date": "1977-05-25 00:00:00.000000",
    "characters": [
        "Feature not yet developed"
    ],
    "planets": [
        "Feature not yet developed"
    ],
    "starships": [
        "http://localhost/test_LN/public/starships/1/",
        "http://localhost/test_LN/public/starships/2/",
        "http://localhost/test_LN/public/starships/3/",
        "http://localhost/test_LN/public/starships/4/",
        "http://localhost/test_LN/public/starships/5/",
        "http://localhost/test_LN/public/starships/6/",
        "http://localhost/test_LN/public/starships/7/",
        "http://localhost/test_LN/public/starships/8/"
    ],
    "vehicles": [
        "http://localhost/test_LN/public/vehicles/1/",
        "http://localhost/test_LN/public/vehicles/2/",
        "http://localhost/test_LN/public/vehicles/3/",
        "http://localhost/test_LN/public/vehicles/4/"
    ],
    "species": [
        "Feature not yet developed"
    ],
    "created": "2014-12-10 14:23:31.000000",
    "edited": "2021-04-27 12:35:17.857038",
    "url": "http://localhost/test_LN/public/films/1/"
}
```


- Endpoints:
The parameters are `id` (number) and `amount` (page).

   `/films/` --get all the films resources, divided by pages of ten.<br>
   `/films/?page={page}` --get the specified page. - requires: page<br>
   `/films/{id}/` --get an individual film by its ID. - requires: id<br>
    
   Attributes:<br>
    The attributes are identical to SWAPI's, but with an id added<br>
    
   Search fields:<br>
    `title`<br>

## Testing
   There is not a lot to test in this API. But the most failsafe way to test if it works or not is to use the /init/ endpoint, as it interacts with almost everything at the same time. 
   If /init/ is working correctly, nothing should stop working.
## Can I look at the code and use it?

If you find something useful, sure! The proyect was made as a test, so the API is an open source proyect.

By the way, it would be really cool if you checked out https://github.com/cetzz/SWAPICLONE_Front , it is a front-end proyect that I made as an additional characteristic. It uses this API , so you can find some examples on how to use it there! Plus it looks pretty cool in my opinion. It's fully responsive. I'm more of a back-end type of guy, but I hope you like it too.

-Cristian Metz
