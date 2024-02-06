
<div class="modal fade" id="<?= $properties['id']; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $properties['title'] ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="<?= empty($properties['onClose']) ? '' : $properties['onClose'] ?>">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="<?= $properties['id']; ?>-body">
        <?php
        if (empty($properties['inline'])) {
          if (!empty($properties['children'])) require_once "{$properties['children']}";
          else echo '';
        } else {
          echo $properties['children'];
        }
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="<?= $properties['id'] ?>-close" data-dismiss="modal">Close</button>
        <?php
        if (!empty($properties['buttons'])) {
          foreach ($properties['buttons'] as $btn) { ?>
            <button id="<?= $properties['id'] ?>-btn-<?= $btn['id'] ?>" class="btn btn-<?= $btn['type'] ?>"><?= $btn['value'] ?></button>
        <?php }
        }
        ?>
        <button type="button" style="display: none;" id="<?= $properties['id'] ?>-hide" data-dismiss="modal"></button>
      </div>
    </div>
  </div>
</div>
