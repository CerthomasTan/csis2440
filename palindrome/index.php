<?php
    $input = 'race car';
    echo strlen($input);
    echo "<ul>";
    echo "<h1>Facts about \"$input\"</h1>";
    echo "<li> Palindrom: " .(isPalindrome($input)? "TRUE" : "FALSE")."</li>";
    echo "<li> Number of Characters: " . strlen($input) . "</li>";
    echo "<li> Number of Words: " . str_word_count($input)."</li>";
    echo "</ul>";

    function isPalindrome($phrase){
        //removes spaces from phrase
        $phrase = str_replace(" ", "", $phrase);
        $phrase = str_replace("'", "", $phrase);

        //lowercase
        $phrase = strtolower($phrase);

        //reverse phrase and assign to variable
        $revPhrase = strrev($phrase);

        //compare and return
        if($revPhrase == $phrase) return true;
        else return false;
    }

?>