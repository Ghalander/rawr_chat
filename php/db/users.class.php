<?php
require_once 'php/db/db.class.php';
class Users extends DB
{
    public static function login($username, $password)
    {
        parent::connect();
        $result = parent::query("SELECT * FROM users WHERE username='$username' AND password='$password'");
        $count = mysql_num_rows($result);
        if($count < 1) {
            return false;
        } else {
            $row = mysql_fetch_assoc($result);
            $_SESSION['picture'] = $row[picture];
            return true;
        }
    }
    
    public static function logout($username, $roomID, $text)
    {
        parent::connect();
        parent::query("UPDATE users SET roomID=null WHERE username='$username'");
        if (!is_null($roomID)) {
            mysql_query("INSERT INTO chatlog (username, roomID, text) VALUES ('SYSTEM', '$roomID' , '$text')");
        }
    }
    
    public static function register($username, $password, $picture)
    {
        parent::connect();
        return parent::query("INSERT INTO users (username,password,picture) VALUES ('$username', '$password', '$picture')");        
    }
    
    public static function getUserID($username)
    {
        $result = parent::query("SELECT * FROM users WHERE username='$username'");
        $row = mysql_fetch_assoc($result);
        return $row['id'];
    }
    
    public static function setRoomID($roomID)
    {
        $_SESSION['roomID'] = $roomID;
        $username = $_SESSION['username'];
        parent::connect();
        parent::query("UPDATE users SET roomID='$roomID' WHERE username='$username'");
    }
    
    public static function isRoomAdmin()
    {
        $userID = $_SESSION['userID'];
        // echo 'UserID: ' . $userID;
        $roomID = $_POST['roomID'];
        // echo '</br>roomID: ' . $roomID;
        parent::connect();
        $result = parent::query("SELECT * FROM chatrooms WHERE id='$roomID'");
        $row = mysql_fetch_assoc($result);
        $roomAdmin = $row['roomCreatorID'];
        // echo '</br>Room Admin: ' . $roomAdmin;
        if ($userID == $roomAdmin) {
            include 'room_control.php';
        }
    }
}
?>