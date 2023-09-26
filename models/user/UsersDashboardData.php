<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
 **/

class UsersDashboardData
{

    private $pdoQuery;
    private $userSession;

    public function __construct()
    {

        $this->pdoQuery = new PDOQuery;
        $this->userSession = new UserSession;
    }

    public function AllUsers($page, $limit, $name = "", $init_date = "", $end_date = "")
    {
        $pagination = "";
        $where = "";

        if ($page !== false && $limit != false) {
            $pagination = "LIMIT $page, $limit";
        }

        if ($init_date != "" && $end_date != "") {
            $inner = "INNER JOIN user_action ua ON ua.users_id = u.id AND ua.action_name = :action_n AND ua.date_cadastre BETWEEN '$init_date 00:00:00' AND '$end_date 23:59:59'";
        } else {
            $inner = "LEFT JOIN user_action ua ON ua.users_id = u.id AND ua.action_name = :action_n";
        }

        if ($name != "") {

            $where .= " AND u.username LIKE '%$name%'";
        }

        $sql = $this->pdoQuery->fetchAll("SELECT u.*, ua.action_name, ua.date_cadastre, IF(u.guide = 1, 'Guia', IF(
            (SELECT count(i.id) as count FROM inn i WHERE i.users_id = u.id) > 1 OR 
            (SELECT count(iof.id) as count FROM inn_oficial iof WHERE iof.users_id = u.id) > 1 OR 
            (SELECT count(fp.id) as count FROM fish_pay fp WHERE fp.users_id = u.id) > 1 OR 
            (SELECT count(fpo.id) as count FROM fish_pay_oficial fpo WHERE fpo.users_id = u.id) > 1 OR
            (SELECT count(s.id) as count FROM stores s WHERE s.users_id = u.id) > 1 OR 
            (SELECT count(so.id) as count FROM stores_oficial so WHERE so.users_id = u.id) > 1 OR
            (SELECT count(r.id) as count FROM rent r WHERE r.users_id = u.id) > 1 OR 
            (SELECT count(ro.id) as count FROM rent_oficial ro WHERE ro.users_id = u.id) > 1 OR
            (SELECT count(t.id) as count FROM transfer t WHERE t.users_id = u.id) > 1 OR 
            (SELECT count(tof.id) as count FROM transfer_oficial tof WHERE tof.users_id = u.id) > 1, 'Anunciante', 
            IF((SELECT count(cf.id) as count FROM catch_fish cf WHERE cf.users_id = u.id) > 1, 'Pescador', 
                IF((SELECT count(bs.id) as counter FROM bedroom_reservations bs WHERE bs.users_id = u.id) > 1, 'Hóspede', 'Usuário Padrão')))) as typeuser FROM users u
        $inner
        WHERE u.status <> 3 $where
		
        ORDER BY u.id DESC  $pagination", array(':action_n' => 'Cadastro'));



        return $sql;
    }

    public function AllUsersExport($page, $limit, $name = "", $init_date = "", $end_date = "")
    {
        $pagination = "";
        $where = "";

        if ($page !== false && $limit != false) {
            $pagination = "LIMIT $page, $limit";
        }

        if ($init_date != "" && $end_date != "") {
            $inner = "INNER JOIN user_action ua ON ua.users_id = u.id AND ua.action_name = :action_n AND ua.date_cadastre BETWEEN '$init_date 00:00:00' AND '$end_date 23:59:59'";
        } else {
            $inner = "LEFT JOIN user_action ua ON ua.users_id = u.id AND ua.action_name = :action_n";
        }

        if ($name != "") {

            $where .= " AND u.username LIKE '%$name%'";
        }

        $sql = $this->pdoQuery->fetchAll("SELECT u.*, ua.action_name, ua.date_cadastre,coun.name as country,  st.iso2 as uf, c.name as city, IF(u.guide = 1, 'Guia', IF(
            (SELECT count(i.id) as count FROM inn i WHERE i.users_id = u.id) > 1 OR 
            (SELECT count(iof.id) as count FROM inn_oficial iof WHERE iof.users_id = u.id) > 1 OR 
            (SELECT count(fp.id) as count FROM fish_pay fp WHERE fp.users_id = u.id) > 1 OR 
            (SELECT count(fpo.id) as count FROM fish_pay_oficial fpo WHERE fpo.users_id = u.id) > 1 OR
            (SELECT count(s.id) as count FROM stores s WHERE s.users_id = u.id) > 1 OR 
            (SELECT count(so.id) as count FROM stores_oficial so WHERE so.users_id = u.id) > 1 OR
            (SELECT count(r.id) as count FROM rent r WHERE r.users_id = u.id) > 1 OR 
            (SELECT count(ro.id) as count FROM rent_oficial ro WHERE ro.users_id = u.id) > 1 OR
            (SELECT count(t.id) as count FROM transfer t WHERE t.users_id = u.id) > 1 OR 
            (SELECT count(tof.id) as count FROM transfer_oficial tof WHERE tof.users_id = u.id) > 1, 'Anunciante', 
            IF((SELECT count(cf.id) as count FROM catch_fish cf WHERE cf.users_id = u.id) > 1, 'Pescador', 
                IF((SELECT count(bs.id) as counter FROM bedroom_reservations bs WHERE bs.users_id = u.id) > 1, 'Hóspede', 'Usuário Padrão')))) as typeuser FROM users u
        $inner
        left outer join cities c        on c.id  = u.city_id
        left outer join countries coun  on coun.id  = u.country_id
        left outer join states st       on st.id = c.state_id
        WHERE u.status <> 3 $where
		
        ORDER BY u.id DESC  $pagination", array(':action_n' => 'Cadastro'));

        foreach ($sql as $key => $value) {
            $id = $value['id'];

            $sql[$key]['inn'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM inn i 
        LEFT JOIN images_inn ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.inn_oficial_id IS NULL");

            array_merge($sql[$key]['inn'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM inn_oficial i
        LEFT JOIN images_inn_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));


            $sql[$key]['rent'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM rent i 
        LEFT JOIN images_rent ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.rent_oficial_id IS NULL");

            array_merge($sql[$key]['rent'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM rent_oficial i
        LEFT JOIN images_rent_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

            $sql[$key]['transfer'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM transfer i 
        LEFT JOIN transfer_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.transfer_ofc_id IS NULL");

            array_merge($sql[$key]['transfer'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM transfer_oficial i
        LEFT JOIN transfer_oficial_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

            $sql[$key]['fish_pay'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM fish_pay i 
        LEFT JOIN fish_pay_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.fish_pay_oficial_id IS NULL");

            array_merge($sql[$key]['fish_pay'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM fish_pay_oficial i
        LEFT JOIN fish_pay_images_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

            $sql[$key]['stores'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM stores i 
        LEFT JOIN stores_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.store_ofc_id IS NULL");

            array_merge($sql[$key]['stores'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM stores_oficial i
        LEFT JOIN stores_oficial_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));
        }



        return $sql;
    }

    public function CountAllUsers($name = "", $init_date = "", $end_date = "")
    {
        $inner = "";
        $where = "";

        $pdo = array(
            ':android' => 'android',
            ':ios' => 'ios',
        );
        if ($init_date != "" && $end_date != "") {
            $inner = "INNER JOIN user_action ua ON ua.users_id = u.id AND ua.action_name = :action_n AND ua.date_cadastre BETWEEN '$init_date' AND '$end_date'";
            $pdo[':action_n'] = "Cadastro";
        } else {
            $inner = "";
        }

        if ($name != "") {

            $where .= " AND u.username LIKE '%$name%'";
        }
        $sql = $this->pdoQuery->fetch(
            "SELECT count(u.id) as counter, count(u.appToken) as appQtd, (SELECT count(u2.id) FROM users u2 WHERE u2.deviceType = :android) as android, (SELECT count(u2.id) FROM users u2 WHERE u2.deviceType = :ios) as ios FROM users u $inner WHERE u.status <> 2 $where",
            $pdo
        );

        return $sql;
    }

    public function getUser($id)
    {
        $sql = $this->pdoQuery->fetch("SELECT u.*, IF(u.guide = 1, 'Guia', IF(
            (SELECT count(i.id) as count FROM inn i WHERE i.users_id = u.id) > 1 OR 
            (SELECT count(iof.id) as count FROM inn_oficial iof WHERE iof.users_id = u.id) > 1 OR 
            (SELECT count(fp.id) as count FROM fish_pay fp WHERE fp.users_id = u.id) > 1 OR 
            (SELECT count(fpo.id) as count FROM fish_pay_oficial fpo WHERE fpo.users_id = u.id) > 1 OR
            (SELECT count(s.id) as count FROM stores s WHERE s.users_id = u.id) > 1 OR 
            (SELECT count(so.id) as count FROM stores_oficial so WHERE so.users_id = u.id) > 1 OR
            (SELECT count(r.id) as count FROM rent r WHERE r.users_id = u.id) > 1 OR 
            (SELECT count(ro.id) as count FROM rent_oficial ro WHERE ro.users_id = u.id) > 1 OR
            (SELECT count(t.id) as count FROM transfer t WHERE t.users_id = u.id) > 1 OR 
            (SELECT count(tof.id) as count FROM transfer_oficial tof WHERE tof.users_id = u.id) > 1, 'Anunciante', 
            IF((SELECT count(cf.id) as count FROM catch_fish cf WHERE cf.users_id = u.id) > 1, 'Pescador', 
                IF((SELECT count(bs.id) as counter FROM bedroom_reservations bs WHERE bs.users_id = u.id) > 1, 'Hóspede', 'Usuário Padrão')))) as typeuser FROM users u
        
        WHERE u.id = $id");

        $sql['inn'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM inn i 
        LEFT JOIN images_inn ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.inn_oficial_id IS NULL");

        array_merge($sql['inn'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM inn_oficial i
        LEFT JOIN images_inn_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));


        $sql['rent'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM rent i 
        LEFT JOIN images_rent ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.rent_oficial_id IS NULL");

        array_merge($sql['rent'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM rent_oficial i
        LEFT JOIN images_rent_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

        $sql['transfer'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM transfer i 
        LEFT JOIN transfer_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.transfer_ofc_id IS NULL");

        array_merge($sql['transfer'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM transfer_oficial i
        LEFT JOIN transfer_oficial_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

        $sql['fish_pay'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM fish_pay i 
        LEFT JOIN fish_pay_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.fish_pay_oficial_id IS NULL");

        array_merge($sql['fish_pay'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM fish_pay_oficial i
        LEFT JOIN fish_pay_images_oficial ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

        $sql['stores'] = $this->pdoQuery->fetchAll("SELECT i.*, i.id as photo_id,ii.image FROM stores i 
        LEFT JOIN stores_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id AND i.store_ofc_id IS NULL");

        array_merge($sql['stores'], $this->pdoQuery->fetchAll("SELECT i.*,ii.image FROM stores_oficial i
        LEFT JOIN stores_oficial_images ii ON ii.property_id = i.id AND ii.banner = 1
        WHERE i.users_id = $id"));

        return $sql;
    }
}
