<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-barangay.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);



Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'add-barangay-modal',
    'title' => 'Add barangay',
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
    'id' => 'edit-barangay-modal',
    'title' => 'Edit barangay',
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
    'id' => 'delete-barangay-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Delete barangay',
    'success' => 'Delete',
    'buttons' => [
      [
        'id' => 'delete',
        'value' => 'Delete',
        'type' => 'danger'
      ]
    ],
  ]
]);
?>

<script type="module" src="./manage-barangay.js"></script>