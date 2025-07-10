<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <?php
    if($v['value'] && is_array($v['value'])) {
        ?>
    <el-checkbox-group v-model="<?=$model?>.<?=$name?>">
        <?php
            foreach($v['value'] as $kk => $vv) {
        ?>
        <el-checkbox border label="<?=$vv['value']?>"><?=$vv['label']?></el-checkbox>
        <?php }?>
    </el-checkbox-group>
    <?php }?>
</el-form-item> 
<?php 
$vue->data_form($name, "[]"); 
?>