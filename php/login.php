<?php
require_once "bbeims.php";

echo BBEIMS::login($_POST['username'], $_POST['password']);

