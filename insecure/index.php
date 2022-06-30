<?php
    //Function that will create page
    function createPage(){
        //this will attempt to get the login info from post, if not available use default values
        $loginAttempts = $_POST['loginAttempts']?? 1;
        $previousLogin = $_POST['previousLogin']?? "" ;

        //start creating content
        $content = '';
        $content .= "<div id='mainContent'>";

        //check if $_POST is empty
        if(!empty($_POST)){
            $content .= "<div id='loginMessage'>";
            //check the credentials. If true, grant access. If false, display access denied and increment login attempts
            if(checkCredentials()){
                $content .= "Access Granted";
                $content .= "</div>";
                return $content;
            }
            else{
                //if previous login matches login username attempt and the username is not empty, increment login attempts. Else reset counter.
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
        
        //if login attempts is greater than 2, show lockout message. Logic starts attmepts at 0
        if($loginAttempts > 2){
            $content .= "<div id='lockoutMessage'>";
            $content .= "Account has been locked! Please contact site administator for help.";
            $content .= "</div>";
        }
        //create webform
        $content .= createWebFrom($loginAttempts);
        $content .= "</div>";
        return $content;
    }

    //function will create the webform and take login as parameter. Login attempts will take a default constructor of 1 if no parameter is passed.
    function createWebFrom($loginAttempts = 1){
        //start form, form will have post method
        $content = "";
        $content .= "<form action='.' method='post'>";

        //grab username and place in variable. If no previousUser is availabe use default value pf "".
        $previousUser = $_POST['username']??"";
        $content .= "<input hidden name='previousLogin' value='$previousUser'>";

        //grabs login attempts and place in hidden varaible. Will sent in post
        $content .= "<input hidden name='loginAttempts' value='$loginAttempts'>";

        //username input
        $content .= "<div id='userNameContainer'>";
        $content .= "<label for='usernameBox'>Username</label>";
        $content .= "<input id='usernameBox' name='username' type='text' placeholder='Username'>";
        $content .= "</div>";

        //password input
        $content .= "<div id='passwordContainer'>";
        $content .= "<label for='passwordBox'>Password</label>";
        $content .= "<input id='passwordBox' name='password' type='password' placeholder='Password'>";
        $content .= "</div>";

        //reset and submit 
        $content .= "<div id='buttonContainer'>";
        $content .= "<input type='submit' value='Login'>";
        $content .= "<input type='reset'>";
        $content .= "</div>";

        //close form
        $content .= "</form>";
        return $content;
    }

    //will check credentials of Post
    function checkCredentials(){
        //open file stream and read users.txt.
        $fs = fopen('includes/users.txt','r');
        $data = fread($fs, filesize('includes/users.txt'));

        //create array from users.text, delimiter is ||>><<||
        $credentials = explode('||>><<||',$data);
        
        //for each credential in credential array, create array. First part of the array is username. 2nd is password
        foreach($credentials as $credential){
            $userLoginArr = explode(',' , $credential);
            //if both the username and the password matches, return true
            if(($userLoginArr[0] == $_POST['username']) && ($userLoginArr[1] == $_POST['password'])){
                return true;
            }
        }

        //return false if no password and username matches txt file. 
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