<?php
$qr = "";
ob_start();
QRCode::png($id,false,QR_ECLEVEL_L,4,2);
$qr = base64_encode(ob_get_clean());
header('Content-Type: text/html');

?>
<html>
<head>
    <style>
        @page {
            background-color: #0A263D;
            size: A5;
        }
        .background{
            background-color: #0A263D;
            text-align: center;
        }
        .text-head{
            color:#F2E57F;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .qr{
            width: 50%;
            border: 5px solid #F2E57F;
            border-radius: 30px;
            padding: 10px;
            background-color: #fff;
        }
        .scan{
            width: 100px;
            text-align: center;
            margin-top: 5px;
            margin-left: auto;
            margin-right: auto;
            padding: 3px 20px;
            border-radius: 30px;
            background: rgb(144,91,9);
            font-weight: bold;
            font-size: 14pt;
            background: linear-gradient(90deg, rgba(144,91,9,1) 0%, rgba(241,214,123,1) 35%, rgba(246,230,122,1) 100%);
        }
    </style>
</head>

<body class="background">
    <h1 class="text-head"><?=$sponsor;?></h1>
    <img class="qr" src="data:image/png;base64,<?=$qr;?>" />
    <div class="scan">
        <span>SCAN ME</span>
    </div>
    <p style="color: #fff;font-size:12pt">
        Silakan isi kehadiran melalui halaman yang muncul setelah melakukan scan QR Code
    </p>
</body>

</html>