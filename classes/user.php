<?php
//session_start();
class user{
    private $pdo;
    public function __construct(PDO $pdo){
        $this->pdo= $pdo;
    }
    public function register($username,$email,$password){
        $errors=array();
        if(empty($username)){
            $errors[]="please enter a username.";
        }
        if(empty($email)){
            $errors[]="please enter an email adress.";
        }
        if(empty($password)){
            $errors[]="please enter a password.";
        }
        //check if the username or email is already exist before adding to database
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            $errors[]="please enter a valid email adress.";
        }
        // Check if the username or email address is already in use
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
            
        $stmt->execute(array(':username' => $username, ':email' => $email));
 
        
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $errors[] = "Username or email address already in use.";
        }
        //adding to database
        if(empty($errors)){
            $hash =password_hash($password,PASSWORD_DEFAULT);
            $stmt=$this->pdo->prepare("insert into users(username , email ,password) values (:username , :email ,:password)");
            $stmt->execute(array(':username'=>$username,':email'=>$email,':password'=>$hash));
            return true;
       }
       else{
        return $errors;
       }

        
    }
    public function login($email,$password){
        $stmt=$this->pdo->prepare('select * from users where email=:email');
        $stmt->execute(array(':email' => $email));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);//user feha associative array feha kol el data el khasa bl user dh fe el database

        if($user && password_verify($password,$user['password'])){ //check email and password are correct as register
            $_SESSION['user_id']=$user['id']; // save the data of login
            $_SESSION['email']=$user['email'];
            $_SESSION['username']=$user['username'];
            header('location : homepage.php'); //go to homepage
            exit;
        }
        else{
            return "the email or password not correct";
        }

    }







}





?>