<?php


class StatusCount
{

    private $pdoQuery;
    private $pdoCrud;
    private $userSession;
    private $admSession;

    public function __construct()
    {
        $this->pdoQuery = new PDOQuery;
        $this->pdoCrud = new PDOCrud;
        $this->userSession = new UserSession;
        $this->admSession = new AdmSession;
    }

    public function totalQuantity()
    {
        $id_admin = $this->admSession->get('id');
        $adminAccess = $this->getMenuAccess($id_admin);

        if ($adminAccess["menu_property_complaint"] != 0) {
            $sql["property_complaint_qtd"] = intval($this->pdoQuery->fetch("SELECT COUNT(c.id) as count FROM complaint_property c WHERE c.solved_date IS NULL AND c.status = 0")['count']);
        } else {
            $sql["property_complaint_qtd"] = 0;
        }

        if ($adminAccess['menu_inn'] != 0) {
            $sql['inn_qtd'] = intval($this->pdoQuery->fetch("SELECT COUNT(i.id) as inn_qtd FROM inn i 
            INNER JOIN property_purchase pp ON pp.property_id = i.id AND (pp.type = 1 OR pp.type = 2)
            WHERE i.status = 0")['inn_qtd']);
        } else {
            $sql['inn_qtd'] = 0;
        }

        if ($adminAccess['menu_rent'] != 0) {
            $sql['rent_qtd'] = intval($this->pdoQuery->fetch("SELECT COUNT(r.id) as rent_qtd FROM rent r
            INNER JOIN property_purchase pp ON pp.property_id = r.id AND pp.type = 3
            WHERE r.status = 0")['rent_qtd']);
        } else {
            $sql['rent_qtd'] = 0;
        }

        if ($adminAccess['menu_stores'] != 0) {
            $sql['stores_qtd'] = intval($this->pdoQuery->fetch("SELECT COUNT(s.id) as stores_qtd FROM stores s 
            INNER JOIN property_purchase pp ON pp.property_id = s.id AND pp.type = 5
            WHERE s.status = 0")['stores_qtd']);
        } else {
            $sql['stores_qtd'] = 0;
        }

        if ($adminAccess['menu_fish_pay'] != 0) {
            $sql['fishpay_qtd'] = intval($this->pdoQuery->fetch("SELECT COUNT(fp.id) as fishpay_qtd FROM fish_pay fp
            INNER JOIN property_purchase pp ON pp.property_id = fp.id AND pp.type = 4
             WHERE fp.status = 0")['fishpay_qtd']);
        } else {
            $sql['fishpay_qtd'] = 0;
        }

        if ($adminAccess['menu_transfer'] != 0) {
            $sql['transfer_qtd'] = intval($this->pdoQuery->fetch("SELECT COUNT(t.id) as transfer_qtd FROM transfer t
            INNER JOIN property_purchase pp ON pp.property_id = t.id AND pp.type = 6
            WHERE t.status = 0")['transfer_qtd']);
        } else {
            $sql['transfer_qtd'] = 0;
        }

        if($adminAccess['menu_guides'] != 0){
            $sql['total_guides'] = intval($this->pdoQuery->fetch("SELECT COUNT(u.id) as id FROM users u WHERE u.status <> 3 AND u.guide = 1 AND u.guide_status = 0")["id"]);
        }else{
            $sql['total_guides'] = 0;
        }

        $sql['totalvalue'] = $sql["property_complaint_qtd"] + $sql['inn_qtd'] + $sql['rent_qtd'] + $sql['stores_qtd'] + $sql['transfer_qtd'] + $sql['fishpay_qtd'] + $sql['allinclusive_qtd'] + $sql["total_guides"];

        return $sql;
    }

    public function getReproveds($id_user)
    {
        $sql['inn_rep']      = $this->pdoQuery->fetch("SELECT i.id as inn_rep, i.type as inn_type  FROM inn i      WHERE i.status = 2 and i.users_id = :id_user", array(
            ":id_user" => $id_user
        ));
        $sql['rent_rep']     = $this->pdoQuery->fetch("SELECT r.id as rent_rep     FROM rent r     WHERE r.status = 2 and r.users_id = :id_user", array(
            ":id_user" => $id_user
        ));
        $sql['store_rep']    = $this->pdoQuery->fetch("SELECT s.id as store_rep    FROM stores s   WHERE s.status = 2 and s.users_id = :id_user", array(
            ":id_user" => $id_user
        ));
        $sql['fish_pay_rep'] = $this->pdoQuery->fetch("SELECT f.id as fish_pay_rep FROM fish_pay f WHERE f.status = 2 and f.users_id = :id_user", array(
            ":id_user" => $id_user
        ));
        $sql['transfer_rep'] = $this->pdoQuery->fetch("SELECT t.id as transfer_rep FROM transfer t WHERE t.status = 2 and t.users_id = :id_user", array(
            ":id_user" => $id_user
        ));

        return $sql;
    }

    public function getMenuAccess($id_admin)
    {
        $sql = $this->pdoQuery->fetch("SELECT admin.id, ma.* FROM admin
        LEFT OUTER JOIN menu_admin ma ON ma.admin_id = :id_admin
        WHERE admin.id = :id_admin", array(
            ":id_admin" => $id_admin
        ));

        return $sql;
    }

    public function getCaughtFishes()
    {
        $id_admin = $this->admSession->get('id');
        $adminAccess = $this->getMenuAccess($id_admin);

        if ($adminAccess['menu_fisheds'] != 0) {
            $sql = intval($this->pdoQuery->fetch("SELECT COUNT(cf.id) as cf_qtd FROM catch_fish cf WHERE cf.status = 0")['cf_qtd']);
        } else {
            $sql = 0;
        }

        return $sql;
    }

    public function getCaughtFishesComplaint()
    {
        $id_admin = $this->admSession->get('id');
        $adminAccess = $this->getMenuAccess($id_admin);
        if ($adminAccess['menu_catchfish_complaint'] != 0) {
            $sql = count($this->pdoQuery->fetchAll("SELECT COUNT(cf.id) as qtd FROM catch_fish cf,complaint c WHERE c.catch_fish_id = cf.id AND cf.solved_date is null
            GROUP BY cf.id
            ORDER BY c.complaint_date DESC"));
        } else {
            $sql = 0;
        }

        return $sql;
    }

    public function GetNewMessages()
    {

        $result = $this->pdoQuery->fetch("SELECT count(id) as new_messages FROM notifications
        WHERE  (notifications.idUser = :id OR notifications.idProfile = :id )
        AND notifications.visibility_number = 0 
        AND notifications.id_last_to_change <> :id
        GROUP BY notifications.idProfile", array(
            ':id' => $this->userSession->get('id')
        ));

        if($result){
            return $result['new_messages'];
        }else{
            return 0;
        }

    }
}
