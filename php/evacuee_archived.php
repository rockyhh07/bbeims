<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::evacuee_archived($_POST)]);