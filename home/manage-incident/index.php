<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-incident.php";
require_once "manage-incident.php";
require_once "manage-incident.php";
require_once "manage-incident.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);



Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'add-incident-modal',
    'title' => 'Add Incident',
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
    'id' => 'edit-incident-modal',
    'title' => 'Edit Incident',
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
    'id' => 'delete-incident-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Delete Incident',
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

<script type="module" src="./manage-incident.js"></script>