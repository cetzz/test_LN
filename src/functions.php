<?php
/***
 * wookie translations
 * @param   $encode=false     decides if its going to be encoded or not  
 * @param   $json             json to be encoded      
 * @return  translated json
 */
    function wookieEncode($encode,$json){
        if(isset($encode['format'])){
            if($encode['format']=='wookie'){
                $json=strtr ($json,ENGLISH_TO_WOOKIE);
            }
        }
        return $json;
    }

/***
 * get the last insert of the database
 * @return  last insert ID,
 */
    function getLastInsert(){
        global $connection;
        return $connection->lastInsertId();
    }
    
/***
 * execute query
 * @param   $query  
 * @return  if the query was successful or not
 */
    function executeQuery($query){
        global $connection;
        return $connection->query($query);
    }

    function loadQueryArray($query){
        $result = executeQuery($query);
        return $result == null ? null : $result->fetchAll();
    }
/***
 * prepare query to use ->execute()
 * @param   $query  
 * @return  prepared query,
 */
    function prepareQuery($query){
        global $connection;
        return $connection->prepare($query);
    }
    
/***
 * remove Z timezone auxiliary, parse data
 * @param   $query  
 * @return  parsed date
 */
    function getParsedDate($date) {
        return str_replace( // remove timezone auxiliary
            'Z',
            '',
            explode('.', $date)[0] 
        );
    }
/***
 * get all vehicles that were in a film
 * @param   $filmID  
 * @return  array with results
 */
    function getVehiclesOfFilm($filmID){
        return executeQuery('SELECT Vehicles.* FROM VehiclesInFilms INNER JOIN Vehicles ON Vehicles.VehicleID = VehiclesInFilms.VehicleID WHERE VehiclesInFilms.FilmID='.$filmID);
    }

/***
 * get all starships that were in a film
 * @param   $filmID  
 * @return  array with results
 */
    function getStarshipsOfFilm($filmID){
        return executeQuery('SELECT Starships.* FROM StarshipsInFilms INNER JOIN Starships ON Starships.StarshipID = StarshipsInFilms.StarshipID WHERE StarshipsInFilms.FilmID='.$filmID);
    }

/***
 * get all film that a starship was starred in
 * @param   $starshipID  
 * @return  array with results
 */
    function getFilmsOfStarship($starshipID){
        return executeQuery('SELECT Films.* FROM StarshipsInFilms INNER JOIN Films ON Films.FilmID = StarshipsInFilms.FilmID WHERE StarshipsInFilms.StarshipID='.$starshipID);
    }

/***
 * get all film that a vehicle was starred in
 * @param   $vehicleID 
 * @return  array with results
 */
    function getFilmsOfVehicle($vehicleID){
        return executeQuery('SELECT Films.* FROM VehiclesInFilms INNER JOIN Films ON Films.FilmID = VehiclesInFilms.FilmID WHERE VehiclesInFilms.VehicleID='.$vehicleID);
    }
?>