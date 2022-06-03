<?php
    /*
    Header php, create multidimensional array to store values. Then call the function of insert Album to insert data into
    array. This array will be called in the body.
    */

    $favoriteAlbums; //array varaible that will contain albums 
    #$degubAlbums = ['album1', 'album2','album3'];

    //data will be inserted into array
    insertAlbum("Alpha", "CL", "https://www.chaelincl.com/");
    insertAlbum("Symbol", "Tymee","https://www.youtube.com/channel/UCEih7vGXWWC1COXnW0aQHlw/featured");
    insertAlbum("I", "Ichika","https://www.youtube.com/channel/UCq3Wpi10SyZkzVeS7vzB5Lw");
    insertAlbum("The Age of Villians", "Yousei Teikoku", "http://dasfeenreich.com/");
    insertAlbum("Pax Vesania", "Yousei Teikoku", "http://dasfeenreich.com/");
    insertAlbum("Kyo", "M-FLO", "https://m-flo.com/en?ls=en-US&cache=false");
    insertAlbum("Vocalo Zanmai", "Wagakki Band", "https://wagakkiband.com/");
    insertAlbum("Kyogen", "Ado", "https://www.youtube.com/channel/UCln9P4Qm3-EAY4aiEPmRwEA");
    insertAlbum("Gotta Go Fast", "NateWantsToBattle", "https://www.natewantstobattle.com/");
    insertAlbum("Today Is A Beautiful Day", "SuperCell", "https://www.supercell.jp/");
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Music-Database</title>
    <link rel="stylesheet" href="css/mystylesheet.css">
    <script src="js/myscript.js"></script>
</head>
<body>
    <?php
        //create main content of page
        echo "<div id='main'>" . printTable($favoriteAlbums) . "</div>";
    ?>
</body>
</html>

<?php
    /*
    This function will insert an array into the $favoriteAlbum array. It will take an an album name, artist, and link as parameters
    for the function.
    */
    function insertAlbum($albumName, $artist, $link){
        Global $favoriteAlbums; //declare global variable
        //insert array into next index of array
        $favoriteAlbums[] = 
            ['albumName' => $albumName,
             'artist' => $artist,
             'link' => $link]; 
    }

    /*
    This function will take an album array as an argument. Then, the function will create an organized table and return the html code. 
    */
    function printTable($albumArray){
        //error catching to determine if array is null or empty
        if(empty($albumArray) || !isset($albumArray)){
            return "Array is Empty or doesn't exists";
        }

        //shuffle the array for additional content section
        shuffle($albumArray);

        //create table
        $return = "<table>"; 
        $return .= "<caption id='table-title'>Favorite Albums</caption>"; //create title

        //check if array is multidimensional or not
        //code block for 1d array
        if(!is_array($albumArray[0])){
            //create table with single column
            foreach($albumArray as $album){
                $return .= "<tr>";
                $return .= "<td>$album</td>";
                $return .= "</tr>";
            }
        }
        
        //code block for multidimensional arrayt
        else{
            //create data column titles (Album, Artist, Link)
            $return .= "<tr> <td>Album</td> <td>Artist</td> <td>Website</td> </tr>";
            
            //create row for each album
            foreach($albumArray as $album){
                //open table row
                $return .= "<tr>";
                
                //add data for all data within sub array
                foreach($album as $key => $albumInfo){
                    //check if key is link. if the data is a link, create anchor and link.
                    if($key == "link"){
                        $return .= "<td><a href='$albumInfo' target='_blank'>Link</a></td>";
                    }

                    //if not link, create standarded table cell
                    else{
                    $return .= "<td>$albumInfo</td>";
                    }
                }

                //close the table row
                $return .= "</tr>";
            }
        }
        //return entire html string code
        return $return;
    }
?>