<?php
require_once "bbeims.php";

echo json_encode(["result" => BBEIMS::house_member_get($_POST)]);