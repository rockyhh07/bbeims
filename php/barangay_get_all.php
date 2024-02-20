<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::barangay_get_all($_POST)]);