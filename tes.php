<?php
$mysqli = new mysqli("localhost","aomc_smite","Dqs0N4d9f##1jxBl","aomc_smite");

// Check connection
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  exit();
}
