<script src="https://cdn.jsdelivr.net/npm/jspdf@latest/dist/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "dashboard.php";

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'generate-report-modal',
    'title' => 'Generate Report',
    'buttons' => [
      [
        'id' => 'generate',
        'value' => 'Generate',
        'type' => 'primary'
      ]
    ],
  ]
]);

Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);
?>

<script src="<?= Core::base_url() ?>/assets/js/chart.js"></script>
<script type="module" src="./dashboard.js"></script>