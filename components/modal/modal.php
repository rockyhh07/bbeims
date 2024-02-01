<div class="modal fade" id="<?= $properties['id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $properties['title'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="<?= empty($properties['onClose']) ? '' : $properties['onClose'] ?>">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?= empty($properties['children']) ? '' : require_once "{$properties['children']}"; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="<?= empty($properties['onClose']) ? '' : $properties['onClose'] ?>" data-dismiss="modal">Close</button>
        <?php if (!empty($properties['success'])) { ?>
          <button type="button" class="btn btn-primary" onclick="<?= empty($properties['onSubmit']) ? '' : $properties['onSubmit'] ?>">
            <?= empty($properties['success']) ? 'Okay' :  $properties['success'] ?>
          </button>
        <?php } ?>
      </div>
    </div>
  </div>
</div>