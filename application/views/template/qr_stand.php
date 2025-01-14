<?php
$qr = "";
ob_start();
QRCode::png($qrLink,false,QR_ECLEVEL_L,4,2);
$qr = base64_encode(ob_get_clean());
header('Content-Type: text/html');

?>
<html>
<head>
    <style>
        @page {
            background-color: #6f42c1;
            size: A5;
        }
        .background{
            background-color: #6f42c1;
            text-align: center;
        }
        .text-stand{
            font-family: Dejavu Sans;
            color:#F2E57F;
            padding:0px;
            line-height:10px;
            margin:30px 0px 10px 0px;
            text-decoration: underline;
        }
        .text-head{
            color:#F2E57F;
            font-family: Dejavu Sans;
            margin-bottom: 50px;
            padding: 0px;
            margin-top: 0px;
            line-height: 30px;
        }
        .qr{
            width: 60%;
            border: 5px solid #F2E57F;
            border-radius: 30px;
            background-color: #fff;
            margin: 0px;
        }
        .scan{
            width: 150px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            margin-top: 0px;
            padding: 8px 40px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 14pt;
            background: #F2E57F;
            /* background: linear-gradient(90deg, rgba(144,91,9,1) 0%, rgba(241,214,123,1) 35%, rgba(246,230,122,1) 100%); */
        }
        .container{
            padding: 10px;
        }
      	.header{
        	text-align:center;  
          	background:#F2E57F;
          	padding:5px;
      	}
      	.header img{
          height:30px;
          margin-top:5px;
      	}
      	.header span{
          	padding:10px;
        	color:black;
          	font-size:20px;
            font-family: Arial;
          	font-weight:bold;
      	}
    </style>
</head>

<body class="background">
  		<div class="header">
    		<img src="<?=$logoUrl;?>" />
    		<span>
      			<?=$siteTitle;?>
    		</span>
	  </div>

    <div class="container">
        <h2 class="text-stand">STAND</h2>
        <h1 class="text-head"><?=$sponsor;?></h1>
        <img class="qr" src="data:image/png;base64,<?=$qr;?>" />
        <div class="scan">SCAN ME</div>
        <p style="color: #fff;font-size:12pt">
            Silakan isi kehadiran melalui halaman yang muncul setelah melakukan scan QR Code
        </p>
    </div>
</body>

</html>