<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::dashboard_get($_POST)]);