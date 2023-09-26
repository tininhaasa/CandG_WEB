<?php

/**
*
* Classe que converte uma data para o formato do banco de dados.
*
* @author Cristina Stanck
*
**/
class AccessToken
{

    public function get()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api-m.sandbox.paypal.com/v1/oauth2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_USERPWD, 'ATulDdebIFlLS--4ifPwKVFtbY5vcvfxuwydvRRXRqmigOgGDaDHNJR3weOB6xvEqOHbxMLBDopY8M30' . ':' . 'EOlGpZD-6V4ngE0lz1IFjWHd_CFELqv_yC4_4g3_d298no6PHhLPZblmllydBqjHo0GdZAQZ7kIktWsV');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Accept-Language: pt_BR';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $result = false;
        }else{
            
            $result = json_decode($result);
            $result = $result->access_token;
        }
        curl_close($ch);

        return $result;
    }

}