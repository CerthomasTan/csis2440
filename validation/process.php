<!--process.php-->
<?php
    //print out for page
    $printOut = "";

    //check error codes
    $errorCodes = errorCheck($_POST);

    //if error is true, redirect back to index with userdata and error codes
    if($errorCodes['error']){
        $errorArray = json_encode($errorCodes); //creates a JSON of array to be placed in get
        //redirect with user information and error codes
        header('location:.?error='. $errorArray. '&fName='. $_POST['fName'].'&email='. $_POST['email'].'&phoneNumber='. $_POST['phoneNumber'].'&zipCode='. $_POST['zipCode']);
    }
    else{ 
        $printOut = createPage();//create page if no errors exists
    }

    //Function to create body of html
    function createPage(){
        $text = "";
        $text .= createThankYou();
        $text .= createForm($_POST);
        return $text;
    }

    //Function to create thank you message at the top
    function createThankYou(){
        $text = "";
        $text .= "<div id='mainContainer'>";
        $text .= "<h1>Thank you</h1>";
        $text .= "<h1>The data you entered was valid</h1>";
        $text .= "</div>";
        return $text;
    }

    //displays user information in form, simliar to index.php form, but makes form read only with no sumbit or clear button
    function createForm($userData){
        $text = "";

        $text = "<h1>Your Information</h1>";
        
        $text .= '<form action="" method="">';
        $text .= '<div> <label for="fName">First Name</label> <input type="text" value="'. $userData['fName'] .'"name="fName" placeholder="First Name" readonly> </div>';
        $text .= '<div> <label for="email">Email</label><input type="text" value="'. $userData['email'] .'"name="email" placeholder="email@domain.com" readonly> </div>';
        $text .= '<div> <label for="phoneNumber">Phone Number</label><input type="text" value="'. $userData['phoneNumber'] .'"name="phoneNumber" placeholder="(999)999-9999" readonly></div>';
        $text .= '<div> <label for="zipCode">Zip Code</label><input type="text" value="'. $userData['zipCode'] .'"name="zipCode" placeholder="Zip Code" readonly></div>';

        $text .= '<div id="buttonContainer">';
        $text .= '</div>';
        $text .= '</form>';
        return $text;
    }

    //check for errors
    function errorCheck($formData){
        //creates default array with values
        $errorData['error'] = false;
        $errorData['email'] = 0;
        $errorData['phoneNumber'] = 0;
        $errorData['fName'] = 0;
        $errorData['zipCode'] = '0';

        //check if email is set
        if(empty($formData['email'])){
            $errorData['email'] += 1;
            $errorData['error'] = true;
        }
        //check if email is valid
        else if(!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
            $errorData['email'] += 2;
            $errorData['error'] = true;
        }

        //check if phoneNumber is set
        if(empty($formData['phoneNumber'])){
            $errorData['phoneNumber'] += 1;
            $errorData['error'] = true;
        }
        //check if phone number is valid
        else if (!preg_match('/^\(\d{3}\)\d{3}-\d{4}$/',$formData['phoneNumber'])){
            $errorData['phoneNumber'] += 2;
            $errorData['error'] = true;
        }

        //check if name is available
        if(empty($formData['fName'])){
            $errorData['fName'] += 1;
            $errorData['error'] = true;
        }
        //check if name is valid
        else if (!preg_match('/^[a-zA-Z|\s]+$/',$formData['fName'])){
            $errorData['fName'] += 2;
            $errorData['error'] = true;
        }

        //check if zip code is available 
        if(empty($formData['zipCode'])){
            $errorData['zipCode'] += 1;
            $errorData['error'] = true;
        }
        //check if zipcode is valid
        else if (!preg_match('/^\d{5}$/',$formData['zipCode'])){
            $errorData['zipCode'] += 2;
            $errorData['error'] = true;
        }
        return $errorData;
    }
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/mystylesheet.css">
    <title>results</title>
</head>
<body>
    <?php 
        //prints out webpages
        echo $printOut
    ?>
</body>
</html>