<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::user_get_all($_POST)]);