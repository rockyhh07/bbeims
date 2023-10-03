<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::calamity_get_all($_POST)]);