<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
**/

class WorldData{

    private $pdoQuery;
    private $pdoCrud;
    private $userSession;

    public function __construct(){

        $this->pdoQuery = new PDOQuery;
        $this->pdoCrud = new PDOCrud;
        $this->userSession = new UserSession;

    }

    public function getCountries()
    {
        $sql = $this->pdoQuery->fetchAll("SELECT * FROM countries");

        return $sql;
    }

    public function getCities($id = null, $idstate = null)
    {
        
        $select = ($this->userSession->has()) ? ', u.city_id as usercity' : '' ;

        $iduser = $this->userSession->has() ? $this->userSession->get('id') : 0;

        if ($id) {
            $sql = $this->pdoQuery->fetchAll("SELECT c.* $select FROM cities c left outer join users u on u.id = $iduser WHERE c.country_id = :id ORDER BY name ASC", array(
                ':id'  =>  $id
            ));
        }else{
            $sql = $this->pdoQuery->fetchAll("SELECT c.* $select FROM cities c left outer join users u on u.id = $iduser WHERE c.state_id = :id ORDER BY name ASC", array(
                ':id'  =>  $idstate
            ));
        }
        
        return $sql;
    }
    public function getState($id)
    {
        $select = ($this->userSession->has()) ? ', u.city_id as usercity' : '' ;
        $left = ($this->userSession->has()) ? "left outer join users u on u.status <> 3 AND u.id = " . $this->userSession->get('id') : '' ;
        

        $sql = $this->pdoQuery->fetchAll("SELECT s.* $select FROM states s $left WHERE s.country_id = :id ORDER BY name ASC", array(
            ':id'  =>  $id
        ));

        
        return $sql;
    }
}