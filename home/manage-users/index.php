<?php
require_once "../../core/core.php";
Core::importComponent(['path' => '/components/header']);
require_once "manage-users.php";
Core::importComponent(['path' => '/components/notification']);
Core::importComponent(['path' => '/components/footer']);

Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'edit-user-modal',
    'title' => 'Edit User',
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
    'id' => 'delete-user-modal',
    'title' => '<img src="' . Core::base_url() . '/assets/img/sent.png"> Delete User',
    'buttons' => [
      [
        'id' => 'delete',
        'value' => 'Delete',
        'type' => 'danger'
      ]
    ],
  ]
]);



Core::importComponent([
  'path' => '/components/modal',
  'properties' => [
    'id' => 'reset-password-user-modal',
    'title' => 'Reset password',
    'buttons' => [
      [
        'id' => 'reset-password',
        'value' => 'Reset password',
        'type' => 'primary'
      ]
    ],
  ]
]);
?>

<script type="module" src="./manage-users.js"></script>