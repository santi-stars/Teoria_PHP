<?php

require_once '..\models\users_model.php';
require_once '..\models\conexion\conexion.php';

class UserController
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }

    public function get_user_by_id($user_id)
    {
        return UsersModel::get_user_by_id($user_id);
    }

    public function register($usernick, $email, $md5password)
    {
        // se reciben los datos que el usuario ha introducido en la vista y se envían al models para que registre al usuario
        // en la base de datos.
        // Si el registro se realiza correctamente, se enviará un email al usuario
        if (UsersModel::register($usernick, $email, $md5password)) {
            $this->sendEmail($usernick, $email);
            return true;
        } else {
            return false;
        }
    }

    public function sendEmail($username, $email)
    {
        // se envía un email al usuario
        $senderName = "Awesome Application";
        $senderEmail = "register@awesomeapplication.com";
        $recipientEmail = $email;
        $recipientUsername = $username;
        $subject = "Registration succesful";
        $body = "Hi $recipientUsername,\n
        the registration process in $senderName was succesful.\n
        Thanks for your support! Greetings.\n
        The Team of $senderName";

        $headers = "To: $username <$email> \r\n";
        $headers .= "From: $senderName <$senderEmail> \r\n";
        $headers .= "Return-Path: <$senderEmail> \r\n";
        $headers .= "Reply to: <$senderEmail> \r\n";
        $headers .= "MIME-Version: 1.0 \r\n";
        $headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";


        // dado que el envío de mail sólo funciona si se configuran los datos en el servidor, se ha comentado la llamada al método mail()
        // y se ha seteado la variable $success como false

        // $success = mail($recipientEmail, $subject, $body, $headers);

        $success = false;

        if ($success) {
            $message = "A confirmation email has been sended!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        } else {
            $message = "The registration email could not be sended!";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    }

    public function validation($input, $params)
    {
        // el método validation valida cada campo de forma independiente en función del tipo de error a validar
        $error = "";
        for ($i = 0; $i < count($params); $i++) {
            switch ($params[$i]) {
                case 'required':
                    if (!$this->validationRequired($input)) {
                        $error = "El campo no puede estar vacío";
                    }
                    break; //por desgracia, continue 2 no funciona correctamente

                case 'min':
                    if (!$this->validationMin($input)) {
                        $error = "Demasiado corto";
                    }
                    break;

                case 'max':
                    if (!$this->validationMax($input)) {
                        $error = "Supera la longitud máxima permitida";
                    }
                    break;

                case 'email':
                    if (!filter_var($_POST[$input], FILTER_VALIDATE_EMAIL)) {
                        $error = "Formato de email inválido";
                    }
                    break;

                case 'password':
                    if (!$this->validationPassword($input)) {
                        $error = "Formato de contraseña inválido: debe contener al menos 8 carácteres, mayúsculas, minúsculas y números";
                    }
                    break;

                case 'duplicated':
                    if ($this->inputExists(str_replace("new-", "", $input), $_POST[$input])) {
                        $error = "El " . $input . " ya existe";
                    }
                    break;

                default:

                    break;
            }
        }
        return $error;
    }

    public function validationRequired($input)
    {
        // si el campo está vacío, devuelve un error
        if (empty($_POST[$input])) {
            return false;
        } else {
            return true;
        }
    }

    public function validationMin($input)
    {
        // si el campo no llega al mínimo, devuelve un error
        if (strlen($_POST[$input]) < 4) {
            return false;
        } else {
            return true;
        }
    }

    public function validationMax($input)
    {
        // si el campo se pasa del máximo permitido, devuelve un error
        if (strlen($_POST[$input]) > 20) {
            return false;
        } else {
            return true;
        }
    }

    public function validationPassword($input)
    {
        // la contraseña debe contener al menos una mayúscula, una minúscula y un número.
        // si no cumple estas condiciones, devuelve un error
        $uppercase = preg_match('@[A-Z]@', $_POST[$input]);
        $lowercase = preg_match('@[a-z]@', $_POST[$input]);
        $number = preg_match('@[0-9]@', $_POST[$input]);

        if (!$uppercase || !$lowercase || !$number || strlen($_POST[$input]) < 8 || strlen($_POST[$input]) > 32) {
            return false;
        } else {
            return true;
        }
    }

    public function userPassCheck($usernick, $md5password)
    {
        $num_of_rows = UsersModel::check_user($usernick, $md5password);

        if ($num_of_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function inputExists($input, $value)
    {
        $num_of_rows = UsersModel::inputExists($input, $value);

        if ($num_of_rows->count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_id_by_user_nick($user_nick)
    {
        return UsersModel::get_user_id_by_user_nick($user_nick);
    }

    public function cryptconmd5($password)
    {
        return UsersModel::cryptconmd5($password);
    }
}
