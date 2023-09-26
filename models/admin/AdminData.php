<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
 **/

class AdminData
{

    private $pdoQuery;
    private $pdoCrud;

    public function __construct()
    {

        $this->pdoQuery = new PDOQuery;
        $this->pdoCrud = new PDOCrud;
    }

    // Buscando nome de usuário no banco
    public function checkLoginAdm($login)
    {

        return $this->pdoQuery->fetch("SELECT * FROM admin WHERE login = :login AND (status IS NULL OR status = 1)", array(
            ':login' => $login,
        ));
    }
    // Buscando email do admin no banco
    public function checkEmailAdm($email)
    {

        return $this->pdoQuery->fetch("SELECT id FROM admin WHERE email = :email ", array(
            ':email' => $email,
        ));
    }
    // Buscando nome de usuário no banco
    public function checkIdAdm($email)
    {
        $id = $this->pdoQuery->fetch("SELECT id,email FROM admin WHERE email = :email ", array(
            ':email' => $email,
        ));
        return $id;
    }
    // Buscando nome de usuário no banco
    public function checkRecoverValidateAdm($id, $code)
    {

        $id = $this->pdoQuery->fetch("SELECT id,email,recoverpass FROM admin WHERE id = :id AND recoverpass = :recoverpasscode", array(
            ':id' => $id,
            ':recoverpasscode' => $code,
        ));
        return $id;
    }

    // Buscando a senha do usuário no banco
    public function checkPasswordAdm($username, $password)
    {

        $password = SHA1($password);

        return $this->pdoQuery->fetch("SELECT * FROM admin WHERE login = :username AND password = :password ", array(
            ':username' => $username,
            ':password' => $password,
        ));
    }

    // Buscando os dados da sessão
    public function getData($username)
    {

        return $this->pdoQuery->fetch("SELECT * FROM admin WHERE login = :username ", array(
            ':username' => $username,
        ));
    }

    public function getDataById($id)
    {
        return $this->pdoQuery->fetch("SELECT * FROM admin WHERE id = :id ", array(
            ':id' => $id,
        ));
    }

    public function getAdminByMenu($id)
    {
        return $this->pdoQuery->fetch("SELECT id FROM menu_admin WHERE admin_id = :id", array(
            ':id'   =>  $id,
        ));
    }


    public function getCollaborators($limit = false, $page = false, $getmenus = null)
    {
        $inner = $getmenus == 1 ? 'INNER JOIN menu_admin ma ON ma.admin_id = a.id AND ma.menu_complaint = 1' : '';
        if (!$limit && !$page) {
            $pagination = "";
        } else {
            $pagination = "LIMIT $page, $limit";
        }

        $sql = $this->pdoQuery->fetchAll("SELECT a.*, m.menu_advertiser, m.menu_inn, m.menu_rent, m.menu_fish_pay, m.menu_stores, m.menu_transfer, m.menu_all_inclusive, m.menu_reserves, m.menu_fishes, m.menu_fisheds, m.menu_game, m.menu_collaborator, m.menu_plans, m.menu_complaint, m.menu_config, m.menu_property_complaint, m.menu_catchfish_complaint,m.menu_financial, m.menu_guides, m.menu_config_points, m.menu_config_trophies, m.menu_publishing, m.menu_transferences FROM admin a
        LEFT OUTER JOIN menu_admin m ON m.admin_id = a.id
        $inner
        ORDER BY a.id
        ASC $pagination");

        return $sql;
    }

    public function getCollaborator($id)
    {

        $sql = $this->pdoQuery->fetch("SELECT admin.*, m.menu_advertiser, m.menu_inn, m.menu_rent, m.menu_fish_pay, m.menu_stores, m.menu_transfer, m.menu_all_inclusive, m.menu_reserves, m.menu_fishes, m.menu_fisheds, m.menu_game, m.menu_collaborator, m.menu_plans, m.menu_config, m.menu_property_complaint, m.menu_catchfish_complaint, m.menu_financial, m.menu_guides, m.menu_config_points, m.menu_config_trophies, m.menu_publishing, m.menu_transferences FROM admin 
        LEFT OUTER JOIN menu_admin m ON m.admin_id = :id
        WHERE admin.id = :id", array(
            ':id' => $id,
        ));

        return $sql;
    }
}
