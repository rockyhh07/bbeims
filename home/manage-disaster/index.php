<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-disaster.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);



Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'add-disaster-modal',
    'title' => 'Add Disaster',
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
    'id' => 'edit-disaster-modal',
    'title' => 'Edit Disaster',
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
    'id' => 'delete-disaster-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Hide Disaster',
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

<script type="module" src="./manage-disaster.js"></script>