<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::incident_get_all($_POST)]);