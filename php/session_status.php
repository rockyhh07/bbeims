<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::session_status($_POST)]);