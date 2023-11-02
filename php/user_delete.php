<?php
require_once "bbeims.php";
validate_POST($_POST);

echo json_encode(["result" => BBEIMS::user_delete($_POST)]);