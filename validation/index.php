<!--index.php-->
<?php
    //varaibles
    $printOut = ""; //html code that will be printed to page

    //default varaibles for page logic
    $userData = array('email'=>'', 'phoneNumber'=>'', 'fName'=>'', 'zipCode' => '');

    $errorData = array('error'=>false);

    //check get if error data, exists. If exists, replace errorData
    if(!empty($_GET['error'])){
        $errorData = (array)json_decode($_GET['error']);
    }

    //check get if userdata, exists. If exists, replace userdata
    setVaraibles();

    //create webpage
    $printOut = createForm($userData);
    $printOut .= createErrorMessage($errorData);

    //function will create a webform and will take an array. This function will fill in the values from the given array and create a webpage.

    function setVaraibles(){
        GLOBAL $userData;
        if(!empty($_GET['fName'])){
            $userData['fName']= $_GET['fName'];
        }
        if(!empty($_GET['email'])){
            $userData['email']= $_GET['email'];
        }
        if(!empty($_GET['zipCode'])){
            $userData['zipCode']= $_GET['zipCode'];
        }
        if(!empty($_GET['phoneNumber'])){
            $userData['phoneNumber']= $_GET['phoneNumber'];
        }
    }
    function createForm($userData){

        $text = ""; //create string builder varaible
        $text = "<h1>Please Enter Your Information Below.</h1>"; //create title 
        
        //create form. userData feilds will fill in the values. 
        $text .= '<form action="process.php" method="post">';
        $text .= '<div> <label for="fName">First Name</label> <input id="fName" type="text" value="'. $userData['fName'] .'" name="fName" placeholder="First Name"> </div>';
        $text .= '<div> <label for="email">Email</label><input id="email" type="text" value="'. $userData['email'] .'" name="email" placeholder="email@domain.com"> </div>';
        $text .= '<div> <label for="phoneNumber">Phone Number</label><input id="phoneNumber" type="text" value="'. $userData['phoneNumber'] .'" name="phoneNumber" placeholder="(999)999-9999"></div>';
        $text .= '<div> <label for="zipCode">Zip Code</label><input id="zipCode" type="text" value="'. $userData['zipCode'] .'" name="zipCode" placeholder="Zip Code"></div>';

        //create the buttons
        $text .= '<div id="buttonContainer">';
        $text .= '<input id="submit" type="submit">';

        //created a seperate submit button name clear form since reset button will only reset to initial values which causes wierd behavoirs if page is redirected from process.php. Left reset button for assignment 
        $text .= '<input id="clearForm" value="Clear Form" type="submit" formaction=".">';
        $text .= '<input id="reset" type="reset" >';
        $text .= '</div>';
        $text .= '</form>';
        return $text;
    }

    //create errors if any exists
    function createErrorMessage($errorData){
        $text="";
        //if No errors exists, exit out of function
        if($errorData['error'] == false){
            return $text;
        }
        //create error container
        $text .= "<div id='errorContainer'>"; //create div
        $text .= getFirstNameError($errorData['fName']); //create name error
        $text .= getPhoneError($errorData['phoneNumber']); //create phone error
        $text .= getEmailError($errorData['email']); //create email error
        $text .= getZipCodeError($errorData['zipCode']); //create zip error
        $text .= "</div>"; //close div
        return $text;
    }

    //creates phone number error, parameter is an error code. If error code is 1, phone feild was left blank and will prompt user to enter information. If error code is 2, an invalid entry was submitted. Validation was done in process.php
    function getPhoneError($errorCode){
        $text ="";
        
        switch ($errorCode)
        {
            case 2:
                $text = '<p>PhoneNumber is Invalid, Please Type in format of (xxx)xxx-xxxx</p>';
                break;
            case 1:
                $text = '<p>Please Enter Phone Number</p>';
                break;
            case 0:
                break;
        }
        
        return $text;
    }

    //creates email error, parameter is an error code. If error code is 1, phone feild was left blank and will prompt user to enter information. If error code is 2, an invalid entry was submitted. Validation was done in process.php
    function getEmailError($errorCode){
        $text ="";
        switch ($errorCode)
        {
            case 2:
                $text = '<p>Email is Invalid, please make sure there are no spaces and is in a valid email format (email@domain.com)</p>';
                break;
            case 1:
                $text = '<p>Please Enter Email</p>';
                break;
            case 0:
                break;

        }
        return $text;
    }

    //creates first name error, parameter is an error code. If error code is 1, phone feild was left blank and will prompt user to enter information. If error code is 2, an invalid entry was submitted. Validation was done in process.php
    function getFirstNameError($errorCode){
        $text ="";
        switch ($errorCode)
        {
            case 2:
                $text = '<p>First Name is Invalid, please make sure there are no digits and no special characters.</p>';
                break;
            case 1:
                $text = '<p>Please Enter First Name</p>';
                break;
            case 0:
                break;

        }
        return $text;
    }

    //creates zipcode error, parameter is an error code. If error code is 1, phone feild was left blank and will prompt user to enter information. If error code is 2, an invalid entry was submitted. Validation was done in process.php
    function getZipCodeError($errorCode){
        $text ="";
        switch ($errorCode)
        {
            case 2:
                $text = '<p>ZipCode is Invalid, please make sure there are only 5 digits.</p>';
                break;
            case 1:
                $text = '<p>Please Enter a zip code</p>';
                break;
            case 0:
                break;

        }
        return $text;
    }
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/mystylesheet.css">
    <title>Validation</title>
</head>
<body>
    <?php 
        //print page out
        echo $printOut;
    ?>
</body>
</html>