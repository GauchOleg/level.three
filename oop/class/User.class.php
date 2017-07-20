<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20.07.2017
 * Time: 14:18
 */
class User extends AUser{
    public $name;
    public $login;
    public $password;
    public static $count = 0;

    public function __construct($name,$login,$password){
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
        ++self::$count;
    }

    public function __clone(){
        $this->name = 'Guest';
        $this->login = 'Login';
        $this->password = 'password';
        --self::$count;
    }

    public function __destruct(){
        echo '<br>'.$this->login . ' был удален';
    }

    public function showInfo(){
        echo 'Name: ' . $this->name . '<br>' . ' Login: ' . $this->login . '<br>' . ' Password: ' . $this->password . '<br>';
    }
}