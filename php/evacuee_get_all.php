<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::evacuee_get_all($_POST)]);