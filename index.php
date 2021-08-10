<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coprar Converter</title>
    <link rel="icon" type="image/png" href="cart.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Test Try Place -->
    <?php
        //http://localhost/3000/
        //ssh -R 80:localhost:3000 plan@localhost.run
        //https://68f204ac146f5a.localhost.run/

        require 'vendor/autoload.php';
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
        $reciever_code = "RECIEVER";
        $callsign_code = "CALLSIGN";
        $file_output = "Output";

        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); //ignore warning
        $spreadsheet = $reader->load("sample.xlsx");
        $d=$spreadsheet->getSheet(0)->toArray();
        $num=count($d);
        

        if (isset($_SERVER["REQUEST_METHOD"]) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
            $reciever_code = test_input($_POST["reciever_code"]);
            $callsign_code = test_input($_POST["callsign_code"]);
            // echo $file_output." hi"."</br>";
        }

        echo "Hello Piee";
        echo "\n";
        echo count($d);


        function test_input($data) {
            $data = trim($data); //removes whitespace and other predefined characters from both sides of a string
            $data = stripslashes($data); //removes backslashes added by the addslashes() function
            $data = htmlspecialchars($data); //converts some predefined characters to HTML entities
            return $data;
        }

    ?>


    <?php echo $num+3 ; ?>
    <?php //echo $d[row][col] ; ?>
    <?php echo $d[1][2] ; ?>
    <?php echo $d[3][2] ; ?>
    </br>
    <?php 
        // for ($row=0; $row<$num; $row++){
        //     echo $d[$row][4];
        // }
    ?>
    </br>
    <?php 
        // for ($row=0; $row<$num; $row++){
        //     for ($col=0; $col<12; $col++)
        //     echo $d[$row][$col];
        // }
    ?>
 


    <div class="container">
        <br/>
        <form role="form" class="container" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="card">
                <div class="jumbotron text-center" >
                    <h2><span><img src="cart.png" alt="Cart Icon" width="75" height="75" style="margin-right: 25px;"></span>Export Booking Excel to Coprar Converter</h2>
                </div>

                <div class="card-body">
                    <!-- Receiver and Callsign Code-->
                    <div class="form-group">
                        <label for="reciever_code">Receiver Code:</label>
                        <input class="form-control" id="reciever_code" name="reciever_code" type="text" placeholder="Enter Receiver Code" value="<?php echo $reciever_code;?>">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="callsign_code">Callsign Code:</label>
                        <input class="form-control" id="callsign_code"  name="callsign_code" type="text" placeholder="Enter Callsign Code" value="<?php echo $callsign_code;?>">
                    </div>
                    <br/>

                    <!-- Upload excel file -->
                    <!-- <div>Export booking excel file:</div>
                    <a href="sample.xlsx" download>sample.xlsx</a>
                    <div><button type="submit" class="btn btn-primary">Submit</button></div>
                </div> -->

                    <div>
                        <!-- <label>Choose Excel File: </label> <input type="file" name="file" id="file" accept=".xls,.xlsx"> -->
                        <button type="submit" id="submit" name="submit" class="btn-submit">Submit</button>
                    </div>
                    </br>

                    <div class="form-group"><textarea class="form-control" rows="10" cols="20" id='file_output' name="file_output"><?php echo $file_output ; ?></textarea></div>
            </div>
        </form>
    </div>
    <footer class="mastfoot mt-auto text-center">
        <div class="inner " style="padding-top: 20px; ">
            <p> Muhammad Syazwan | August 2021 | Source Code: <a href="https://github.com/syaz131/To-Coprar" target="_blank"><i class="fa fa-github"></i> To Coprar</a> | <a href="https://westports.github.io/ETP/" target="_blank">To Coprar JS Vers</a></p>
        </div>
    </footer>

    <!-- Create Coprar Edi -->
    <?php
        $line = $contcount = 0;
        $refno = get_date_str("");
        $edi = "UNB+UNOA:2+KMT+". $reciever_code. "+". get_date_str("daterawonly"). ":". get_date_str("timetominrawonly"). "+". $refno. "'\n";
        $edi .= "UNH+". $refno. "+COPRAR:D:00B:UN:SMDG21+LOADINGCOPRAR'\n";
        $line++;
        
        echo $edi;
        echo "</br>---------- </br>";
        echo $line;
        echo "</br>----------</br> ";

        //Process Header
        $report_dt = $voyage = $vslname = $callsign = $opr = "";





        function get_date_str($type) {
            $now = getdate(date("U"));
            $dt = $now["mday"];
            $hrs = $now["hours"];
            $min =  $now["minutes"];
            $sec = $now["seconds"];
            $mth = $now["mon"] + 1;
            $yr = $now["year"];

            $dt  = (strlen(strval($dt)) < 2) ? "0". strval($dt): strval($dt);
            $hrs  = (strlen(strval($hrs)) < 2) ? "0". strval($hrs): strval($hrs);
            $min  = (strlen(strval($min)) < 2) ? "0". strval($min): strval($min);
            $sec  = (strlen(strval($sec)) < 2) ? "0". strval($sec): strval($sec);
            $mth  = (strlen(strval($mth)) < 2) ? "0". strval($mth): strval($mth);

            if ($type == "daterawonly")
                return $yr.$mth.$dt;
            else if ($type == "timetominrawonly")
                return $hrs.$min;
            else
                return $yr.$mth.$dt.$hrs.$min.$sec;

        }     
        
        // $now = getdate(date("U"));
        // echo $now["mday"]."</br>";
        // echo $now["hours"]."</br>";
        // echo $now["minutes"]."</br>";
        // echo $now["seconds"]."</br>";
        // echo $now["mon"]."</br>";
        // echo $now["year"]."</br>";

        // echo "---------- </br>";
        // $dt = $now["mday"];
        // $dt = (strlen(strval($dt)) < 2) ? "0". strval($dt): strval($dt);
        // echo gettype($dt).$dt ;

    ?>




    <!-- Upload Excel File  -->
    <?php
    // use Phppot\DataSource;
    // use PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    // require_once 'DataSource.php';
    // $db = new DataSource();
    // $conn = $db->getConnection();
    // require_once ('./vendor/autoload.php');

    // if ($_SERVER["REQUEST_METHOD"] == "POST"){
    //     if (empty($_POST["reciever_code"]))
    //     {    $recvErr = "Reciever code is required";}
        
    //     else 
    //       {  $reciever_code = $_POST["receiver_code"];}
        
    // }

    echo "<h4>Your Input:</h4>";
    echo $reciever_code;
    echo "</br>";
    echo $callsign_code;

    

    //=================================

    // if (isset($_POST["submit"])) {


    //     echo $reciever_code;
    //     echo $callsign_code;

    //     $allowedFileType = [
    //         'application/vnd.ms-excel',
    //         'text/xls',
    //         'text/xlsx',
    //         'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    //     ];

    //     if (in_array($_FILES["file"]["type"], $allowedFileType)) {

    //         $targetPath = 'uploads/' . $_FILES['file']['name'];
    //         move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

    //         $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    //         $spreadSheet = $Reader->load($targetPath);
    //         $excelSheet = $spreadSheet->getActiveSheet();
    //         $spreadSheetAry = $excelSheet->toArray();
    //         $sheetCount = count($spreadSheetAry);

    //         for ($i = 0; $i <= $sheetCount; $i ++) {
    //             $name = "";
    //             if (isset($spreadSheetAry[$i][0])) {
    //                 $name =  $spreadSheetAry[$i][0];
    //             }
    //             $description = "";
    //             if (isset($spreadSheetAry[$i][1])) {
    //                 $description =  $spreadSheetAry[$i][1];
    //             }

    //             if (! empty($name) || ! empty($description)) {
    //                 // $query = "insert into tbl_info(name,description) values(?,?)";
    //                 // $paramType = "ss";
    //                 // $paramArray = array(
    //                 //     $name,
    //                 //     $description
    //                 // );
    //                 // $insertId = $db->insert($query, $paramType, $paramArray);
    //                 // $query = "insert into tbl_info(name,description) values('" . $name . "','" . $description . "')";
    //                 // $result = mysqli_query($conn, $query);
    //                 $insertId = 1;

    //                 if (! empty($insertId)) {
    //                     $type = "success";
    //                     $message = "Excel Data Imported into the Database";
    //                 } else {
    //                     $type = "error";
    //                     $message = "Problem in Importing Excel Data";
    //                 }
    //             }
    //         }
    //     } else {
    //         $type = "error";
    //         $message = "Invalid File Type. Upload Excel File.";
    //     }
    // }
    ?>

</body>

</html>