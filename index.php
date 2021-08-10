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
        
        <!-- Declare Variables -->
        <?php
            //http://localhost/3000/
            //ssh -R 80:localhost:3000 plan@localhost.run
            //https://68f204ac146f5a.localhost.run/
            
            require 'vendor/autoload.php';
            use PhpOffice\PhpSpreadsheet\Spreadsheet;
            use PhpOffice\PhpSpreadsheet\Writer\Xlsx; 
            $receiver_code = "RECEIVER";
            $callsign_code = "CALLSIGN";
            $file = $file_output = $allRows = $spreadsheet = "";
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
            if (isset($_SERVER["REQUEST_METHOD"]) && ($_SERVER["REQUEST_METHOD"] == "POST")) {
                $receiver_code = test_input($_POST["receiver_code"]);
                $callsign_code = test_input($_POST["callsign_code"]);
                
                // $file = $_FILES["file"]["name"];
                $file = $_FILES["file"]["tmp_name"];
                // echo "</br>--------</br>";
                // echo $file;
                // echo "</br>--------</br>";
            }

            if ($file != ""){
                $spreadsheet = $reader->load($file);
                // $spreadsheet = $reader->load("sample.xlsx");
                // echo "</br>--------</br>";
                // echo isset($spreadsheet) ;
                // echo "</br>--------</br>";
                $allRows=$spreadsheet->getSheet(0)->toArray();
            }            
        ?>

        <!-- Create Coprar Edi -->
        <?php
            $line = $contcount = 0;
            $refno = get_date_str("");
            $edi = "UNB+UNOA:2+KMT+". $receiver_code. "+". get_date_str("daterawonly"). ":". get_date_str("timetominrawonly"). "+". $refno. "'\n";
            $edi .= "UNH+". $refno. "+COPRAR:D:00B:UN:SMDG21+LOADINGCOPRAR'\n";
            $line++;
            
            if ($file != ""){
                //Process Header
                $report_dt = $voyage = $vslname = $callsign = $opr = "";
                $numRow = count($allRows);

                for ($singleRow=0; $singleRow<$numRow; $singleRow++){
                    if ($singleRow > 6) break;
                    $rowCells = $allRows[$singleRow];
                    
                    if ($singleRow == 1) {
                        $tmpdt = explode("/", $rowCells[1]);
                        $day = $tmpdt[0];
                        $month = $tmpdt[1];
                        $tmpyear = explode(" ", $tmpdt[2]) ;
                        $report_date = $tmpyear[0]. "-". $month. "-". $day. " ". $tmpyear[1];
                        $report_dt = get_date_str($report_date, "");
                    }
                    if ($singleRow == 3) {
                        if (isset($rowCells[3])) {
                            $tmp = explode("/", $rowCells[3]);
                            $voyage = $tmp[0];
                            $callsign = $tmp[1];
                            $opr = $tmp[2];
                            $vslname = $rowCells[1];
                        }
                    }
                }
                $edi .= "BGM+45+". $report_dt. "+5'\n";
                $line++;
                $edi .= "TDT+20+". $voyage. "+1++172:". $opr. "+++". $callsign_code. ":103::". $vslname. "'\n";
                $line++;
                $edi .= "RFF+VON:". $voyage. "'\n";
                $line++;
                $edi .= "NAD+CA+". $opr. "'\n";
                $line++;

                $tmp = $dim = "";
                for ($singleRow=0; $singleRow<$numRow; $singleRow++){
                    if (isset($allRows[$singleRow])) {
                        $rowCells = $allRows[$singleRow];

                        if ($singleRow > 7) {
                            $contcount++;
                            
                            $fe ="5";
                            if (isset($rowCells[3]) && $rowCells[3] =="E")
                                $fe = "4";
                            
                            $type = "2";
                            if (isset($rowCells[11]) && $rowCells[11] =="Y")
                                $type = "6";

                            if (isset($rowCells[1]) && isset($rowCells[7])) {
                                $edi .= "EQD+CN+". $rowCells[1]. "+". $rowCells[7]. ":102:5++". $type. "+". $fe. "'\n";
                                $line++;
                            }
                            
                            //might be rowCells[5]
                            if ($rowCells[6]) {
                                $edi .= "LOC+11+". $rowCells[5]. ":139:6'\n";
                                $line++;
                            }
                            if ($rowCells[6]) {
                                $edi .= "LOC+7+". $rowCells[6]. ":139:6'\n";
                                $line++;
                            }
                            if ($rowCells[19]) {
                                $edi .= "LOC+9+". $rowCells[19]. ":139:6'\n";
                                $line++;
                            }
                            if ($rowCells[13]) {
                                $edi .= "MEA+AAE+VGM+KGM:". $rowCells[13]. "'\n";
                                $line++;
                            }

                            if (isset($rowCells[17]) && trim($rowCells[17]) != "" && trim($rowCells[17]) != "/") {
                                $tmp = explode(",", $rowCells[17]);
                                for ($i = 0; $i < count($tmp); $i++) {
                                    $dim = explode(",", $rowCells[17]);
                                    if (trim($dim[0]) == "OF") {
                                        $edi .= "DIM+5+CMT:". trim($dim[1]). "'\n";
                                        $line++;
                                    }
                                    if (trim($dim[0]) == "OB") {
                                        $edi .= "DIM+6+CMT:". trim($dim[1]). "'\n";
                                        $line++;
                                    }
                                    if (trim($dim[0]) == "OR") {
                                        $edi .= "DIM+7+CMT::". trim($dim[1]). "'\n";
                                        $line++;
                                    }
                                    if (trim($dim[0]) == "OL") {
                                        $edi .= "DIM+8+CMT::". trim($dim[1]). "'\n";
                                        $line++;
                                    }
                                    //bug DIM+9+CMT:::20' Not Found
                                    if (trim($dim[0]) == "OH") {
                                        $edi .= "DIM+9+CMT:::". trim($dim[1]). "'\n";
                                        $line++;
                                    }
                                }
                            }

                            if (isset($rowCells[15]) && trim($rowCells[15]) != "" && trim($rowCells[15]) != "/") {
                                $temperature = $rowCells[15];
                                $temperature = str_replace(" ", "", $temperature);
                                $temperature = str_replace("C", "", $temperature);
                                $temperature = str_replace("+", "", $temperature);
                                $edi .= "TMP+2+".  $temperature.  ":CEL'\n";
                                $line++;
                            }
                            if (isset($rowCells[25]) && trim($rowCells[25]) != "" && trim($rowCells[25]) != "/") {
                                $tmp = explode(",", $rowCells[25]);
                                if ($tmp[0] == "L") {
                                    $edi .= "SEL+". $tmp[1]. "+CA'\n";
                                    $line++; //seal L - CA, S - SH, M - CU
                                }
                                if ($tmp[0] == "S") {
                                    $edi .= "SEL+". $tmp[1]. "+SH'\n";
                                    $line++; //seal L - CA, S - SH, M - CU
                                }
                                if ($tmp[0] == "M") {
                                    $edi .= "SEL+". $tmp[1]. "+CU'\n";
                                    $line++; //seal L - CA, S - SH, M - CU
                                }
                            }

                            if (isset($rowCells[8])) {
                                $edi .= "FTX+AAI+++".  $rowCells[8].  "'\n";
                                $line++;
                            }
                            if (isset($rowCells[12]) && trim($rowCells[12]) != "" && trim($rowCells[12]) != "/") {
                                $edi .= "FTX+AAA+++".  trim(cleanString($rowCells[12])).   "'\n";
                                $line++;
                            }
                            if (isset($rowCells[18]) && trim($rowCells[18]) != "" && trim($rowCells[18]) != "/") {
                                $edi .= "FTX+HAN++".  $rowCells[18].  "'\n";
                                $line++;
                            }
                            if (isset($rowCells[14]) && $rowCells[14] != "" && trim($rowCells[14]) != "/") {
                                $tmp = explode("/",$rowCells[14]);
                                $edi .= "DGS+IMD+".  $tmp[0].  "+".  $tmp[1].  "'\n";
                                $line++;
                            }
                            if (isset($rowCells[2]) && trim($rowCells[2]) != "") {
                                $edi .= "NAD+CF+".  $rowCells[2].  ":160:ZZZ'\n";
                                $line++;
                            }
                            
                        }
                    }
                }
                $contcount--;
                $edi .= "CNT+16:". $contcount. "'\n";
                $line++;
                $line++;
                $edi .= "UNT+". $line. "+". $refno."'\n";
                $edi .= "UNZ+1+". $refno. "'";
            }
            
            //Final Output
            if ($line <= 1) //have only header
                $file_output = "*Please Select Excel File";
            else
                $file_output = $edi;

            // Functions
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

            function test_input($data) {
                $data = trim($data); //removes whitespace and other predefined characters from both sides of a string
                $data = stripslashes($data); //removes backslashes added by the addslashes() function
                $data = htmlspecialchars($data); //converts some predefined characters to HTML entities
                return $data;
            }

            function cleanString($input) {
                $output = "";
                for ($i = 0; $i < strlen($input); $i++) {
                    if (ord($input) <= 127) {
                        $output .= $input[$i];
                    }
                }
                return $output;
            }
        ?>

        <div class="container">
            <br/>
            <form role="form" class="container" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <div class="card">
                    <div class="jumbotron text-center" >
                        <h2><span><img src="cart.png" alt="Cart Icon" width="75" height="75" style="margin-right: 25px;"></span>Export Booking Excel to Coprar Converter</h2>
                    </div>

                    <div class="card-body">
                        <!-- Receiver and Callsign Code-->
                        <div class="form-group">
                            <label for="receiver_code">Receiver Code:</label>
                            <input class="form-control" id="receiver_code" name="receiver_code" type="text" placeholder="Enter Receiver Code" value="<?php echo $receiver_code;?>">
                        </div>
                        <br/>
                        <div class="form-group">
                            <label for="callsign_code">Callsign Code:</label>
                            <input class="form-control" id="callsign_code"  name="callsign_code" type="text" placeholder="Enter Callsign Code" value="<?php echo $callsign_code;?>">
                        </div>
                        <br/>

                        <!-- Upload excel file -->
                        <div>
                            <label>Choose Excel File: </label></br><input type="file" name="file" id="file" accept=".xls,.xlsx"></br></br>
                            <button type="submit" id="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                        </br></br>
                        <label for="file_output">COPRAR EDI Result:</label>
                        <div class="form-group"><textarea class="form-control" rows="12" cols="20" id='file_output' name="file_output"><?php echo $file_output ; ?></textarea></div>
                    </div>
                </div>
            </form>
        </div>
        <footer class="mastfoot mt-auto text-center">
            <div class="inner " style="padding-top: 20px; ">
                <p> Muhammad Syazwan | August 2021 | Source Code: <a href="https://github.com/syaz131/To-Coprar" target="_blank"><i class="fa fa-github"></i> To Coprar</a> | <a href="https://westports.github.io/ETP/" target="_blank">To Coprar JS Vers</a></p>
            </div>
        </footer>
    </body>

</html>