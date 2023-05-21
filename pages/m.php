<?php
include_once('../config/database.php');
include_once('../classes/Post.php');
$post1= new post($pdo);
$m=$post1->readOne(11);
print_r($m);

?>