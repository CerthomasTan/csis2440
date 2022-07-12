<?php

include "includes/dp.php";

    $errorCodes;

    function checkCredentials(){
        global $errorCodes;
        if(empty($_POST)){
            return false;
        }
        $errorCodes['username'] = checkUserName();
        $errorCodes['password'] = checkPassword();
        $errorCodes['secretCode'] = checkSecretCode();
        if(array_sum($errorCodes) == 0){
            return true;
        }
        else{
            return false;
        }
    }

    function printErrors($errorCodes){
        if(empty($_POST)){
            return;
        }
        $content = "<div id='errorMessage'>";
         switch($errorCodes['username']){
            case 2:
                $content .= "<h3>Username field is empty</h3>";
                break;
            case 1:
                $content.= "<h3>Username already exists.</h3> ";
                break;
         }
         switch($errorCodes['password']){
            case 2:
                $content.= "<h3>Password field is empty.</h3>";
                break;
            case 1:
                $content.= "<h3>Password doesn't match re-entered password.</h3>";
                break;
         }
         switch($errorCodes['secretCode']){
            case 2:
                $content.= "<h3>Secret Code is empty.</h3>";
                break;
            case 1:
                $content.= "<h3>Secret Code is incorrect.</h3>";
                break;
         }
        $content .= "</div>";
        return $content;
    }

    function checkUserName(){
        if(empty($_POST['username'])){
            return 2;
        }

        $conn = connectToDB();
        $sql = "SELECT * FROM csis2440_user_secure WHERE username = '".$_POST['username']."';";
        $results = mysqli_query($conn, $sql);
        if($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
            
            return 1;
        }
        else{
            return 0;
        }
    }

    function checkPassword(){
        if(empty($_POST['password'])){
            return 2;
        }
        if($_POST['password'] != $_POST['verifyPassword']){
            return 1;
        }
        
        return 0;
    }

    function checkSecretCode(){
        if(empty($_POST['secretCode'])){
            return 2;
        }
        $conn = connectToDB();
        $sql = "SELECT * FROM secretcode WHERE assignment = 'm5a2';";
        $results = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
        //return true if user is found
        if($row != Null && $row['code'] == $_POST['secretCode']){
            return 0;
        }
        //if user is not found, return
        else{
            return 1;
        }
    }

    function createAccountFrom(){
        //start form, form will have post method
        $content = "";
        $content .= "<form action='./create-account.php' method='post'>";

        //username input
        $username = $_POST['username']??null;
        $content .= "<div id='userNameContainer'>";
        $content .= "<label for='usernameBox'>Username</label>";
        $content .= "<input id='usernameBox' name='username' type='text' placeholder='Username' value='$username'>";
        $content .= "</div>";

        //password input
        $content .= "<div id='passwordContainer'>";
        $content .= "<label for='passwordBox'>Password</label>";
        $content .= "<input id='passwordBox' name='password' type='password' placeholder='Password'>";
        $content .= "</div>";

        //password input
        $content .= "<div id='verifyPasswordContainer'>";
        $content .= "<label for='verifyPasswordBox'>Re-enter Password</label>";
        $content .= "<input id='passwordBox' name='verifyPassword' type='password' placeholder='Verify Password'>";
        $content .= "</div>";

        //password input
        $secretCode = $_POST['secretCode']??null;
        $content .= "<div id='secretCodeContainer'>";
        $content .= "<label for='secretCodeBox'>Secret Code</label>";
        $content .= "<input id='secretCodeBox' name='secretCode' type='password' placeholder='Secret Code' value='$secretCode'>";
        $content .= "</div>";

        //reset and submit 
        $content .= "<div id='buttonContainer'>";
        $content .= "<input type='submit' value='Create Account'>";
        $content .= "<input type='reset'>";
        $content .= "<a href='index.php'><input type='button' value='Login Page'></a>";
        $content .= "</div>";

        //close form
        $content .= "</form>";
        return $content;
    }
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>More Secure</title>
    <link rel="stylesheet" href="css/style-sheet.css">
</head>
<body>
    <div id="mainContent">
        <?php
            if(checkCredentials()){
                echo "added to db"; //insert to db needed
            }
            else{
                echo printErrors($errorCodes);
                echo createAccountFrom();
            }
            
        ?>
    </div>
</body>
</html>