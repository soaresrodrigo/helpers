<?php

    /**
    * Name:		Check
    * Type:		Help
    * Function:	Facilitar especificas entradas e saídas de dados
    *
    * @author      Rodrigo Soares <rodrigo.s.ferreira96@gmail.com>
    * @version     1.0
    * @see         http://www.rodrigosoares.esy.es/
    */

    class Check {

        /** Retorna a string criptografada */
        public static function CreateHash($data){
            $options = [
                'cost' => 12
            ];
            return base64_encode(password_hash($data, PASSWORD_BCRYPT, $options));
        }//endFunction

        /** Vefifica se a criptografia é válida */
        public static function validHash($data, $hash){
            if( base64_encode(base64_decode($hash, true)) !== $hash){
                return false;
            }elseif(password_verify($data, base64_decode($hash)) === false){
                return false;
            }else{
                return true;
            }
        }//endFunction

        

    }