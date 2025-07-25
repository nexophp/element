<?php 
 
if($multiple){
  $vue->data_form($name,"[]");
} 
?>

<el-form-item label="<?=$label?>" <?=$item_attr?>> 
    <div style="display: block;clear: both;" >
         <?=vue_upload_files($name, 'form', $mime)?>
    </div>
    <?php  if($v['append']){?><?=$v['append']?><?php }?>
</el-form-item>
