<?php
// ini_set('display_errors', '1');
	$dbhost = '127.0.0.1';
	$dbuser = 'root';
	$dbpass = '';
	$dbname = 'swapi_extension';
 	$dbcharset = 'utf8';
    define('SWAPI','https://swapi.dev/api');

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
            echo ('<p style="text-align:center">Base de datos eliminada correctamente.</p><br>');
        }else{
            echo ('<p style="text-align:center">La base de datos no existe, no pudo ser borrada.</p><br>');
        }
    }
    if (executeQuery('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "'.$dbname.'"')->fetch()[0]==null){
        executeQuery('CREATE DATABASE '.$dbname );
        echo ('<p style="text-align:center">Base de datos creada correctamente.</p><br>');
        try {
            $connection = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname. ';charset=' . $dbcharset, $dbuser, $dbpass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING
            ]);
        } catch (PDOException $e) {
            die('Error de Conexión (' . mysqli_connect_errno() . ') '. mysqli_connect_error() . '<br />IP Sevidor: ' . $dbhost . '<br />Usuario: ' . $dbuser);
        }
    }else{
        echo '<p style="text-align:center">La base de datos <i>'.$dbname.'</i> no se pudo crear, verifique si tiene una base de datos con el mismo nombre. <br><br>
            <a href="init.php?delete"> Borrar la base existente </a>
            </p><br>
        ';
        exit();
    }

    createTables();

	$ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    curl_setopt($ch, CURLOPT_URL,SWAPI.'/films/');
    $response= json_decode(curl_exec($ch),true);
    do{     
        foreach ($response['results'] as $film){
            insertFilm($film);
        }
            if (isset($response['next'])){
                curl_setopt($ch, CURLOPT_URL, SWAPI.'/films/?'.substr($response['next'], strpos($response['next'], "?") + 1));
                $response = array();
                $response = json_decode(curl_exec($ch),true);
            }
    }while(isset($response['next']));
    
    curl_setopt($ch, CURLOPT_URL,SWAPI.'/starships/');
    $response= json_decode(curl_exec($ch),true);
    $i=0;
    do{     
        foreach ($response['results'] as $starship){
            insertStarship($starship);
        }
            if (isset($response['next'])){
                curl_setopt($ch, CURLOPT_URL, SWAPI.'/starships/?'.substr($response['next'], strpos($response['next'], "?") + 1));
                $response = array();
                $response = json_decode(curl_exec($ch),true);
            }
    }while(isset($response['next']));


    curl_setopt($ch, CURLOPT_URL,SWAPI.'/vehicles/');
    $response= json_decode(curl_exec($ch),true);
    do{     
        foreach ($response['results'] as $vehicle){
            insertVehicle($vehicle);
        }
            if (isset($response['next'])){
                curl_setopt($ch, CURLOPT_URL, SWAPI.'/vehicles/?'.substr($response['next'], strpos($response['next'], "?") + 1));
                $response = array();
                $response = json_decode(curl_exec($ch),true);
            }
    }while(isset($response['next']));

    echo '<p style="text-align:center"> Tablas de Películas, Naves espaciales, Vehículos y sus relaciones cargadas correctamente.</p><br>';
    echo '<p style="text-align:center"> Inicialización completada.</p><br>';

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
            `FilmOpeningCrawl` varchar(255) NULL,
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

    function insertFilm($film){
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
            'FilmReleaseDate'=>$film['release_date'],
            'FilmURL'=>$film['url'],
            'FilmCreated'=>$film['created']
        );
        $statement=prepareQuery($query);
        $statement->execute($array);
    }
    
    function insertVehicle($vehicle){
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
            'VehicleCreated'=>$vehicle['created'],
            'VehicleAmount'=>0);
        $statement=prepareQuery($query);
        $statement->execute($array);
        $vehicleID=getLastInsert();
        foreach($vehicle['films'] as $film){
            $query='INSERT INTO `VehiclesInFilms`(`VehicleID`, `FilmID`) VALUES ('.$vehicleID.', :FilmID)';
            $array=array('FilmID'=>str_replace('/','',substr($film, strpos($film, "/films/") + 7)));
            $statement=prepareQuery($query);
            $statement->execute($array);
        }
    }

    function insertStarship($starship){
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
            'StarshipCreated'=>$starship['created'],
            'StarshipAmount' => 0 );
        $statement=prepareQuery($query);
        $statement->execute($array);
        $starshipID=getLastInsert();
        foreach($starship['films'] as $film){
            $query='INSERT INTO `StarshipsInFilms`(`StarshipID`, `FilmID`) VALUES ('.$starshipID.', :FilmID)';
            $array=array('FilmID'=>str_replace('/','',substr($film, strpos($film, "/films/") + 7)));
            $statement=prepareQuery($query);
            $statement->execute($array);
        }
    }
    function getLastInsert(){
        global $connection;
        return $connection->lastInsertId();
    }
    function executeQuery($query){
        global $connection;
        return $connection->query($query);
    }
    function prepareQuery($query){
        global $connection;
        return $connection->prepare($query);
    }
?>