<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <?=$vue->editor($name)?>
</el-form-item>



<?php
$vue->editorMethod();
$vue->mounted('we', "
setTimeout(function(){
   app.weditor();
},500);
");

?>