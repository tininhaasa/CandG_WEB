<?php

/**
*
* Classe que converte a senha de acordo com o armazenamento no banco de dados
*
* @author Cristina Stanck
*
**/
class PhoneHelper
{

	public function fixed($phone){
      
        // Pass phone number in preg_match function
        if(preg_match(
            '/^\+[0-9]([0-9]{3})([0-9]{2})([0-9]{8})$/', 
        $phone, $value)) {
          
            // Store value in format variable
            $format = '+' . $value[1] . ' (' . 
                $value[2] . ') ' . $value[3];
        }
        else {
             
            // If given number is invalid
            return $phone;
        }
          
        // Print the given format
        return $format;
    }

}