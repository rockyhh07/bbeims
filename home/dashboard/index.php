<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "dashboard.php";

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'generate-report-modal',
    'title' => 'Generate Report',
    'success' => 'Okay',
  ]
]);

Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);
?>

<script type="module" src="./dashboard.js"></script>