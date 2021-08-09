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
        //http://localhost/To-Coprar/
        //http://localhost/8000/
        $a = 10;
        $b = 20;
        $result = "Have two values:";
        echo "hello piee";

        // $file = fopen("sample.xlsx","r");
        // print_r(fgetcsv($file));
        // fclose($file);
    ?>

    <div class="container">
        <br/>
        <form class="container">
            <div class="card" style="">
                <div class="jumbotron">
                    <h3><span><img src="cart.png" alt="Cart Icon" width="80" height="80" style="margin-right: 20px;"></span>Export Booking Excel to Coprar Converter</h3>
                </div>

                <div class="card-body">
                    <!-- Receiver and Callsign Code-->
                    <div class="form-group">
                        <label for="reciever_code">Receiver Code:</label>
                        <input class="form-control" id="reciever_code" type="text" placeholder="Enter Receiver Code">
                    </div>
                    <div class="form-group">
                        <label for="callsign_code">Callsign Code:</label>
                        <input class="form-control" id="callsign_code" type="text" placeholder="Enter Callsign Code">
                    </div>

                    <!-- Upload excel file -->
                    <div>Export booking excel file:</div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <footer class="mastfoot mt-auto text-center">
        <div class="inner " style="padding-top: 20px; ">
            <p> Muhammad Syazwan | August 2021 | Source Code : <a href="https://github.com/syaz131/To-Coprar" target="_blank"><i class="fa  fa-github"></i></a></p>
        </div>
    </footer>

</body>

</html>