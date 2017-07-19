<?php
class User{
    public $name;
    public $login;
    public $password;

    public function __construct($name,$login,$password){
        $this->name = $name;
        $this->login = $login;
        $this->password = $password;
    }

    public function __destruct(){
        echo '<br>'.$this->login . ' был удален';
    }

    public function shoeInfo(){
        echo 'Name: ' . $this->name . ' Login: ' . $this->login . ' Password: ' . $this->password . '<br>';
    }
}

$user1 = new User('Вася','Vasya','12345');
$user1->shoeInfo();
$user2 = new User('Петя','Petya','root');
$user2->shoeInfo();
$user3 = new User('Ivan','Ivanich','1507');
$user3->shoeInfo();
?>