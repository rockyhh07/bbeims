<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-evacuation-center.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'add-evac-center-modal',
    'title' => 'Add New Evacuation Center',
    'buttons' => [
      [
        'id' => 'add',
        'value' => 'Save',
        'type' => 'primary'
      ]
    ],
  ]
]);

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'edit-evac-center-modal',
    'title' => 'Edit Evacuation Center',
    'buttons' => [
      [
        'id' => 'edit',
        'value' => 'Save',
        'type' => 'success'
      ]
    ],
  ]
]);

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'delete-evac-center-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Hide Evacuation Center',
    'success' => 'Delete',
    'buttons' => [
      [
        'id' => 'delete',
        'value' => '<i class="fas fa-archive"></i> Archive',
        'type' => 'danger'
      ]
    ],
  ]
]);
?>

<script type="module" src="./manage-evacuation-center.js"></script>