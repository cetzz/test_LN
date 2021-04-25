<?php

/***
 * Increase Starship amount by ID
 * @param   $starshipID the id of the starship
 * @param   $amount the amount that you want to increase
 * @return  json of the detail(detail) and if it was successful(success) or not
 */
function increaseStarshipAmountByID($starshipID,$amount){
    $return=array();
    $checkAmount=checkAmount($amount);
    if($checkAmount['ok']){
        $query= 'UPDATE Starships SET StarshipAmount=StarshipAmount+'.$amount.' WHERE StarshipID=:StarshipID';
        $statement=prepareQuery($query);
        $array=array('StarshipID'=>$starshipID);
        $statement->execute($array);
        if($statement->rowCount()==0){
            $return['success']=false;
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            return json_encode($return);
        }else{
            $return['success']=true;
            $return['detail']=MSG_AMOUNT_INCREASED;
            return json_encode($return);
        }
    }else{
        $return['success']=false;
        $return['detail']=$checkAmount['detail'];
        return json_encode($return);
    }
}

/***
 * Increase vehicle amount by ID
 * @param   $VehicleID the id of the vehicle
 * @param   $amount the amount that you want to increase
 * @return  json of the detail(detail) and if it was successful(success) or not
 */
function increaseVehicleAmountByID($vehicleID,$amount){
    $return=array();
    $checkAmount=checkAmount($amount);
    if($checkAmount['ok']){
        $query= 'UPDATE Vehicles SET VehicleAmount=VehicleAmount+'.$amount.' WHERE VehicleID=:VehicleID';
        $statement=prepareQuery($query);
        $array=array('VehicleID'=>$vehicleID);
        $statement->execute($array);
        if($statement->rowCount()==0){
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            $return['success']=false;
            return json_encode($return);
        }else{
            $return['detail']=MSG_AMOUNT_INCREASED;
            $return['success']=true;
            return json_encode($return);
        }
    }else{
        $return['detail']=$checkAmount['detail'];
        $return['success']=false;
        return json_encode($return);
    }
}

/***
 * Decrease vehicle amount by ID
 * @param   $VehicleID the id of the vehicle
 * @param   $amount the amount that you want to dencrease
 * @return  json of the detail(detail) and if it was successful(success) or not
 */
function decreaseVehicleAmountByID($vehicleID,$amount){
    $return=array();
    $checkAmount=checkAmount($amount);
    if($checkAmount['ok']){
        $statement=prepareQuery('SELECT VehicleAmount FROM Vehicles WHERE VehicleID= :VehicleID');
        $array=array('VehicleID'=>$vehicleID);
        $statement->execute($array);
        $result=$statement->fetch();
        if ($result!=null){
            if(($result['VehicleAmount']-$amount)<0){
                $return['detail']=MSG_LOWERTHANZERO_AMOUNT;
                $return['success']=false;
                return json_encode($return);
            }    
        }else{
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            $return['success']=false;
            return json_encode($return);
        }
        $statement=prepareQuery('UPDATE Vehicles SET VehicleAmount=VehicleAmount-'.$amount.' WHERE VehicleID=:VehicleID');
        $statement->execute($array);
        if($statement->rowCount()==0){
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            $return['success']=false;
            return json_encode($return);
        }else{
            $return['detail']=MSG_AMOUNT_DECREASED;
            $return['success']=true;
            return json_encode($return);
        }
    }else{
        $return['detail']=$checkAmount['detail'];
        $return['success']=false;
        return json_encode($return);
    }
}


/***
 * Decrease starship amount by ID
 * @param   $StarshipID the id of the starship
 * @param   $amount the amount that you want to dencrease
 * @return  json of the detail(detail) and if it was successful(success) or not
 */
function decreaseStarshipAmountByID($starshipID,$amount){
    $return=array();
    $checkAmount=checkAmount($amount);
    if($checkAmount['ok']){
        $statement=prepareQuery('SELECT StarshipAmount FROM Starships WHERE StarshipID= :StarshipID');
        $array=array('StarshipID'=>$starshipID);
        $statement->execute($array);
        $result=$statement->fetch();
        if ($result!=null){
            if(($result['StarshipAmount']-$amount)<0){
                $return['detail']=MSG_LOWERTHANZERO_AMOUNT;
                $return['success']=false;
                return json_encode($return);
            }    
        }else{
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            $return['success']=false;
            return json_encode($return);
        }
        $statement=prepareQuery('UPDATE Starships SET StarshipAmount=StarshipAmount-'.$amount.' WHERE StarshipID=:StarshipID');
        $statement->execute($array);
        if($statement->rowCount()==0){
            $return['detail']=MSG_NO_ROWS_AFFECTED;
            $return['success']=false;
            return json_encode($return);
        }else{
            $return['detail']=MSG_AMOUNT_DECREASED;
            $return['success']=true;
            return json_encode($return);
        }
    }else{
        $return['detail']=$checkAmount['detail'];
        $return['success']=false;
        return json_encode($return);
    }
}