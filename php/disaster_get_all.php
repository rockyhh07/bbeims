<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::disaster_get_all($_POST)]);