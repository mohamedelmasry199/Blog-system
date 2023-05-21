<?php
class post{
    private $pdo;
    private $table ="posts";
    public function __construct($pdo){
        $this->pdo=$pdo;

    }
    public function createPost($title, $content , $user_id) {
        // Prepare the SQL statement with placeholders for the values to be inserted
        $query = "INSERT INTO ".$this->table ."(title, content,user_id) VALUES (:title, :content, :user_id)";
       
        // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Bind the values to the placeholders in the prepared statement
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':user_id', $user_id);
             
            // Execute the prepared statement
            $stmt->execute();

            // Return the ID of the newly created post
            return true;
        } catch(PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }
    }

    
    public function readAllPosts(){
        $query = 'SELECT ' . $this->table . '.* , users.username FROM ' . $this->table . ' LEFT JOIN users
        ON(' . $this->table . '.user_id = users.id
        )ORDER BY ' . $this->table . '.created_at DESC' ;
     try{
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results=$stmt->fetchall(PDO::FETCH_ASSOC);
        return $results;
     }
     catch(PDOException $e){
        echo $e ->getMessage();
        return false;
     }

    }
    function readOne($id)
    {


             // SQL query to select a single post from the database
        $query = "SELECT p.title, p.content , p.user_id , p.created_at, u.username a 
        FROM " . $this->table . " p INNER JOIN users u
        ON(p.user_id = u.id)
        WHERE p.id = :id LIMIT 0,1";         
        // Use a try-catch block to handle any exceptions that may occur during the query execution
        try {
            // Prepare the SQL statement for execution
            $stmt = $this->pdo->prepare($query);

            // Bind the id parameter
            $stmt->bindParam('id', $id);

            // Execute the prepared statement
            $stmt->execute();
            
            // Get the row data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            
            // Return the results
            return $row;
            
        } catch (PDOException $e) {
            // Handle any exceptions that may occur during the query execution
            echo $e->getMessage();
            return false;
        }
    }






}










?>