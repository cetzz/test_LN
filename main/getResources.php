<?php

/***
 * Search a film by its ID
 * @param   $filmID 
 * @param   $encode = true      makes it so it is json_encoded or not.
 * @return  response depending of encode
 */
function searchFilmByID($filmID,$encode=true){
    $statement=prepareQuery('SELECT * FROM Films WHERE FilmID= :FilmID');
    $array=array('FilmID'=>$filmID);
    $statement->execute($array);
    $result=$statement->fetch();
    $return=array();
    if ($result!=null){
        $return['id']=$result['FilmID'];
        $return['title']=$result['FilmTitle'];
        $return['episode_id']=$result['FilmEpisodeID'];
        $return['opening_crawl']=$result['FilmOpeningCrawl'];
        $return['director']=$result['FilmDirector'];
        $return['producer']=$result['FilmProducer'];
        $return['release_date']=$result['FilmReleaseDate'];
        $return['characters'][]=MSG_NOT_YET_DEVELOPED;
        $return['planets'][]=MSG_NOT_YET_DEVELOPED;
        $starshipsInTheFilm=getStarshipsOfFilm($result['FilmID']);
        foreach($starshipsInTheFilm as $starship){
            $return['starships'][]=$starship['StarshipURL'];
        }
        $vehiclesInTheFilm=getVehiclesOfFilm($result['FilmID']);
        foreach($vehiclesInTheFilm as $vehicle){
            $return['vehicles'][]=$vehicle['VehicleURL'];
        }
        $return['species'][]=MSG_NOT_YET_DEVELOPED;
        $return['created']=$result['FilmCreated'];
        $return['edited']=$result['FilmEdited'];
        $return['url']=$result['FilmURL'];
    }else{
        $return['detail']=MSG_NOT_FOUND;
    }
    
    return($encode?json_encode($return):$return);
}

/***
 * Search a starship by its ID
 * @param   $starshipID 
 * @param   $encode = true      makes it so it is json_encoded or not.
 * @return  response depending of encode
 */
function searchStarshipByID($starshipID,$encode=true){
    $statement=prepareQuery('SELECT * FROM Starships WHERE StarshipID= :StarshipID');
    $array=array('StarshipID'=>$starshipID);
    $statement->execute($array);
    $result=$statement->fetch();
    $return=array();
    if ($result!=null){
        $return['id']=$result['StarshipID'];
        $return['name']=$result['StarshipName'];
        $return['model']=$result['StarshipModel'];
        $return['manufacturer']=$result['StarshipManufacturer'];
        $return['cost_in_credits']=$result['StarshipCost'];
        $return['length']=$result['StarshipLength'];
        $return['max_atmosphering_speed']=$result['StarshipMAS'];
        $return['crew']=$result['StarshipCrew'];
        $return['passengers']=$result['StarshipPassengers'];
        $return['cargo_capacity']=$result['StarshipCargoCapacity'];
        $return['consumables']=$result['StarshipConsumables'];
        $return['hyperdrive_rating']=$result['StarshipHRating'];
        $return['MGLT']=$result['StarshipMGLT'];
        $return['starship_class']=$result['StarshipClass'];
        $return['pilots'][]=MSG_NOT_YET_DEVELOPED;
        $filmsOfStarship=getFilmsOfStarship($result['StarshipID']);
        foreach($filmsOfStarship as $film){
            $return['films'][]=$film['FilmURL'];
        }
        $return['created']=$result['StarshipCreated'];
        $return['edited']=$result['StarshipEdited'];
        $return['url']=$result['StarshipURL'];
        $return['amount']=$result['StarshipAmount'];
    }else{
        $return['detail']=MSG_NOT_FOUND;
    }
    
    return($encode?json_encode($return):$return);
}

/***
 * Search a vehicle by its ID
 * @param   $vehicleID 
 * @param   $encode = true      makes it so it is json_encoded or not.
 * @return  response depending of encode
 */
function searchVehicleByID($vehicleID,$encode=true){
    $statement=prepareQuery('SELECT * FROM Vehicles WHERE VehicleID= :VehicleID');
    $array=array('VehicleID'=>$vehicleID);
    $statement->execute($array);
    $result=$statement->fetch();
    $return=array();
    if ($result!=null){
        $return['id']=$result['VehicleID'];
        $return['name']=$result['VehicleName'];
        $return['model']=$result['VehicleModel'];
        $return['manufacturer']=$result['VehicleManufacturer'];
        $return['cost_in_credits']=$result['VehicleCost'];
        $return['length']=$result['VehicleLength'];
        $return['max_atmosphering_speed']=$result['VehicleMAS'];
        $return['crew']=$result['VehicleCrew'];
        $return['passengers']=$result['VehiclePassengers'];
        $return['cargo_capacity']=$result['VehicleCargoCapacity'];
        $return['consumables']=$result['VehicleConsumables'];
        $return['vehicle_class']=$result['VehicleClass'];
        $return['pilots'][]=MSG_NOT_YET_DEVELOPED;
        $filmsOfVehicle=getFilmsOfVehicle($vehicleID);
        foreach($filmsOfVehicle as $film){
            $return['films'][]=$film['FilmURL'];
        }
        $return['created']=$result['VehicleCreated'];
        $return['edited']=$result['VehicleEdited'];
        $return['url']=$result['VehicleURL'];
        $return['amount']=$result['VehicleAmount'];
    }else{
        $return['detail']=MSG_NOT_FOUND;
    }
    
    return($encode?json_encode($return):$return);
}

/***
 * Search films by page
 * @param   $page
 * @return  json of the page
 */
function getFilms($page,$search){
    $return=array();
    $returnresult=array();
    $offset=0;
    if($search!=null){
        $searchQuery='WHERE (FilmTitle  LIKE "%'.$search.'%" )';
        $urlQuery='&search='.$search;
    }else{
        $searchQuery='';
        $urlQuery='';
    } 
    if (is_numeric($page)){
        $offset=getOffset($page);
        $results=loadQueryArray('SELECT FilmID FROM Films '.$searchQuery.' ORDER BY FilmID ASC LIMIT 10 OFFSET '.$offset);
        if($results!=null){
            foreach($results as $result){
                $returnresult[] = searchFilmByID($result['FilmID'],false);
            }
        }else{
            $return['detail']=MSG_NOT_FOUND;
            return(json_encode($return));
        }
    }else{
        $return['detail']=MSG_NOT_FOUND;
        return(json_encode($return));
    }
    $return['count']=loadQueryArray('SELECT COUNT(*) as FilmsCount FROM Films '.$searchQuery)[0]['FilmsCount'];
    if($offset+10<$return['count']){
        $return['next']=LOCAL_URL.'/films/?page='.($page+1).$urlQuery;
    }else{
        $return['next']=null;
    }
    if($page-1>0){
        $return['previous']=LOCAL_URL.'/films/?page='.($page-1).$urlQuery;
    }else{
        $return['previous']=null;
    }
    $return['results']=$returnresult;
    return(json_encode($return));
}

/***
 * Search starships by page
 * @param   $page
 * @return  json of the page
 */
function getStarships($page,$search){
    $return=array();
    $returnresult=array();
    $offset=0;
    if($search!=null){
        $searchQuery='WHERE (StarshipName  LIKE "%'.$search.'%" or StarshipModel LIKE "%'.$search.'%")';
        $urlQuery='&search='.$search;
    }else{
        $searchQuery='';
        $urlQuery='';
    } 
    if (is_numeric($page)){
        $offset=getOffset($page);
        $results=loadQueryArray('SELECT StarshipID FROM Starships '.$searchQuery.' ORDER BY StarshipID ASC LIMIT 10 OFFSET '.$offset);
        if($results!=null){
            foreach($results as $result){
                $returnresult[] = searchStarshipByID($result['StarshipID'],false);
            }
        }else{
            $return['detail']=MSG_NOT_FOUND;
            return(json_encode($return));
        }
    }else{
        $return['detail']=MSG_NOT_FOUND;
        return(json_encode($return));
    }
    $return['count']=loadQueryArray('SELECT COUNT(*) as StarshipsCount FROM Starships '.$searchQuery)[0]['StarshipsCount'];
    if($offset+10<$return['count']){
        $return['next']=LOCAL_URL.'/starships/?page='.($page+1).$urlQuery;
    }else{
        $return['next']=null;
    }
    if($page-1>0){
        $return['previous']=LOCAL_URL.'/starships/?page='.($page-1).$urlQuery;
    }else{
        $return['previous']=null;
    }
    $return['results']=$returnresult;
    return(json_encode($return));
}

/***
 * Search vehicles by page
 * @param   $page
 * @return  json of the page
 */
function getVehicles($page,$search){
    $return=array();
    $returnresult=array();
    $offset=0;
    if($search!=null){
        $searchQuery='WHERE (VehicleName  LIKE "%'.$search.'%" or VehicleModel LIKE "%'.$search.'%")';
        $urlQuery='&search='.$search;
    }else{
        $searchQuery='';
        $urlQuery='';
    } 
    if (is_numeric($page)){
        $offset=getOffset($page);
        $results=loadQueryArray('SELECT VehicleID FROM Vehicles '.$searchQuery.' ORDER BY VehicleID ASC LIMIT 10 OFFSET '.$offset);
        if($results!=null){
            foreach($results as $result){
                $returnresult[] = searchVehicleByID($result['VehicleID'],false);
            }
        }else{
            $return['detail']=MSG_NOT_FOUND;
            return(json_encode($return));
        }
    }else{
        $return['detail']=MSG_NOT_FOUND;
        return(json_encode($return));
    }
    $return['count']=loadQueryArray('SELECT COUNT(*) as VehiclesCount FROM Vehicles '.$searchQuery)[0]['VehiclesCount'];
    if($offset+10<$return['count']){
        $return['next']=LOCAL_URL.'/vehicles/?page='.($page+1).$urlQuery;
    }else{
        $return['next']=null;
    }
    if($page-1>0){
        $return['previous']=LOCAL_URL.'/vehicles/?page='.($page-1).$urlQuery;
    }else{
        $return['previous']=null;
    }
    $return['results']=$returnresult;
    return(json_encode($return));
}


/***
 * search Starship amount by ID
 * @param   $starshipID
 * @return  array
 */
function searchStarshipAmountByID($starshipID){
    $return=array();
    $result=searchStarshipByID($starshipID,false);
    if(isset($result['detail'])){
        return json_encode($result);
    }       
    $return['amount']=$result['amount'];
        
    return json_encode($return);
}

/***
 * search Starship amount by ID
 * @param   $starshipID
 * @return  array
 */
function searchVehicleAmountByID($vehicleID){
    $return=array();
    $result=searchVehicleByID($vehicleID,false);
    if(isset($result['detail'])){
        return json_encode($result);
    }       
    $return['amount']=$result['amount'];
        
    return json_encode($return);
}
/***
 * get offset for pagination
 * @param   $page
 * @return  offset number
 */
function getOffset($page){
    return $page-1>0?($page-1)*10:0;
}

?>