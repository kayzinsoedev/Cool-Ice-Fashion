<div id="side_filter" class="panel panel-default">
  
    <?php foreach ($filter_groups as $filter_group) { ?>
    <div class="list-group">
      <div class="list-group-item item-header"><?php echo $filter_group['name']; ?></div>
      <div class="list-group-item">
        <div id="filter-group<?php echo $filter_group['filter_group_id']; ?>">
          <?php foreach ($filter_group['filter'] as $filter) { ?>
            <div class="checkbox-style" onmouseup="catchFilter();">
              <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
              <input type="checkbox" name="filter[]" id="check-<?= $filter['filter_id']; ?>" value="<?php echo $filter['filter_id']; ?>" checked="checked" />
              <?php }else{ ?>
              <input type="checkbox" name="filter[]" id="check-<?= $filter['filter_id']; ?>" value="<?php echo $filter['filter_id']; ?>" />
              <?php } ?> <label for="check-<?= $filter['filter_id']; ?>"><?php echo $filter['name']; ?></label>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  
</div>