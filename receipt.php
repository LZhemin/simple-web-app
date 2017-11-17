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
    <!-- Relevant custom css -->
    <style type="text/css">
        div.row.form {
            border: 1px solid;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .table td {
            vertical-align: middle;
            background: -o-linear-gradient(bottom, #ffffff 5%, #d3e9ff 100%);
            background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ffffff), color-stop(1, #d3e9ff));
            background: -moz-linear-gradient( center top, #ffffff 5%, #d3e9ff 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr="#ffffff", endColorstr="#d3e9ff");
            background: -o-linear-gradient(top, #ffffff, d3e9ff);
            background-color: #ffffff;
            border: 1px solid #000000;
            border-width: 0px 1px 1px 0px;
            text-align: left;
            padding: 10px;
            font-size: 15px;
            font-family: Arial;
            font-weight: normal;
            color: #000000;
        }

        .table tr:first-child td {
            background: -o-linear-gradient(bottom, #0057af 5%, #007fff 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0057af), color-stop(1, #007fff));
            background: -moz-linear-gradient( center top, #0057af 5%, #007fff 100%);
            filter: progid: DXImageTransform.Microsoft.gradient(startColorstr="#0057af", endColorstr="#007fff");
            background: -o-linear-gradient(top, #0057af, 007fff);
            background-color: #0057af;
            text-align: center;
            font-size: 20px;
            font-family: Arial;
            font-weight: bold;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="h1"><b>Fruit Shop</b></h1>
        <br>
        <h2 class="h2"><b>Receipt Form</b></h2>
        <br>
        <?php
        //php starts here

    //Get the form element attribute value
    $appleVal = $_POST["appleVal"];
    $bananaVal = $_POST["bananaVal"];
    $orangeVal = $_POST["orangeVal"];
    $userName = $_POST["userName"];
    $paymentMode = $_POST["paymentMethod"];

    //write variables
    $appleWrite = 0;
    $bananaWrite = 0;
    $orangeWrite = 0;

     /* write to the order.txt file
     *
     * @appleWrite value of apple to be added
     * @orangeWrite value of orange to be added
     * @bananaWrite value of banana to be added
     *
     * close the pointer after done
     */
    function writeFile($appleWrite,$bananaWrite, $orangeWrite)
    {
            $fileName = "order.txt";
            $fptr = fopen($fileName, 'w') or exit("Unable to open file ($fileName)");
            if(flock($fptr, LOCK_EX)) //Prevent other from accessing the file
            {
                    $outputString = "Total number of apples: $appleWrite \r\nTotal number of oranges: $orangeWrite \r\nTotal number of bananas: $bananaWrite";
                    fwrite($fptr, $outputString);
                    flock($fptr, LOCK_UN); //release lock
            }
            else
                     echo "Error locking file!";
            fclose($fptr);
    }

    $fileName = "order.txt";
    //Check file exisited
    if(file_exists($fileName))
    {
            $pointerIndex = 0;
            $fptr = fopen($fileName, 'r') or exit("unable to open file ($fileName)");
            if(flock($fptr, LOCK_SH))
            {
                    while(! feof($fptr)) //Retrieve file information
                    {
                            $line = fgets($fptr); // Get line by line
                            $previousAmount = preg_split("/:/", $line); //Split line by : to segregate data
                            //array[0] is the fruit information
                            //array[1] is the number stored
                            if($pointerIndex ==0)
                            {
                                    $appleWrite  = $previousAmount[1] + $appleVal;
                            }
                            elseif($pointerIndex==1)
                            {
                                $orangeWrite = $previousAmount[1]+ $orangeVal;

                            }
                            elseif($pointerIndex==2){
                                    $bananaWrite = $previousAmount[1]+ $bananaVal;
                            }
                            $pointerIndex++;
                    }
            }
            else
                    echo "Error locking file!";

            fclose($fptr);
            writeFile($appleWrite,$bananaWrite, $orangeWrite);
    }
    else
    {
            $appleWrite = $appleVal;
            $bananaWrite = $bananaVal;
            $orangeWrite = $orangeVal;
            writeFile($appleWrite,$bananaWrite, $orangeWrite);
    }
    ?>
    </div>
    <div class="container">
        <table class="table" border="1" style="width: 100%">
            <tr>
                <td colspan="3" class="text-center">Customer name :
                    <?php print $userName ?>
                </td>
            </tr>
            <tr>
                <td>Fruit name</td>
                <td>Quantities </td>
                <td>Total Price ($) </td>
            </tr>
            <tr>
                <td>Apple (69&cent each)</td>
                <td>
                    <?php print $appleVal?>
                </td>
                <td>$
                    <?php print $appleTotalPrice=$appleVal*0.69;?>
                </td>
            </tr>
            <tr>
                <td>Orange (59&cent each) </td>
                <td>
                    <?php print $orangeVal;?>
                </td>
                <td>$
                    <?php print $orangeTotalPrice=$orangeVal*0.59;?>
                </td>
            </tr>
            <tr>
                <td>Banana (39&cent each)</td>
                <td>
                    <?php print $bananaVal;?>
                </td>
                <td>$
                    <?php print $bananaTotalPrice=$bananaVal*0.39;?>
                </td>
            </tr>
            <tr>
                <td colspan="3">Total price is $
                    <?php print $allTotalCost = $appleTotalPrice+$bananaTotalPrice+$orangeTotalPrice;?>
                </td>
            </tr>
            <tr>
                <td colspan="3">Payment mode is
                    <?php print $paymentMode;?>.</td>
            </tr>
            <tr>
                <td colspan="3">Purchased on
                    <?php date_default_timezone_set('Etc/GMT-8');
            print( date('d/m/Y g:i A'));?>.</td>
            </tr>
        </table>
        <br> Your order has been shipped.
        <br> Thank you for shopping with us.
        <br> Fruits can be exchange within 3 days.
        <br>
    </div>
</body>

</html>