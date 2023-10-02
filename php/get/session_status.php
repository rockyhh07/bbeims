<?php
require_once "../bbeims.php";

echo json_encode(["result" =>BBEIMS::session_status($_POST)]);

// echo json_encode([
//     "result"=> isset($_SESSION['user']) ? true : false, 
//     "data" => isset($_SESSION['user']) ? $_SESSION['user'] : null]
// );
