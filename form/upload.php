<?php

if ($multiple) {
  $vue->data_form($name, "[]");
}
?>

<el-form-item label="<?= $label ?>" <?= $item_attr ?>>
  <div style="display: block;clear: both;">
    <?php
    vue_upload_files($name, 'form', $mime, $num ?: 1);
    ?>
  </div>
  <?php if ($v['append']) { ?><?= $v['append'] ?><?php } ?>
</el-form-item>