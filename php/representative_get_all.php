<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::representative_get_all($_POST)]);