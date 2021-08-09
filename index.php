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
        
        echo "Hello Piee";
        echo "\n";
        echo count($d); 
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
                    <div>Export booking excel file:</div>
                    <a href="sample.xlsx" download>sample.xlsx</a>
                    <div><button type="submit" class="btn btn-primary">Submit</button></div>
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