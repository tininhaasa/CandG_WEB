<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
 **/

class AdminCrud
{

    private $pdoQuery;
    private $pdoCrud;

    public function __construct()
    {

        $this->pdoQuery = new PDOQuery;
        $this->pdoCrud = new PDOCrud;
    }

    public function submitAdmin($data)
    {
        if (isset($data['id'])) {
            return $this->updateAdmin($data);
        } else {
            return $this->registerAdmin($data);
        }
    }

    public function registerAdmin($data)
    {
        $pdo = array(
            ':nome'     => $data['nome'],
            ':login'    => $data['login'],
            ':password' => $data['password'],
        );

        $columns = 'nome, login, password';
        $values = ':nome, :login, :password';

        return $this->pdoCrud->insert('admin', $columns, $values, $pdo);
    }

    public function updateAdmin($data)
    {
        $pdo = array(
            ':id'       => $data['id'],
            ':nome'     => $data['nome'],
            ':login'    => $data['login'],
            ':password' => $data['password'],
        );

        $clausule = ' WHERE id = :id';
        $values = 'nome = :nome, login = :login, password = :password';

        return $this->pdoCrud->update('admin', $values, $clausule, $pdo);
    }

    public function RecoverCodeUpdate($id, $code)
    {
        $pdo = array(
            ':id'     => $id,
            ':recoverpasscode' => $code
        );

        $values   = "recoverpass = :recoverpasscode";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("admin", $values, $clausule, $pdo);
    }

    public function updatePassword($id, $password)
    {

        $pdo = array(
            ':id'       => $id,
            ':password' => SHA1($password)
        );

        $values   = "password = :password";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("admin", $values, $clausule, $pdo);
    }

    public function saveCollaborator($dataPost)
    {
        $pdo = array(
            ':name'           => $dataPost['name'],
            ':loginName'      => $dataPost['login'],
            ':email'          => $dataPost['email'],
        );

        if ($dataPost['password'] != '') {
            $pdo[':passwordValue'] = sha1($dataPost['password']);
        }


        $columnsAdmin = 'name, login, password, email';
        $valuesAdmin  = ':name, :loginName, :passwordValue, :email';

        $update = false;
        $adminData = new AdminData;

        if ($dataPost['id'] != "") {
            $adminId = $adminData->getDataById($dataPost['id']);

            if ($adminId) {
                $pdo[':id'] = $dataPost['id'];
                $update     = true;
            }
        }

        if ($update) {
            if ($dataPost['password'] != '') {
                $values = 'name = :name, login = :loginName, password = :passwordValue, email = :email';
            } else {
                $values = 'name = :name, login = :loginName, email = :email';
            }
            $clausule = 'WHERE id = :id';

            $admin = $this->pdoCrud->update('admin', $values, $clausule, $pdo);
            if ($admin) {
                $admin = $pdo[':id'];
            }
        } else {
            $admin = $this->pdoCrud->insert('admin', $columnsAdmin, $valuesAdmin, $pdo);
        }

        if ($admin) {
            $pdoMenus = array(
                ':admin_id'               => $admin,
                ':menuAdvNumber'          => $dataPost['menu_advertiser'],
                ':menuInnNumber'          => $dataPost['menu_inn'],
                ':menuRentNumber'         => $dataPost['menu_rent'],
                ':menuFishPayNumber'      => $dataPost['menu_fish_pay'],
                ':menuStoresNumber'       => $dataPost['menu_stores'],
                ':menuTransferNumber'     => $dataPost['menu_transfer'],
                ':menuAllInclusiveNumber' => $dataPost['menu_all_inclusive'],
                ':menuReservesNumber'     => $dataPost['menu_reserves'],
                ':menuFishesNumber'       => $dataPost['menu_fishes'],
                ':menuFishedsNumber'      => $dataPost['menu_fisheds'],
                ':menuGameNumber'         => 0,
                ':menuCollaboratorNumber' => $dataPost['menu_collaborator'],
                ':menuPlansNumber'        => $dataPost['menu_plans'],
                ':menuConfigNumber'       => $dataPost['menu_config'],
                ':menuConfigPointsNumber' => $dataPost['menu_config_points'],
                ':menuCatchfishComplaint' => $dataPost['menu_catchfish_complaint'],
                ':menuPropertyComplaint'  => $dataPost['menu_property_complaint'],
                ':menuConfigTrophies'     => $dataPost['menu_config_trophies'],
                ':menu_publishingNumber'  => $dataPost['menu_publishing'],
                ':menu_guides_number'     => $dataPost['menu_guides']
            );

            $columnsMenus = 'admin_id, menu_advertiser, menu_inn, menu_rent, menu_fish_pay, menu_stores, menu_transfer, menu_all_inclusive, menu_reserves, menu_fishes, menu_fisheds, menu_game, menu_collaborator, menu_plans, menu_config, menu_config_points, menu_catchfish_complaint,menu_property_complaint, menu_config_trophies, menu_publishing, menu_guides';

            $valuesMenus = ':admin_id, :menuAdvNumber, :menuInnNumber, :menuRentNumber, :menuFishPayNumber, :menuStoresNumber, :menuTransferNumber, :menuAllInclusiveNumber, :menuReservesNumber, :menuFishesNumber, :menuFishedsNumber, :menuGameNumber, :menuCollaboratorNumber, :menuPlansNumber, :menuConfigNumber, :menuConfigPointsNumber,:menuCatchfishComplaint,:menuPropertyComplaint, :menuConfigTrophies, :menu_publishingNumber, :menu_guides_number';

            $updateMenu = false;
            $menuAdmin = $adminData->getAdminByMenu($admin);

            if ($menuAdmin) {
                $updateMenu = true;
            }

            if ($updateMenu) {
                $valuesMenu = 'menu_advertiser = :menuAdvNumber, menu_inn = :menuInnNumber, menu_rent = :menuRentNumber, menu_fish_pay = :menuFishPayNumber, menu_stores = :menuStoresNumber, menu_transfer = :menuTransferNumber, menu_all_inclusive = :menuAllInclusiveNumber, menu_reserves = :menuReservesNumber, menu_fishes = :menuFishesNumber, menu_fisheds = :menuFishedsNumber, menu_game = :menuGameNumber, menu_collaborator = :menuCollaboratorNumber, menu_plans = :menuPlansNumber, menu_config = :menuConfigNumber, menu_config_points = :menuConfigPointsNumber, menu_catchfish_complaint = :menuCatchfishComplaint,  menu_property_complaint = :menuPropertyComplaint, menu_config_trophies = :menuConfigTrophies, menu_publishing = :menu_publishingNumber, menu_guides = :menu_guides_number';
                $clausuleMenu = 'WHERE admin_id = :admin_id';

                $menuAdmin = $this->pdoCrud->update('menu_admin', $valuesMenu, $clausuleMenu, $pdoMenus);
            } else {
                $menuAdmin = $this->pdoCrud->insert('menu_admin', $columnsMenus, $valuesMenus, $pdoMenus);
            }
        }

        return $admin;
    }

    public function changeStatus($id, $status)
    {
        $pdo = array(
            ':id'       => $id,
            ':status'   => $status,
        );

        $values = 'status = :status';
        $clausule = 'WHERE id = :id';

        $sql = $this->pdoCrud->update('admin', $values, $clausule, $pdo);

        return $sql;
    }
}
