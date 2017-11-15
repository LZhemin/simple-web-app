<!--
    author : Liu Zhemin (U1621997K)
    Project for CZ3006 Net Centric Computing Nanyang Technological University.
    Project was done as part of a coursework requirement for CZ3006 in AY17/18 Semester 1.

    php includes both css and javascript codes as the instruction was to create a servide-side PHP program
 -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Receipt Page</title>
        <!-- Icon for Web -->
        <link rel="shortcut icon" href="img/appleicon.png" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style type="text/css">
            div.row.form{
                border: 1px solid;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="h1"><b>Fruit Shop</b></h1>
            <!--<div class="row">
                <img src="img/store.jpg" alt="Fruit store">
            </div>-->
            <br>
            <h2 class="h2"><b>Receipt Form</b></h2>
            <br>
            <?php

    //Get form element attribute value
    $appleAmount = $_POST["appleAmountName"];
    $bananaAmount = $_POST["bananaAmountName"];
    $orangeAmount = $_POST["orangeAmountName"];

    //write varibles
    $appleWrite = 0;
    $bananaWrite = 0;
    $orangeWrite = 0;

    //Write to file
    function writeFile($appleWrite,$bananaWrite, $orangeWrite)
    {
            $fileName = "order.txt";
            $file = fopen($fileName, 'w') or exit("Unable to open file ($fileName)");
            if(flock($file, LOCK_EX)) //Prevent other from accessing the file
            {
                    $outputString = "Total number of apples: $appleWrite \r\nTotal number of oranges: $orangeWrite \r\nTotal number of bananas: $bananaWrite";
                    fwrite($file, $outputString);
                    flock($file, LOCK_UN); //release lock
            }
            else
                     echo "Error locking file!";
            fclose($file);
    }
    $fileName = "order.txt";
    //Check file exisited
    if(file_exists($fileName))
    {
            $pointerIndex = 0;
            $file = fopen($fileName, 'r') or exit("unable to open file ($fileName)");
            if(flock($file, LOCK_SH)) //allow other processes to access the file
            {
                    while(! feof($file)) //Retrieve file information
                    {
                            $line = fgets($file); // Get line by line
                            $previousAmount = preg_split("/:/", $line); //Split line by semi colon to get previous data
                            //array 0 is the string information
                            //array 1 is the number store
                            if($pointerIndex ==0)
                            {
                                    $appleWrite  = $previousAmount[1] + $appleAmount;
                            }
                            elseif($pointerIndex==1)
                            {
                                $orangeWrite = $previousAmount[1]+ $orangeAmount;

                            }
                            elseif($pointerIndex==2){
                                    $bananaWrite = $previousAmount[1]+ $bananaAmount;
                            }
                            $pointerIndex++;
                    }
            }
            else
                    echo "Error locking file!";

            fclose($file);
            writeFile($appleWrite,$bananaWrite, $orangeWrite);
    }

    else
    {
            $appleWrite = $appleAmount;
            $bananaWrite = $bananaAmount;
            $orangeWrite = $orangeAmount;
            writeFile($appleWrite,$bananaWrite, $orangeWrite);
    }
    ?>

        </div>
    </body>
</html>