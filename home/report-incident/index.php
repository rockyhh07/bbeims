<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "report-incident.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);
?>

<script type="module" src="./report-incident.js"></script>