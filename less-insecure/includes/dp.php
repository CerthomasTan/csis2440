<?php
    
    //connect to local host if http_host equals host
    if($_SERVER["HTTP_HOST"] == 'localhost'){
        DEFINE('HOST', 'localhost');
        DEFINE('USER', 'root');
        DEFINE('PASS', '1550');
        DEFINE('DB', 'csis2440');
    }
    //connect to online db when not on local machine
    else{
        DEFINE('HOST', 'localhost');
        DEFINE('USER', 'u107008560_djtoyakun');
        DEFINE('PASS', '!Tearsofthenomu100');
        DEFINE('DB', 'u107008560_csis2440');
    }

    //connect to db, return connection
    function connectToDB(){
        $conn = mysqli_connect(HOST, USER, PASS, DB);
        return $conn;
    }
    ?>