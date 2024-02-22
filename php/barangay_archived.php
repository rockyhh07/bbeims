<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::barangay_archived($_POST)]);