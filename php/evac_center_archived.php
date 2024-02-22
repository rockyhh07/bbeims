<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::evac_center_archived($_POST)]);