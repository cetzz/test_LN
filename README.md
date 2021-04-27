Introduction
=====
This is a clone of swapi.dev API. It was made by looking at the responses, so everything here is my own code.
It has a couple more features than the original, including a data scraper for the necessary resources, returning the id attribute , an amount property for each vehicle and starship, and endpoints to get, set, increase, and decrease those amounts.
All the other features native to the swapi.dev API are there as well, like the Wookie encoder, the Searching functionalities, the pagination, etc.
The resources implemented are Starships, Vehicles and Films.
The other resources have not been implemented yet.

## Dependencies
 - PHP 7.3.27 or greater
 - Slim 3
 

## Installing
Getting the website up and running is a simple operation that can be done in a minute or two.

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

## Authentication
This API doesn't have any authentication, as it is an open API. But only GET is supported.

## Searching
Existing resources support a search parameter to filter the results. This allows you to make queries like:
`http://localhost/test_LN/public/vehicles/?search=AT-AT`
These searches are case-insensitive. Check out the individual resource documentation below to see which fields are supported for what resource.

## Encodings
As the original SWAPI, this clone provides two encodings you can render the data with.
JSON
JSON is the standard data format provided by default.

Wookiee
The same format as JSON, but in the language of the Wookies, Shyriiwook
To use the Wookie encoder, just append format=wookiee to your urls:
`http://localhost/test_LN/public/vehicles/?search=AT-AT&format=wookie`


## Resources
Since this was made as a test, I've implemented just the necessary.
I implemented Starships and Vehicles as that was what was requested by the test, but I also implemented Films as it was a good way to practice table relationships, and the Root resource to show the routing.

**ROOT:**

The root resource provides information on the routing of the API. <br>
Example request:<br>
`http://localhost/test_LN/public/`
<br><br>
Example response:
`{
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
}`

The [] brackets mean that it is not necessary, so /films/ can also be /films

And the {} curly brackets mean that it is a variable, so if the id was 1, /vehicles/{id}/ would be /vehicles/1/

**INIT:**

This endpoint creates the database, tables, and scrapes the necessary data for this API to work automatically. It saves a lot of time on the installation, since you don't have to execute any sql files by yourself, and it comes with the plus of the fact that you don't need to use any database managing software to make this API work. 
<br>Example request:<br>
`http://localhost/test_LN/public/init/`
<br>
- Endpoints:
    The parameter is `delete` (empty).
    
    `/init/` --initializes the database, the tables and scrapes data
    `/init/?delete` --same as the other one, but with permissions to delete the database - requires: delete
    
    Attributes:
    `log` An array of strings that contains the log. It tells you what happened in text. 
    `failedInserts` Number of failed inserts.
    `successfulInserts` Number of successful inserts.  
    
    Search fields:
    `name`
    `model`

**Starships:**

The resource Starships provides information about the Starships.<br>
Example request:<br>
`http://localhost/test_LN/public/starships/`
<br>
- Endpoints:
    The parameters are `id` (number) and `page` (number).
    
    `/starships/` --get all the starships resources, divided by pages of ten.<br/>
    `/starships/?page={page}` --get the specified page. - requires: page<br/>
    `/starships/{id}/` --get an individual starship by its ID. - requires: id<br>
    
    Attributes:
    The attributes are identical to SWAPI's, but with an id and amount attributes added
    
    Search fields:
    `name`
    `model`

- EXTRA ENDPOINTS:
    The /amount/ route lets you control the amount of starships there are of each single one. There is data validation in all of them, including things like if a decreased amount is smaller than the set amount, etc.<br>
    The parameters are `id` (number) and `amount` (number).
    <br>
    Example request:<br>
        `http://localhost/test_LN/public/starships/amount/increase/?id=1&amount=10`<br>

    `/starships/amount/get/?id={id}` --get the amount of starships by its ID - requires: id<br>
    `/starships/amount/set/?id={id}&amount={amount}` --set the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
    `/starships/amount/increase/?id={id}&amount={amount}` --increase the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
    `/starships/amount/decrease/?id={id}&amount={amount}` --decrease the amount of starships by its ID and an AMOUNT - requires: id,amount<br>
    
    Attributes:
    Set, increase and decrease respond with a success attribute and the detail attribute, while get responds with the amount attribute.

**Vehicles:**

The resource Vehicles provides information about the Vehicles. This one works identical to the Starship resource.<br>
Example request:<br>
`http://localhost/test_LN/public/vehicles/`<br>

- Endpoints:
    The parameters are `id` (number) and `page` (number).
    
    `/vehicles/` --get all the vehicles resources, divided by pages of ten.
    `/film/?page={page}` --get the specified page. - requires: page
    `/vehicles/{id}/` --get an individual vehicle by its ID. - requires: id
    
    Attributes:
    The attributes are identical to SWAPI's, but with an id and amount attributes added
    
    Search fields:
    `name`
    `model`

- EXTRA ENDPOINTS:
    The /amount/ route lets you control the amount of vehicles there are of each single one. There is data validation in all of them, including things like if a decreased amount is smaller than the set amount, etc.
    The parameters are `id` (number) and `amount` (number).
    <br>
    Example request:<br>
        `http://localhost/test_LN/public/vehicles/amount/increase/?id=1&amount=10`<br>
    `/vehicles/amount/get/?id={id}` --get the amount of vehicles by its ID - requires: id
    `/vehicles/amount/set/?id={id}&amount={amount}` --set the amount of vehicles by its ID and an AMOUNT - requires: id,amount
    `/vehicles/amount/increase/?id={id}&amount={amount}` --increase the amount of vehicles by its ID and an AMOUNT - requires: id,amount
    `/vehicles/amount/decrease/?id={id}&amount={amount}` --decrease the amount of vehicles by its ID and an AMOUNT - requires: id,amount
    
    Attributes:
     Set, increase and decrease respond with a success attribute and the detail attribute, while get responds with the amount attribute.

**Films:**

The resource Films provides information about the Films. <br>
Example request:<br>
`http://localhost/test_LN/public/films/`<br>

- Endpoints:
The parameters are `id` (number) and `amount` (page).

    `/films/` --get all the films resources, divided by pages of ten.
    `/films/?page={page}` --get the specified page. - requires: page
    `/films/{id}/` --get an individual film by its ID. - requires: id
    
   Attributes:
    The attributes are identical to SWAPI's, but with an id added
    
   Search fields:
    `title`

## Testing
   There is not a lot to test in this API. But the most failsafe way to test if it works or not is to use the /init/ endpoint, as it interacts with almost everything at the same time. 
   If /init/ is working correctly, nothing should stop working.
## Can I look at the code and use it?

If you find something useful, sure! The proyect was made as a test, so the API is an open source proyect.

By the way, it would be really cool if you checked out https://github.com/cetzz/SWAPICLONE_Front , it is a front-end proyect that I made as an additional characteristic. It uses this API , so you can find some examples on how to use it there! Plus it looks pretty cool in my opinion. It's fully responsive.

-Cristian Metz
