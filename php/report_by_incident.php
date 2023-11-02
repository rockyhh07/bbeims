<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::report_by_incident($_POST)]);