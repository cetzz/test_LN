<?php
// ini_set('display_errors', '1');

    $return=array();

	$dbhost = '127.0.0.1';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'swapi_extension';
 	$dbcharset = 'utf8';
    define('SWAPI','https://swapi.dev/api');

/***************************************************************************
 * Create database
 */
    try {
		$connection = new PDO('mysql:host=' . $dbhost . ';charset=' . $dbcharset, $dbuser, $dbpass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
		]);
	} catch (PDOException $e) {
		die('Error de Conexión (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />IP Sevidor: ' . $dbhost . '<br />Usuario: ' . $dbuser);
	}
    if(isset($_REQUEST['delete'])){
        if (executeQuery('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$dbname.'"')->fetch()[0]!=null){
            executeQuery('DROP DATABASE '.$dbname);
            $return['log'][] = 'Database deleted correctly.';
        }else{
            $return['log'][] = 'The database doesn\'t exist, it couldn\'t be deleted.';
        }
    }
    if (executeQuery('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$dbname.'"')->fetch()[0]==null){
        executeQuery('CREATE DATABASE '.$dbname );
        $return['log'][] = 'Database created correctly.';
        try {
            $connection = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname. ';charset=' . $dbcharset, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);
        } catch (PDOException $e) {
            die('Error de Conexión (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />IP Sevidor: ' . $dbhost . '<br />Usuario: ' . $dbuser);
        }
    }else{
        $return['log'][] = 'The database <i>'.$dbname.'</i> couldn\'t be created, please check if you have a database with the same name. ';
        $return['deleteURL']='init.php?delete';
        $return['failedInserts']=0;
        $return['successfulInserts']=0;
        echo '<pre>';
        print_r(json_encode($return));
        echo '</pre>';
        exit();
    }

/***************************************************************************
 * Initialize variables and create $ch.
 */
    
    createTables();
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $totalSuccesses= 0;
    $totalFails= 0;

/***************************************************************************
 * Store Films on the database
 */

    curl_setopt($ch, CURLOPT_URL,SWAPI.'/films/');
    $successful=0;
    $failed=0;
    do{         
        $response= json_decode(curl_exec($ch),true);
        foreach ($response['results'] as $film){
            $filmResponse = insertFilm($film);
            if ($filmResponse['status']){
                $successful++;
            }else{
                $failed--;
            }
        }
            if (isset($response['next'])){
                curl_setopt($ch, CURLOPT_URL, SWAPI.'/films/?'.substr($response['next'], strpos($response['next'], "?") + 1));
                $response = array();
                $response = json_decode(curl_exec($ch),true);
            }
    }while(isset($response['next']));
    $return['log'][] = ' '.$successful.' films were inserted successfully, '.$failed.' failed.';


/***************************************************************************
 * Store Starships on the database
 */

    curl_setopt($ch, CURLOPT_URL,SWAPI.'/starships/');
    $successful=0;
    $failed=0;
    $successfulFilms=0;
    $failedFilms=0;
    do{     
        $response = json_decode(curl_exec($ch),true);
        foreach ($response['results'] as $starship){
            $starshipResponse=insertStarship($starship);
            if ($starshipResponse['status']){
                $successful++;
            }else{
                $failed--;
            }
            $successfulFilms=$successfulFilms+$starshipResponse['films_successful'];
            $failedFilms=$failedFilms+$starshipResponse['films_failed'];
        }
        if (isset($response['next'])){
            curl_setopt($ch, CURLOPT_URL, SWAPI.'/starships/?'.substr($response['next'], strpos($response['next'], "?") + 1));
        }
    }while(isset($response['next']));
    $totalSuccesses= $totalSuccesses + $successful + $successfulFilms;
    $totalFails= $totalFails + $failed + $failedFilms;
    $return['log'][] = ' '.$successful.' starships were inserted successfully, '.$failed.' failed.';
    $return['log'][] = ' '.$successfulFilms.' starship and film relationships were inserted successfully, '.$failedFilms.' failed.';

/***************************************************************************
 * Store Vehicles on the database
 */

    curl_setopt($ch, CURLOPT_URL,SWAPI.'/vehicles/');
    $successful=0;
    $failed=0;
    $successfulFilms=0;
    $failedFilms=0;
    do{     
        $response = json_decode(curl_exec($ch),true);
        foreach ($response['results'] as $vehicle){
            $vehicleResponse=insertVehicle($vehicle);
            if ($vehicleResponse['status']){
                $successful++;
            }else{
                $failed--;
            }
            $successfulFilms=$successfulFilms+$vehicleResponse['films_successful'];
            $failedFilms=$failedFilms+$vehicleResponse['films_failed'];
        }
        if (isset($response['next'])){
            curl_setopt($ch, CURLOPT_URL, SWAPI.'/vehicles/?'.substr($response['next'], strpos($response['next'], "?") + 1));
        }
    }while(isset($response['next']));
    $totalSuccesses= $totalSuccesses + $successful + $successfulFilms;
    $totalFails= $totalFails + $failed + $failedFilms;
    $return['log'][] = ' '.$successful.' vehicles were inserted successfully, '.$failed.' failed.';
    $return['log'][] = ' '.$successfulFilms.' vehicle and film relationships were inserted successfully, '.$failedFilms.' failed.';


/***************************************************************************
 * Store Films on the database
 */

    $return['failedInserts']=$totalFails;
    $return['successfulInserts']=$totalSuccesses;
    $return['log'][] = ' Initialization completed. '.$totalSuccesses.' inserts were successful, '.$totalFails.' failed.';
    echo '<pre>';
    print_r(json_encode($return));
    echo '</pre>';

    
/***
 * Create tables
 */
    function createTables(){
        $query="
        DROP TABLE IF EXISTS `Starships`;
        CREATE TABLE `Starships`  (
            `StarshipID` int(255) NOT NULL AUTO_INCREMENT,
            `StarshipName` varchar(255) NULL,
            `StarshipModel` varchar(255) NULL,
            `StarshipClass` varchar(255) NULL,
            `StarshipManufacturer` varchar(255) NULL,
            `StarshipCost` varchar(255) NULL,
            `StarshipLength` varchar(255) NULL,
            `StarshipCrew` varchar(255) NULL,
            `StarshipPassengers` varchar(255) NULL,
            `StarshipMAS` varchar(255) NULL,
            `StarshipHRating` varchar(255) NULL,
            `StarshipMGLT` varchar(255) NULL,
            `StarshipCargoCapacity` varchar(255) NULL,
            `StarshipConsumables` varchar(255) NULL,
            `StarshipURL` varchar(255) NULL,
            `StarshipCreated` datetime(6) NULL,
            `StarshipEdited` datetime(6) DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(6),
            `StarshipAmount` int(255) NULL,
            PRIMARY KEY (`StarshipID`)
        );
        DROP TABLE IF EXISTS `Vehicles`;
        CREATE TABLE `Vehicles`  (
            `VehicleID` int(255) NOT NULL AUTO_INCREMENT,
            `VehicleName` varchar(255) NULL,
            `VehicleModel` varchar(255) NULL,
            `VehicleClass` varchar(255) NULL,
            `VehicleManufacturer` varchar(255) NULL,
            `VehicleCost` varchar(255) NULL,
            `VehicleLength` varchar(255) NULL,
            `VehicleCrew` varchar(255) NULL,
            `VehiclePassengers` varchar(255) NULL,
            `VehicleMAS` varchar(255) NULL,
            `VehicleCargoCapacity` varchar(255) NULL,
            `VehicleConsumables` varchar(255) NULL,
            `VehicleURL` varchar(255) NULL,
            `VehicleCreated` datetime(6) NULL,
            `VehicleEdited` datetime(6) DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(6),
            `VehicleAmount` int(255) NULL,
            PRIMARY KEY (`VehicleID`)
        ); 
        DROP TABLE IF EXISTS `Films`;
        CREATE TABLE `Films`  (
            `FilmID` int(255) NOT NULL AUTO_INCREMENT,
            `FilmTitle` varchar(255) NULL,
            `FilmEpisodeID` int(255) NULL,
            `FilmOpeningCrawl` text(65535) NULL,
            `FilmDirector` varchar(255) NULL,
            `FilmProducer` varchar(255) NULL,
            `FilmReleaseDate` datetime(6) NULL,
            `FilmURL` varchar(255) NULL,
            `FilmCreated` datetime(6) NULL,
            `FilmEdited` datetime(6) NULL DEFAULT current_timestamp(255) ON UPDATE CURRENT_TIMESTAMP(255),
            PRIMARY KEY (`FilmID`)
        ); 
            DROP TABLE IF EXISTS StarshipsInFilms;
            CREATE TABLE `StarshipsInFilms`  (
            `StarshipID` int(255) NOT NULL,
            `FilmID` int(255) NOT NULL ,
            PRIMARY KEY (`StarshipID`, `FilmID`)
            );
            ALTER TABLE StarshipsInFilms
            ADD 
            CONSTRAINT `FK_SF_StarshipID` FOREIGN KEY (`StarshipID`) REFERENCES Starships (`StarshipID`) ON DELETE CASCADE ON UPDATE RESTRICT, 
            ADD
            CONSTRAINT `FK_SF_FilmID` FOREIGN KEY (`FilmID`) REFERENCES Films (`FilmID`) ON DELETE CASCADE ON UPDATE RESTRICT;
            DROP TABLE IF EXISTS VehiclesInFilms;
            CREATE TABLE `VehiclesInFilms`  (
            `VehicleID` int(255) NOT NULL,
            `FilmID` int(255) NOT NULL ,
            PRIMARY KEY (`VehicleID`, `FilmID`)
            );
            ALTER TABLE VehiclesInFilms
            ADD 
            CONSTRAINT `FK_VF_VehicleID` FOREIGN KEY (`VehicleID`) REFERENCES Vehicles (`VehicleID`) ON DELETE CASCADE ON UPDATE RESTRICT, 
            ADD
            CONSTRAINT `FK_VF_FilmID` FOREIGN KEY (`FilmID`) REFERENCES Films (`FilmID`) ON DELETE CASCADE ON UPDATE RESTRICT;";    
        executeQuery($query);
    }
    
/***
 * Insert   Film
 * @param   $film                           Coming from the results of SWAPI/films/
 * @return  if insert was successful or not
 */
    function insertFilm($film){
        $response=array();
        $query="INSERT INTO `Films` (
            `FilmTitle`,
            `FilmEpisodeID`,
            `FilmOpeningCrawl`,
            `FilmDirector`,
            `FilmProducer`,
            `FilmReleaseDate`,
            `FilmURL`,
            `FilmCreated`
            )
        VALUES
            (   
            :FilmTitle,
            :FilmEpisodeID,
            :FilmOpeningCrawl,
            :FilmDirector,
            :FilmProducer,
            :FilmReleaseDate,
            :FilmURL,
            :FilmCreated     
            );
        ";
        $array=array(
            'FilmTitle'=>$film['title'],
            'FilmEpisodeID'=>$film['episode_id'],
            'FilmOpeningCrawl'=>$film['opening_crawl'],
            'FilmDirector'=>$film['director'],
            'FilmProducer'=>$film['producer'],
            'FilmReleaseDate'=> $film['release_date'],
            'FilmURL'=>$film['url'],
            'FilmCreated'=> getParsedDate($film['created'])
        );
        $statement=prepareQuery($query);
        if($statement->execute($array)){
                $response['status']=true;
        }else{
                $response['status']=false;   
        }
        return $response;
    }
    
/***
 * Insert   Vehicle
 * @param   $vehicle                        Coming from the results of SWAPI/vehicles/
 * @return  if insert was successful or not,
 * amount of successful and failed
 * relationship inserts
 */
    function insertVehicle($vehicle){
        $response=array();
        $query="INSERT INTO `Vehicles` (
            `VehicleName`,
            `VehicleModel`,
            `VehicleClass`,
            `VehicleManufacturer`,
            `VehicleCost`,
            `VehicleLength`,
            `VehicleCrew`,
            `VehiclePassengers`,
            `VehicleMAS`,
            `VehicleCargoCapacity`,
            `VehicleConsumables`,
            `VehicleURL`,
            `VehicleCreated`,
            `VehicleAmount` 
        )
        VALUES
            (            
            :VehicleName,
            :VehicleModel,
            :VehicleClass,
            :VehicleManufacturer,
            :VehicleCost,
            :VehicleLength,
            :VehicleCrew,
            :VehiclePassengers,
            :VehicleMAS,
            :VehicleCargoCapacity,
            :VehicleConsumables,
            :VehicleURL,
            :VehicleCreated,
            :VehicleAmount 
            );
        "; 
        $array=array(
            'VehicleName'=>$vehicle['name'],
            'VehicleModel'=>$vehicle['model'],
            'VehicleClass'=>$vehicle['vehicle_class'],
            'VehicleManufacturer'=>$vehicle['manufacturer'],
            'VehicleCost'=>$vehicle['cost_in_credits'],
            'VehicleLength'=>$vehicle['length'],
            'VehicleCrew'=>$vehicle['crew'],
            'VehiclePassengers'=>$vehicle['passengers'],
            'VehicleMAS'=>$vehicle['max_atmosphering_speed'],
            'VehicleCargoCapacity'=>$vehicle['cargo_capacity'],
            'VehicleConsumables'=>$vehicle['consumables'],
            'VehicleURL'=>$vehicle['url'],
            'VehicleCreated'=> getParsedDate($vehicle['created']),
            'VehicleAmount'=>0);
        $statement=prepareQuery($query);
        if($statement->execute($array)){
                $response['status']=true;
        }else{
                $response['status']=false;   
        }
        $vehicleID=getLastInsert();      
        $response['films_successful']=0;
        $response['films_failed']=0;      
        foreach($vehicle['films'] as $film){
            $query='INSERT INTO `VehiclesInFilms`(`VehicleID`, `FilmID`) VALUES ('.$vehicleID.', :FilmID)';
            $array=array('FilmID'=>str_replace('/','',substr($film, strpos($film, "/films/") + 7)));
            $statement=prepareQuery($query);
            if($statement->execute($array)){
                $response['films_successful']++;
            }else{
                $response['films_failed']++;
            }
        }
        return $response;
    }

    

/***
 * Insert   Starship
 * @param   $starship                        Coming from the results of SWAPI/starships/
 * @return  if insert was successful or not,
 * amount of successful and failed
 * relationship inserts
 */
    function insertStarship($starship){
        $response=array();
        $query="INSERT INTO `Starships` (
            `StarshipName`,
            `StarshipModel`,
            `StarshipClass`,
            `StarshipManufacturer`,
            `StarshipCost`,
            `StarshipLength`,
            `StarshipCrew`,
            `StarshipPassengers`,
            `StarshipMAS`,
            `StarshipHRating`,
            `StarshipMGLT`,
            `StarshipCargoCapacity`,
            `StarshipConsumables`,
            `StarshipURL`,
            `StarshipCreated`,
            `StarshipAmount` 
        )
        VALUES
            (   
            :StarshipName,
            :StarshipModel,
            :StarshipClass,
            :StarshipManufacturer,
            :StarshipCost,
            :StarshipLength,
            :StarshipCrew,
            :StarshipPassengers,
            :StarshipMAS,
            :StarshipHRating,
            :StarshipMGLT,
            :StarshipCargoCapacity,
            :StarshipConsumables,
            :StarshipURL,
            :StarshipCreated,
            :StarshipAmount         
            );
        ";
        $array=array(
            'StarshipName'=>$starship['name'],
            'StarshipModel'=>$starship['model'],
            'StarshipClass'=>$starship['starship_class'],
            'StarshipManufacturer'=>$starship['manufacturer'],
            'StarshipCost'=>$starship['cost_in_credits'],
            'StarshipLength'=>$starship['length'],
            'StarshipCrew'=>$starship['crew'],
            'StarshipPassengers'=>$starship['passengers'],
            'StarshipMAS'=>$starship['max_atmosphering_speed'],
            'StarshipHRating'=>$starship['hyperdrive_rating'],
            'StarshipMGLT'=>$starship['MGLT'],
            'StarshipCargoCapacity'=>$starship['cargo_capacity'],
            'StarshipConsumables'=>$starship['consumables'],
            'StarshipURL'=>$starship['url'],
            'StarshipCreated'=> getParsedDate($starship['created']),
            'StarshipAmount' => 0 );
        $statement=prepareQuery($query);
        if($statement->execute($array)){
                $response['status']=true;
        }else{
                $response['status']=false;   
        }
        $starshipID=getLastInsert();      
        $response['films_successful']=0;
        $response['films_failed']=0;      
        foreach($starship['films'] as $film){
            $query='INSERT INTO `StarshipsInFilms`(`StarshipID`, `FilmID`) VALUES ('.$starshipID.', :FilmID)';
            $array=array('FilmID'=>str_replace('/','',substr($film, strpos($film, "/films/") + 7)));
            $statement=prepareQuery($query);
            if($statement->execute($array)){
                $response['films_successful']++;
            }else{
                $response['films_failed']++;
            }
        }
        return $response;
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
?>