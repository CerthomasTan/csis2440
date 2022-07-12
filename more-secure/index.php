<?php
    include "includes/dp.php";

    //Function that will create page
    function createPage(){
        //start creating content
        $content = '';
        
        #echo "<pre>". var_dump($_POST)."</pre>";
        //set varaibales 
        $content .= "<div id='mainContent'>";
        $loginAttempts = $_POST['loginAttempts']?? 0;

        //if post is not set, this is first instance of visiting site. Just create form
        if(empty($_POST)){
            $content .= createWebFrom($loginAttempts);
            $content .= "</div>";
            $content .= printUsers();
        }

        //if username and password are set, check the credential if there is a match. If there is a match, grant access.
        else if(isset($_POST['username']) && isset($_POST['password']) && checkCredentials($_POST['username'], $_POST['password'])){
            $content .= "<div id='loginMessage'>";
            $content .= "<h3>Access Granted.</h3>";
            $content .= "<h4>Welcome ".$_POST['username']." you have logged in ".getLoginCount($_POST['username'])."</h4>";
            $content .= "</div>";   
        }

        //if not go thru error message. lock account if user login attempts are 3 or more.
        else{
            $content .= "<div id='loginMessage'>";
            $content .= "Access Denied";
            if(!findUserInDB($_POST['username'])){
                $content .= ", User doesn't exists.";
                $loginAttempts = 0;;
            }
            //increment if username is same as last attempt
            else if($_POST['username'] == $_POST['previousUsername'] && $_POST['username'] != ""){
                $loginAttempts += 1;
            }
            //if not, reset counter
            else{
                $loginAttempts = 0;
            }
            //if login attempts are 3 or more (attempt 1 start at 0), lock account and change password.
            if($loginAttempts >= 2){
                //generate random pass
                $content .= ", Account has been locked.";
                $alphabet = "abcdefghijkjklmnopqrstuvwxyz1234567890";
                $newPassword = array();
                $alphaLength = strlen($alphabet) - 1;
                for($i = 0; $i < 8; $i++){
                    $n = rand(0, $alphaLength);
                    $newPassword[] = $alphabet[$n];
                }
                $newPassword = implode($newPassword);
                changePassword($_POST['username'], $newPassword);
            }
            $content .= "</div>";
            $content .= createWebFrom($loginAttempts);
            $content .= "</div>";
            $content .= printUsers();
        }
        
        //if login attempts is greater than 2, show lockout message. Logic starts attmepts at 0
        return $content;
    }

    //function will create the webform and take login as parameter. Login attempts will take a default constructor of 1 if no parameter is passed.
    function createWebFrom($loginAttempts){
        //start form, form will have post method
        $content = "";
        $content .= "<form action='.' method='post'>";

        //grab username and place in variable. If no previousUser is availabe use default value pf "".
        $previousUsername = $_POST['username']??"";
        $content .= "<input hidden name='previousUsername' value='$previousUsername'>";

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
        $content .= "<a href='create-account.php'><input type='button' value='Create Account'></a>";
        $content .= "</div>";

        //close form
        $content .= "</form>";
        return $content;
    }

    //will check credentials of Post
    function checkCredentials($username, $password){
        //connect to db
        $conn = connectToDB();
        //find row where username is equal to user inputed username
        $sql = "SELECT * FROM csis2440_user_secure WHERE username = '$username';";
        $results = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
        //if row is not null and the password matches user password, return true and increment loginCount
        if($row != Null && $row['password'] == $password){
            $sql = "UPDATE csis2440_user_secure SET `loginCount` = loginCount+1 WHERE `id` = '".$row['id']."'";
            mysqli_query($conn, $sql);
            mysqli_close($conn);
            return true;
        }
        //return if username not found or password doesn't match
        else{
            mysqli_close($conn);
            return false;
        }
    }

    //will return the login count
    function getLoginCount($username){
        //connect to db
        $conn = connectToDB();
        $sql = "SELECT * FROM csis2440_user_secure WHERE username = '$username';";
        $results = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
        return $row['loginCount'];
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

    //finds if user exists in db
    function findUserInDB($username){
        $conn = connectToDB();
        $sql = "SELECT * FROM csis2440_user_secure WHERE username = '$username';";
        $results = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
        //return true if user is found
        if($row != Null){
            return true;
        }
        //if user is not found, return
        else{
            return false;
        }
    }

    function printUsers(){
        $content = '<a href="index.php?getUsers=1">Click to Get Users</a>';
        $var = $_GET['getUsers']??null;
        if($var != null){
            //print list of users;
            $conn = connectToDB();
            $sql = "SELECT `username`, `password` FROM csis2440_user_secure;";
            $results = mysqli_query($conn, $sql);
            $content .= "<table><tr><th>UserName</th><th>Password</th></tr>";
            while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
               $content .="<tr><td>".$row['username']."</td><td>".$row['password']."</td></tr>";
            }
        }
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
    <?php
    echo createPage();
    ?>
</body>
</html>