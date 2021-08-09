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
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); //ignore warning
        $spreadsheet = $reader->load("sample.xlsx");
        $d=$spreadsheet->getSheet(0)->toArray();
        $num=count($d);
        
        echo "Hello Piee";
        echo "\n";
        echo count($d);

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
        <form class="container">
            <div class="card">
                <div class="jumbotron text-center" >
                    <h2><span><img src="cart.png" alt="Cart Icon" width="75" height="75" style="margin-right: 25px;"></span>Export Booking Excel to Coprar Converter</h2>
                </div>

                <div class="card-body">
                    <!-- Receiver and Callsign Code-->
                    <div class="form-group">
                        <label for="reciever_code">Receiver Code:</label>
                        <input class="form-control" id="reciever_code" type="text" placeholder="Enter Receiver Code">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="callsign_code">Callsign Code:</label>
                        <input class="form-control" id="callsign_code" type="text" placeholder="Enter Callsign Code">
                    </div>
                    <br/>

                    <!-- Upload excel file -->
                    <!-- <div>Export booking excel file:</div>
                    <a href="sample.xlsx" download>sample.xlsx</a>
                    <div><button type="submit" class="btn btn-primary">Submit</button></div>
                </div> -->

                <form action="" method="post" name="frmExcelImport" id="frmExcelImport" enctype="multipart/form-data">
                    <div>
                        <label>Choose Excel File: </label> <input type="file" name="file" id="file" accept=".xls,.xlsx">
                        <button type="submit" id="submit" name="import" class="btn-submit">Import</button>
                    </div>
                </form>




            </div>
        </form>
    </div>
    <footer class="mastfoot mt-auto text-center">
        <div class="inner " style="padding-top: 20px; ">
            <p> Muhammad Syazwan | August 2021 | Source Code: <a href="https://github.com/syaz131/To-Coprar" target="_blank"><i class="fa fa-github"></i> To Coprar</a> | <a href="https://westports.github.io/ETP/" target="_blank">To Coprar JS Vers</a></p>
        </div>
    </footer>

    <!-- Upload Excel File  -->
    <?php
    // use Phppot\DataSource;
    // use PhpOffice\PhpSpreadsheet\Reader\Xlsx();

    // require_once 'DataSource.php';
    // $db = new DataSource();
    // $conn = $db->getConnection();
    require_once ('./vendor/autoload.php');

    if (isset($_POST["import"])) {

        $allowedFileType = [
            'application/vnd.ms-excel',
            'text/xls',
            'text/xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (in_array($_FILES["file"]["type"], $allowedFileType)) {

            $targetPath = 'uploads/' . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

            $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();

            $spreadSheet = $Reader->load($targetPath);
            $excelSheet = $spreadSheet->getActiveSheet();
            $spreadSheetAry = $excelSheet->toArray();
            $sheetCount = count($spreadSheetAry);

            for ($i = 0; $i <= $sheetCount; $i ++) {
                $name = "";
                if (isset($spreadSheetAry[$i][0])) {
                    $name =  $spreadSheetAry[$i][0];
                }
                $description = "";
                if (isset($spreadSheetAry[$i][1])) {
                    $description =  $spreadSheetAry[$i][1];
                }

                if (! empty($name) || ! empty($description)) {
                    // $query = "insert into tbl_info(name,description) values(?,?)";
                    // $paramType = "ss";
                    // $paramArray = array(
                    //     $name,
                    //     $description
                    // );
                    // $insertId = $db->insert($query, $paramType, $paramArray);
                    // $query = "insert into tbl_info(name,description) values('" . $name . "','" . $description . "')";
                    // $result = mysqli_query($conn, $query);
                    $insertId = 1;

                    if (! empty($insertId)) {
                        $type = "success";
                        $message = "Excel Data Imported into the Database";
                    } else {
                        $type = "error";
                        $message = "Problem in Importing Excel Data";
                    }
                }
            }
        } else {
            $type = "error";
            $message = "Invalid File Type. Upload Excel File.";
        }
    }
    ?>

</body>

</html>