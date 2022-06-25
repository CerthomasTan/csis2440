<?php

    function createPage(){

        $loginAttempts = $_POST['loginAttempts']?? 1;
        $previousLogin = $_POST['previousLogin']?? "" ;

        $content = '';
        $content .= "<div id='mainContent'>";
        if(!empty($_POST)){
            $content .= "<div id='loginMessage'>";
            if(checkCredentials()){
                $content .= "Access Granted";
                $content .= "</div>";
                return $content;
            }
            else{
                if($previousLogin == $_POST['username'] && !empty($_POST['username'])){
                    $loginAttempts += 1;
                }
                else{
                    $loginAttempts = 1;
                }
                $content .= "Access Denied";
                $content .= "</div>";
            }
        }
        
        if($loginAttempts > 2){
            $content .= "<div id='lockoutMessage'>";
            $content .= "Account has been locked! Please contact site administator for help.";
            $content .= "</div>";
        }
        $content .= createWebFrom($loginAttempts);
        $content .= "</div>";
        return $content;
    }

    function createWebFrom($loginAttempts = 1){
        $content = "";
        $content .= "<form action='' method='post'>";

        $previousUser = $_POST['username']??"";
        $content .= "<input hidden name='previousLogin' value='$previousUser'>";

        $content .= "<input hidden name='loginAttempts' value='$loginAttempts'>";

        $content .= "<div id='userNameContainer'>";
        $content .= "<label for='usernameBox'>Username</label>";
        $content .= "<input id='usernameBox' name='username' type='text' placeholder='Username'>";
        $content .= "</div>";

        $content .= "<div id='passwordContainer'>";
        $content .= "<label for='passwordBox'>Password</label>";
        $content .= "<input id='passwordBox' name='password' type='password' placeholder='Password'>";
        $content .= "</div>";

        $content .= "<div id='passwordContainer'>";
        $content .= "<input type='submit' value='Login'>";
        $content .= "<input type='reset'>";
        $content .= "</div>";

        $content .= "</form>";
        return $content;
    }

    function checkCredentials(){
        $fs = fopen('includes/users.txt','r');
        $data = fread($fs, filesize('includes/users.txt'));
        $credentials = explode('||>><<||',$data);
        
        foreach($credentials as $credential){
            $userLoginArr = explode(',' , $credential);
            if(($userLoginArr[0] == $_POST['username']) && ($userLoginArr[1] == $_POST['password'])){
                return true;
            }
        }

        return false;
    }

?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Insecure</title>
    <link rel="stylesheet" href="css/style-sheet.css">
</head>
<body>
    <?php
    echo createPage();
    ?>
</body>
</html>