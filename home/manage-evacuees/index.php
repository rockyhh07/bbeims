<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-evacuees.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);



Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'edit-modal',
    'title' => 'Edit Evacuee',
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
    'id' => 'archive-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Archive Evacuees',
    'buttons' => [
      [
        'id' => 'archive',
        'value' => '<i class="fas fa-archive"></i> Archive',
        'type' => 'danger'
      ]
    ],
  ]
]);

?>

<script type="module" src="./manage-evacuees.js"></script>