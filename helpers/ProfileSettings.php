<?php

/**
 *
 * Classe que converte os caracteres especiais e remove espaços.
 *
 * @author Cristina Stanck
 *
 **/
class ProfileSettings
{
    public function settings($path)
    {
        if ($path == '/assets/img/man.jpg' || $path == '/assets/img/woman.jpg' || $path == '/assets/img/noprofile.jpg') {
            return "$path ');background-position: center; background-size: 100%; background-color: #465a65; background-repeat: no-repeat;";
        } else {
            return $path . "');";
        }
    }

    function getPhoto($profile, $user, $userSession)
    {
        if ($profile['id'] == $userSession->get('id')) {
            $path = $user['userphoto'];
        } else if ($user['id'] == $userSession->get('id')) {
            $path = $profile['profilephoto'];
        }

        return $this->settings($path);
    }

    function getPhotoMessages(array $message, $userSession)
    {
        if ($message['ProfileId'] == $userSession->get('id')) {
            $path = $message['userphoto'];
        } else if ($message['UserId'] == $userSession->get('id')) {
            $path = $message['profilephoto'];
        }
        return $this->settings($path);
    }

    function hasApp($user_id){
        
        $userData = new UserData;
        $apptoken = $userData->getAppToken($user_id);

        return $apptoken['appToken'];
    }

}
