<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::representative_archived($_POST)]);