<?php
require_once "bbeims.php";
validate_POST($_POST);
// echo json_encode($_POST);

echo json_encode(["result" => BBEIMS::calamity_new($_POST)]);