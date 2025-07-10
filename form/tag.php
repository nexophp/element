<el-form-item label="<?=$label?>" <?=$item_attr?> style="position: relative;">
    <span class="hand link" @click="push_tag('<?=$name?>')">添加</span>
    <div v-for="(v,index) in <?=$model?>.<?=$name?>">
        <el-input v-model="<?=$model?>.<?=$name?>[index]" style="width:200px;" class="mb10"></el-input>
        <span @click="del_tag('<?=$name?>',index)" class="hand link">删除</span>
    </div>
    <?php  if($v['append']) {?><?=$v['append']?><?php }?>
</el-form-item>
<?php
$vue->data_form($name, "js:['']");
$vue->method("push_tag(field)", "js: 
	this.".$model."[field].push('');
");
$vue->method("del_tag(field,index)", "js: 
	this.".$model."[field].splice(index,1);
");
?>