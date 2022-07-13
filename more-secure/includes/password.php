<?php

    //password.php

    function getEncryptPassword($username, $password){
            $password = hash('sha512', $password);
            $su1 = 'dsfdsfsdfdf';
            $su2 = 'gdgfdsfgdsf';
            $salt1 = hash('sha512',$su1.$username.$su2);
            $salt2 = hash('sha512',$su1.$username.$username.$su2);
            $password = $salt1 . $password . $salt2;
            return hash('sha512',$password);
        }

    function convertDBPasswords(){
        $conn = connectToDB();
        $sql = "SELECT * FROM csis2440_user_secure;";
        $results = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
            $username = mysqli_escape_string($conn, $row['username']);
            $password = mysqli_escape_string($conn, $row['password']);
            $password = getEncryptPassword($username, $password);
            changePassword($username, $password);
        }
    }

    //changes password of user
    function changePassword($username, $password){
        $conn = connectToDB();
        $sql = "SELECT * FROM csis2440_user_secure WHERE username = '$username';";
        $results = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
        //update passward column if user exists in db
        if($row != Null){
            $sql = "UPDATE csis2440_user_secure SET `password` = '$password' WHERE `id` = '".$row['id']."'";
            mysqli_query($conn, $sql);
            return true;
        }
        //if user is not found, return
        else{
            return false;
        }
    }
    
?>