<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <?=$vue->editor($name)?>
</el-form-item>

<?php
$vue->editor_method();
$vue->mounted('we', "
setTimeout(function(){
   app.weditor();
},500);
");

?>