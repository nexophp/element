<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <?=$vue->editor($name)?>
</el-form-item>



<?php
$vue->editorMethod();
$vue->mounted('editor_mounted', " 
if (document.querySelector('#".$name."weditor')) {
    this.open_editor();
}
");
$vue->method("open_editor()", "
    setTimeout(function(){
        if (typeof app !== 'undefined' && app.weditor) {
            app.weditor();
        } 
    }, 300);
");
?>