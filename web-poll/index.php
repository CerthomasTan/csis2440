<?php
    //include dp.php
    include 'includes/dp.php';

    //Function to create the page
    function createPage(){
        //check if varaibles are set. If user fails to answer all questions. Don't proceed.
        if(!isset($_POST['gameseries']) || !isset($_POST['food']) || !isset($_POST['boardgame'])){
            echo createQuestionare();
        }

        //if all variables are set, add to database and display results
        else{
            addToDataBase($_POST['gameseries'], 'web_poll');
            addToDataBase($_POST['food'], 'web_poll_two');
            addToDataBase($_POST['boardgame'], 'web_poll_three');
            displayResults();
        }
    }

    //create questionare
    function createQuestionare(){
        
        //start content
        $content = "";
        $content .= "<div class='main'>";
        $content .= "<h1 class='title'>Web Poll</h1>";
        $content .= "<form action='.' method='post'>";

        //create each questions
        $content .= createQuestionOne();
        $content .= createQuestionTwo();
        $content .= createQuestionThree();
        
        //close form
        $content .= "<input type='submit'>";
        $content .= "</form>";
        $content .= "</div>";

        //return form
        return $content;
    }

    //creates first question
    function createQuestionOne(){
        //array for posible answer
        $arr = ["League of Legends", "Final Fantasy", "Street Fighter", "Super Mario"];

        //question to ask
        $content = "";
        $content .= "<h1 class='question-text'>Which Video Game Series was Most Influential?</h1>";

        //create radio button and label for each possible question
        foreach($arr as $subject){
            $content .= "<input type='radio' id='$subject' value='$subject' name='gameseries'>";
            $content .= "<label for='$subject'>$subject</label><br>";
        }

        //return content
        return $content;
    }

    //create 2nd question
    function createQuestionTwo(){
        //array for posible answer
        $arr = ["Pizza", "Nachos", "Hamburger", "Fries"];

        //question to ask
        $content = "";
        $content .= "<h1 class='question-text'>Which food is most satisfying?</h1>";

        //create radio button and label for each possible question
        foreach($arr as $subject){
            $content .= "<input type='radio' id='$subject' value='$subject' name='food'>";
            $content .= "<label for='$subject'>$subject</label><br>";
        }

        
        return $content;
    }

    //create 3rd question
    function createQuestionThree(){
        //arry for posible answers
        $arr = ["Monopoly", "Risk", "Chess", "Checkers"];

        //qestion to ask
        $content = "";
        $content .= "<h1 class='question-text'>What is the best board game?</h1>";

        //create radio button and label for each possible question
        foreach($arr as $subject){
            $content .= "<input type='radio' id='$subject' value='$subject' name='boardgame'>";
            $content .= "<label for='$subject'>$subject</label><br>";
        }

        //return content
        return $content;
    }

    //function adds data to DB 
    function addToDataBase($data, $DBName){
        //edit string to be lowercase
        $data = strtolower($data);
        $conn = connectToDB();

        //check database for primary key of answer 
        $sql = "SELECT * FROM $DBName WHERE name='$data';";
        $results = mysqli_query($conn, $sql);

        //get primary key of row
        if(mysqli_num_rows($results) != 0){
            $key = mysqli_fetch_array($results, MYSQLI_ASSOC)['id'];
        }

        //increment vote number and insert value
        $sql = "UPDATE $DBName SET votes = votes + 1 WHERE id=$key;";
        mysqli_query($conn, $sql);
        
        //close connection
        mysqli_close($conn);
    }

    //function to display results
    function displayResults(){
        //arr to contain name of tables
        $arr = ['web_poll', 'web_poll_two','web_poll_three'];

        //create results content
        $content = '';
        $content = '<div class="main">';
        $content .= "<h1 class='title'>Thank You!</h1>";
        //create new result container for each table
        foreach($arr as $DBName){
            $content .= '<div class="result-container">';
            $content .= getResults($DBName);
            $content .= '</div>';
        }
        $content .= '</div>';
        echo $content;
    }

    //function to result content, will print out each result depending on the database and calculates the percentage of votes a particular object has.
    //Function will display results in a descending order (highest to lowest)
    function getResults($DBName){
        $conn = connectToDB();
        //get results
        $sql = "SELECT * FROM $DBName;";
        $results = mysqli_query($conn, $sql);

        //get total
        $sql = "SELECT SUM(votes) as total FROM $DBName;";
        $total = mysqli_query($conn,$sql);
        $total = mysqli_fetch_assoc($total)['total'];


        //create an associate array with percentage and name
        $sortedArray = array();
        while($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
            $name = $row['name'];
            $percentage = round($row['votes']/$total * 100);
            $sortedArray[$name] = $percentage; 
        }

        //sort by percentage
        $content = '';
        $content = '<h1 class="result-text">Results</h1>';
        arsort($sortedArray);

        //display results
        foreach($sortedArray as $name => $percentage  ){
            $content .= "<h2>". ucwords($name) ." has $percentage% of the votes</h2>";
        }

        //close connection
        mysqli_close($conn);
        
        //return content
        return $content;
    }
?>
<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Web Poll</title>
    <link rel="stylesheet" href="css/style-sheet.css">
</head>
<body>
<?php 
    createPage();
?>
</body>
</html>