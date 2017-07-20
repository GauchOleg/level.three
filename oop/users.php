<?php
function __autoload($class){
    include "class/" . $class . '.class.php';
}
$user1 = new User('Вася','Vasya','12345');
$user1->showInfo();

$user2 = new User('Петя','Petya','root');
$user2->showInfo();

$user3 = new User('Ivan','Ivanich','1507');
$user3->showInfo();

$user4 = clone $user1;
$user4->showInfo();

$user = new SuperUser('Vasya Pupkin','PuP','1222155','Admin');
$user->showInfo();

echo 'Всего обычных пользователей: ' . User::$count . '<br>';
echo 'Всего супер-пользователей: ' . SuperUser::$count;

interface A {}
interface B {}
interface C {}

class e implements C,A,B{
    
}
