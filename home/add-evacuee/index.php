<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "add-evacuee.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);


Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'add-modal',
    'title' => 'New Member',
    'buttons' => [
      [
        'id' => 'add',
        'value' => 'Save',
        'type' => 'success'
      ]
    ],
  ]
]);

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'delete-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Hide Member',
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

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'edit-modal',
    'title' => 'Edit Member',
    'success' => 'edit',
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
    'id' => 'add_evacuees-modal',
    'title' => 'Add this family to Evacuation Center',
    'success' => 'add_evacuees',
    'buttons' => [
      [
        'id' => 'add_evacuees',
        'value' => 'Add Evacuees',
        'type' => 'warning'
      ]
    ],
  ]
]);
?>

<script type="module" src="./add-evacuee.js"></script>