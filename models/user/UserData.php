<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
 **/

class UserData
{

    private $pdoQuery;
    private $userSession;

    public function __construct()
    {

        $this->pdoQuery = new PDOQuery;
        $this->userSession = new UserSession;
    }

    public function getAllUsers()
    {
        $categorys = $this->pdoQuery->fetchAll('SELECT * FROM users');

        return $categorys;
    }

    public function getWithoutCadastre()
    {
        return $this->pdoQuery->fetchAll("SELECT u.id FROM users u LEFT JOIN user_action ua ON ua.users_id = u.id WHERE ua.id is null");
    }

    /**
     * getUsersTokenNotify
     * pega só o campo de notificação do usuário pra fazer a notificação do app
     * 
     */
    public function getUsersTokenNotify()
    {
        return $this->pdoQuery->fetchAll("SELECT appToken FROM users WHERE appToken is not null  AND status <> 3");
    }

    public function getDataById($id)
    {
        $sql = $this->pdoQuery->fetch("SELECT u.*, c.name as city, st.iso2 as uf, st.country_code as country, coun.id as country_id FROM users u
        left outer join cities c        on c.id  = u.city_id
        left outer join countries coun  on coun.id  = u.country_id
        left outer join states st       on st.id = c.state_id
        WHERE u.id = :id", array(
            ':id' => $id
        ));

        if($sql != false){

            $sql['guide_property'] = $this->pdoQuery->fetchAll("SELECT gi.* FROM guide_inns gi WHERE gi.users_id = $sql[id]");
            $sql['guide_services'] = $this->pdoQuery->fetchAll("SELECT gi.* FROM guide_services gi WHERE gi.users_id = $sql[id]");
            $sql['guide_fishes']   = $this->pdoQuery->fetchAll("SELECT gi.* FROM guide_fishes gi WHERE gi.user_id = $sql[id]");
            $sql['images']   = $this->pdoQuery->fetchAll("SELECT gi.* FROM images_guide gi WHERE gi.users_id = $sql[id]");
        
        }
        
        return $sql;
    }

    public function getData($username, $limit = "")
    {
        $pag = ($limit != "")? "LIMIT " . $limit : "" ;
        $data = $this->pdoQuery->fetch('SELECT * FROM users u WHERE u.email = :email AND u.status <> 3 '. $pag, array(
            ':email' => mb_strtolower($username),
        ));

        return $data;
    }

    public function getAllBanks(){
        $sql = $this->pdoQuery->fetchAll("SELECT * FROM banks");
        return $sql;
    }

    public function getAllGuides($limit = false, $start = false, $status = false){

        $pagination = "";
        if($limit !== false && $start !== false){
            $pagination = "LIMIT $start, $limit";
        }

        $guide_status = "";
        if($status !== false){
            $guide_status = " AND u.guide_status = $status";
        }

        return $this->pdoQuery->fetchAll("SELECT * FROM users u 
        WHERE u.status <> 3 
        AND u.guide = 1
        $guide_status
        $pagination");
    }

    public function getGuideName($id){
        $sql = $this->pdoQuery->fetch("SELECT u.guide_username FROM users u WHERE u.id = :id AND u.status <> 3 AND u.guide = 1", array(
            ':id' => $id,
        ));
        return $sql;
    }

    public function checkUserName($username)
    {
        $sql = $this->pdoQuery->fetch("SELECT id FROM users WHERE users.username = :email AND status <> 3", array(
            ':email'  =>  $username
        ));

        return $sql;
    }

    public function checkEmail($email)
    {
        $sql = $this->pdoQuery->fetch("SELECT id FROM users WHERE users.email = :email AND status <> 3", array(
            ':email'    => mb_strtolower($email)
        ));

        return $sql;
    }

    public function checkPhone($phone)
    {
        $sql = $this->pdoQuery->fetch("SELECT id FROM users WHERE users.phone = :phone AND status <> 3", array(
            ':phone' => mb_strtolower($phone)
        ));

        return $sql;
    }

    public function checkUser($username, $password)
    {
        return $this->pdoQuery->fetch("SELECT * FROM users u WHERE u.email = :login AND u.password = :password AND u.status <> 3", array(
            ':login'   => mb_strtolower($username),
            ':password' => $password
        ));
    }

    public function checkValidation($username)
    {
        $sql =  $this->pdoQuery->fetch("SELECT * FROM users u WHERE u.email = :login AND u.validation_email = 0 AND u.status <> 3", array(
            ':login'    =>  $username
        ));

        return $sql;
    }

    private function saveData(array $data)
    {
        $pdo = array(
            'id'         => $data['id'],
            'username'   => $data['username'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'country_id' => $data['country_id'],
            'city_id'    => $data['city_id'],
            'guide'      => $data["guide"]
        );
        $this->userSession->saveUser($pdo);
    }


    private function setLogin($username, $password)
    {
        $data     = $this->getData($username);
        $dataUser = $this->checkUser($username, $password);

        if ($data and $dataUser) {
            $this->saveData($data);
            return true;
        }

        return false;
    }

    public function loginUser($username, $password)
    {

        if ($this->setLogin($username, hash('sha1', $password))) {

            return true;
        }

        return false;
    }

    public function checkCodeRecovering($code)
    {
        return $this->pdoQuery->fetch("SELECT * FROM users WHERE code = :code AND recovering = 1 AND status <> 3", array(
            ':code' => $code
        ));
    }

    public function checkCodeEmail($code)
    {
        // var_dump($code);
        return $this->pdoQuery->fetch("SELECT * FROM users WHERE code_email = $code AND validation_email = 0 AND `status` <> 3");
    }

    public function searchName($name, $limit = false, $condition = "")
    {
        $limit_t = ($limit)? "LIMIT $limit"  :"";
        return $this->pdoQuery->fetchAll("SELECT id, username, photo FROM users u WHERE u.username LIKE '%$name%' AND u.status <> 3 $condition ORDER BY id DESC $limit_t");
    }

    public function getAppToken($id)
    {
        return $this->pdoQuery->fetch("SELECT appToken FROM users WHERE id = $id AND users.status <> 3");
    }
}
