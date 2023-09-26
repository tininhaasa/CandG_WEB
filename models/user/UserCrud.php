<?php

/**
 * 
 * Data do dashboard
 * 
 * @author Emprezaz
 * 
 **/

class UserCrud
{

    private $pdoQuery;
    private $pdoCrud;
    private $userSession;

    public function __construct()
    {
        $this->pdoQuery = new PDOQuery;
        $this->pdoCrud = new PDOCrud;
        $this->userSession = new UserSession;
    }

    public function resetAccountGuide(int $id)
    {

        $pdo = array(
            ':id'                  => $id,
            ':guide_lat'           => null,
            ':guide_long'          => null,
            ':guide_username'      => null,
            ':guide_water'         => null,
            ':guide_whats'         => null,
            ':guide_desc'          => null,
            ':guide_apresentation' => null,
            ':guide'               => "0",
            ':guide_status'        => "0"
        );

        $values   = "guide_lat = :guide_lat, guide_long = :guide_long, guide_username = :guide_username, guide_water = :guide_water, guide_whats = :guide_whats, guide_desc = :guide_desc, guide_apresentation = :guide_apresentation, guide = :guide, guide_status = :guide_status";
        $clausule = "WHERE id = :id";

        $update = $this->pdoCrud->update("users", $values, $clausule, $pdo);

        if($update){
            $this->pdoCrud->deleteMap('guide_inns', 'users_id', $id);
            $this->pdoCrud->deleteMap('guide_services', 'users_id', $id);
        }

        return $update;
    }

    public function updateGuideStatus($id, $status)
    {
        return $this->pdoCrud->update('users', "guide_status = :status", "WHERE id = :id", array(
            ':id'     => $id,
            ':status' => $status
        ));
    }

    public function saveAsaas($id, $idasaas)
    {

        $pdo = array(
            ':idasaas' => $idasaas,
        );

        $columns = 'idasaas=:idasaas';

        return $this->pdoCrud->update('users', $columns, 'WHERE id = :id', array_merge($pdo, array(
            ':id' => $id
        )));
    }

    public function recoverPassword($code, $id)
    {
        $pdo = array(
            ':id'         => $id,
            ':codeNumber' => $code
        );

        $values   = "code = :codeNumber, recovering = 1";
        $clausule = "WHERE id = :id";

        $update = $this->pdoCrud->update("users", $values, $clausule, $pdo);

        if ($update) {
            $eventName = 'recoverPassword' . $id;
            $this->pdoQuery->executeQuery("DROP EVENT IF EXISTS $eventName");
            $this->pdoQuery->executeQuery("CREATE EVENT $eventName ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 1 DAY DO UPDATE users SET recovering = 0 WHERE id = '$id' AND recovering = 1");
        }

        return $update;
    }

    public function updatePassword($id, $password)
    {
        $pdo = array(
            ':id'       => $id,
            ':password' => sha1($password)
        );

        $values   = "password = :password, recovering = 0";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }

    public function activeEmailUser($id, $status = 1)
    {
        $pdo = array(
            ':id'       => $id
        );

        $values   = "validation_email = $status";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }

    public function register($username, $birthday, $phone, $email, $password, $country, $city, $code)
    {
        $pdo = array(
            ':username'     => $username,
            ':birthdaydate' => $birthday,
            ':phone'        => $phone,
            ':email'        => mb_strtolower($email),
            ':password'     => sha1($password),
            ':country_id'   => $country,
            ':city_id'      => $city,
            ':codeNumber'   => $code,
            ':validation_number' => 0,
        );


        $colums = "username, birthday, phone, email, password, country_id, city_id, code_email, validation_email";
        $values = ":username, :birthdaydate, :phone, :email, :password, :country_id, :city_id, :codeNumber, :validation_number";


        $id = (int) $this->pdoCrud->insert('users', $colums, $values, $pdo);


        $eventName = 'validationUser' . $id;
        $this->pdoQuery->executeQuery("DROP EVENT IF EXISTS $eventName");
        $this->pdoQuery->executeQuery("CREATE EVENT $eventName ON SCHEDULE AT CURRENT_TIMESTAMP + INTERVAL 10 DAY DO DELETE FROM users WHERE id = '$id' AND validation_email = 0");

        $now   = new DateTime(date("Y-m-d"));
        $birth = new DateTime($birthday);
        $age   = $now->diff($birth);

        $this->userSession->saveUser(array(
            'id'             => $id,
            'username'       => $username,
            'age'            => $age->y,
            'birthdaydate'   => $birthday,
            'phone'          => $phone,
            'email'          => mb_strtolower($email),
            'country_id'     => $country,
            'city_id'        => $city,
        ));

        return $id;
    }

    public function updateUser(array $data, $validation)
    {
        if(isset($data['guide']) && $data['guide'] != '0'){
            $pdo = array(
                ':id'                  => $data['id'],
                ':guide_lat'           => $data['lat'],
                ':guide_long'          => $data['long'],
                ':guide_username'      => $data['guide_username'],
                ':guide_water'         => $data['guide_water'],
                ':guide_whats'         => $data['code_guide'] . " " . $data['guide_whats'],
                ':guide_desc'          => $data['guide_desc'],
                ':guide_apresentation' => $data['guide_apresentation'],
                ':guide_date_init'     => $data['guide_date_init'],
                ':guide_participation_number'     => $data['guide_participation'],
                ':guide'               => "1"
            );

            $values   = "guide = :guide, guide_lat = :guide_lat , guide_long = :guide_long, guide_username = :guide_username, guide_water = :guide_water, guide_whats = :guide_whats, guide_desc = :guide_desc, guide_apresentation = :guide_apresentation, guide_date_init = :guide_date_init, guide_participation = :guide_participation_number, guide_status = 1";

            $this->pdoCrud->deleteMap('guide_inns', 'users_id', $data['id']);
            if(isset($data['guide_property']) && count($data['guide_property']) > 0 ){
                

                foreach ($data['guide_property'] as $key => $guide_property) {

                    if(intval($guide_property) > 0){
                        $pdo_inn = array(
                            ':property_id' => $guide_property,
                            ':users_id'    => $data['id'],
                        );

                        $columns_inn = "property_id, users_id";
                        $values_inn  = ":property_id, :users_id";
                    } else {
                        $pdo_inn = array(
                            ':inn_title' => $guide_property,
                            ':users_id' => $data['id'],
                        );

                        $columns_inn = "inn_title, users_id";
                        $values_inn  = ":inn_title, :users_id";
                    }

                    $this->pdoCrud->insert('guide_inns', $columns_inn, $values_inn, $pdo_inn);
                }
            }

            $this->pdoCrud->deleteMap('guide_services', 'users_id', $data['id']);
            if(isset($data['guide_services']) && count($data['guide_services']) > 0 ){
                

                foreach ($data['guide_services'] as $key => $guide_service) {
                    $pdo_service = array(
                        ':desc'     => $guide_service,
                        ':users_id' => $data['id'],
                    );

                    $columns_service = "users_id, description";
                    $values_service  = ":users_id, :desc";
                    $this->pdoCrud->insert('guide_services', $columns_service, $values_service, $pdo_service);
                }
            }

            $this->pdoCrud->deleteMap('guide_fishes', 'user_id', $data['id']);
            if(isset($data["guide_specialties"]) && count($data["guide_specialties"]) > 0){


                foreach ($data["guide_specialties"] as $key => $specialties) {
                    
                    $pdo_specialty = array(
                        ':fish_id' => $specialties,
                        ':user_id' => $data['id'],
                    );

                    $columns_specialty = "user_id, fish_id";
                    $values_specialty  = ":user_id, :fish_id";

                    $this->pdoCrud->insert('guide_fishes', $columns_specialty, $values_specialty, $pdo_specialty);
                }
            }

        }else{
            $pdo = array(
                ':id'                    => $data['id'],
                ':username'              => $data['username'],
                ':email'                 => mb_strtolower($data['email']),
                ':country_id'            => $data['country'],
                ':city_id'               => $data['city'],
                ':phone'                 => $data['phone'],
                ':birthdate'             => $data['birthday'],
            );

            $values = "username = :username, country_id = :country_id, city_id = :city_id, phone = :phone, email = :email, birthday = :birthdate";

        }
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }

    public function updateProfilePhoto($image, $id)
    {
        $pdo = array(
            ':photo'         => $image,
            ':id'            => $id,
        );
        $values = "photo = :photo";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }
    public function updateGuidePhoto($image, $id)
    {
        $pdo = array(
            ':photo'        => $image,
            ':users_id'     => $id,
            ':bannernumber' => 0,
        );
        $columns = "image, users_id, banner";
        $values = ":photo, :users_id, :bannernumber";

        return $this->pdoCrud->insert("images_guide", $columns, $values, $pdo);
    }

    public function updateAppToken($id, $token, $devicetype)
    {
        $pdo = array(
            ':id'             => $id,
            ':appToken_value' => $token,
            ':devicetype_value' => $devicetype
        );

        $values = "appToken = :appToken_value, deviceType = :devicetype_value";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }

    public function savePhotos(array $photos, $userId)
    {

        if ($photos['name'][0]) {

            $id = $userId;

            for ($i = 0; $i < count($photos['name']); $i++) {

                $this->photoControl($id, $photos, $i);
            }

            return true;
        }
    }
    public function savePhotosGuide(array $photos, $userId)
    {
        if ($photos['name'][0]) {

            $id = $userId;

            $this->pdoCrud->deleteMap('images_guide', 'users_id', $userId);
            for ($i = 0; $i < count($photos['name']); $i++) {

                $this->photoControlGuide($id, $photos, $i);
            }

            return true;
        }
    }

    public function photoControlGuide($id, $image, $order)
    {
        $imageName = $image['name'][$order];
        $imageType = $image['type'][$order];
        $imageTmp  = $image['tmp_name'][$order];
        @$exitf     = exif_read_data($image['tmp_name'][$order]);

        if ($imageName != "") {

            $image = $this->ImageConfiguration($imageName, $imageType, $imageTmp, 600, 400, $id, $exitf);

            $this->updateGuidePhoto($image, $id);
        }

        return true;
    }

    public function photoControl($id, $image, $order)
    {
        $imageName = $image['name'][$order];
        $imageType = $image['type'][$order];
        $imageTmp  = $image['tmp_name'][$order];
        @$exitf     = exif_read_data($image['tmp_name'][$order]);

        if ($imageName != "") {

            $image = $this->ImageConfiguration($imageName, $imageType, $imageTmp, 600, 400, $id, $exitf);

            $this->updateProfilePhoto($image, $id);
        }

        return true;
    }

    private function ImageConfiguration($name, $type, $temp, $width, $height, $id, $exitf = null)
    {

        if (preg_match("/^image\/(png)$/", $type)) {

            $formatedImage = imagecreatefrompng($temp);
        } else {

            $formatedImage = imagecreatefromjpeg($temp);
        }

        if (!preg_match("/^image\/(png)$/", $type) && !$formatedImage) {

            $formatedImage = imagecreatefrompng($temp);
        } else if (!$formatedImage) {

            $formatedImage = imagecreatefromjpeg($temp);
        }


        if (!$formatedImage) {
            $formatedImage = imagecreatefromstring(file_get_contents($temp));
        }

        $originalWidth = imagesx($formatedImage);

        $originalHeigth = imagesy($formatedImage);

        if ($originalWidth > $width) {

            $newWidth  = $width;
        } else {

            $newWidth = $originalWidth;
        }

        $newHeigth = ($originalHeigth * $newWidth) / $originalWidth;

        if ($originalHeigth > $height) {

            $newHeigth  = $height;
        } else {

            $newHeigth = $originalHeigth;
        }

        $newWidth = ($originalWidth * $newHeigth) / $originalHeigth;

        $newImage = imagecreatetruecolor($newWidth, $newHeigth);

        // preserve transparency
        $transindex = imagecolortransparent($formatedImage);

        if ($transindex >= 0) {

            $transcol = imagecolorsforindex($formatedImage, $transindex);
            $transindex = imagecolorallocatealpha($newImage, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
            imagefill($newImage, 0, 0, $transindex);
            imagecolortransparent($newImage, $transindex);
			
		} else if (preg_match("/^image\/(png)$/", $type)) {

            imagesavealpha($newImage, true);
            $color = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $color);
        }

        imagecopyresampled($newImage, $formatedImage, 0, 0, 0, 0, $newWidth, $newHeigth, $originalWidth, $originalHeigth);

        if (!empty($exitf['Orientation'])) {

            switch ($exitf['Orientation']) {
                case 3:
                    $newImage = imagerotate($newImage, 180, 0);
                    break;
                case 6:
                    $newImage = imagerotate($newImage, -90, 0);
                    break;
                case 8:
                    $newImage = imagerotate($newImage, 90, 0);
                    break;
            }
        }

        return $this->savePhotoFile($name, $newImage, $formatedImage, $type, $id);
    }

    private function savePhotoFile($name, $newImage, $temp, $type, $id)
    {

        $newName = sha1($name);

        if (preg_match("/^image\/(png)$/", $type)) {

            $newName .= '.png';
        } else {

            $newName .= '.jpg';
        }

        if (!file_exists(ROOT . "/assets/img/user")) {

            mkdir(ROOT . "/assets/img/user/", 0755, true);
        }

        if (!file_exists(ROOT . "/assets/img/user/" . $id)) {

            mkdir(ROOT . "/assets/img/user/" . $id, 0755, true);
        }

        if (!file_exists(ROOT . "/assets/img/user/" . $id . "/photos")) {

            mkdir(ROOT . "/assets/img/user/" . $id . "/photos", 0755, true);
        }

        if (preg_match("/^image\/(png)$/", $type)) {

            imagepng($newImage, ROOT . "/assets/img/user/" . $id . "/photos/" . $newName, 9);
        } else {

            imagejpeg($newImage, ROOT . "/assets/img/user/" . $id . "/photos/" . $newName, 99);
        }

        imagedestroy($temp);

        return $newName;
    }

    public function saveCustomerId($iduser, $customerId)
    {
        $pdo = array(
            ":id"         => $iduser,
            ":customerId" => $customerId
        );

        $values   = "customerId = :customerId";
        $clausule = "WHERE id = :id";

        return $this->pdoCrud->update("users", $values, $clausule, $pdo);
    }

    public function fishingGameSub($id,$status)
    {
        $pdo = array(
            ":id"         => $id,
            ":status" => $status
        );

        $values   = "game_participation = :status, game_participation_date = NOW()";
        $clausule = "WHERE id = :id";

        $result = $this->pdoCrud->update("users", $values, $clausule, $pdo);

        return $result;
    }

    public function deleteAccount($id)
    {
        $pdo = array(
            ":id"         => $id,
            ":status"     => 3
        );

        $values   = "status = :status";
        $clausule = "WHERE id = :id";

        $result = $this->pdoCrud->update("users", $values, $clausule, $pdo);
        
        $pdo = array(
            ':users_id'  => $id,
            ':action'   => "Deletou sua conta"
        );   

        $columns = "users_id, action_name, date_cadastre";
        $values = ":users_id, :action, NOW()";
        
        $this->pdoCrud->insert('user_action', $columns, $values, $pdo);

        return $result;
    }

    public function gameParticipation($id, $participation)
    {
        $pdo = array(
            ":id"         => $id,
            ":participationnumber"     => $participation,
        );

        $values   = "game_participation = :participationnumber, game_participation_date = NOW()";
        $clausule = "WHERE id = :id";

        $result = $this->pdoCrud->update("users", $values, $clausule, $pdo);

        $pdo = array(
            ':users_id'  => $id,
            ':action'   => "Aceite FishingGame"
        );   

        $columns = "users_id, action_name, date_cadastre";
        $values = ":users_id, :action, NOW()";
        
        $this->pdoCrud->insert('user_action', $columns, $values, $pdo);

        return $result;
    }

    public function saveAction($id, $action)
    {                 
        $pdo = array(
            ':users_id'  => $id,
            ':action'   => $action
        );   

        $columns = "users_id, action_name, date_cadastre";
        $values = ":users_id, :action, NOW()";
        
        $result = $this->pdoCrud->insert('user_action', $columns, $values, $pdo);

        return $result;

    }
    public function editAction($action)
    {                 
        $pdo = array(
            ':action'   => $action
        );   

        $columns = " action_name = :action";
        
        $result = $this->pdoCrud->update('user_action', $columns, "WHERE 1", $pdo);

        return $result;

    }
}
