<?php
sleep(5);
file_put_contents("./tes.json",json_encode(['data'=>$_REQUEST,'time'=>date("Y-m-d H:i:s")]));