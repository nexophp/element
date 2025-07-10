<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <?php
    if($v['value'] && is_array($v['value'])) {
        foreach($v['value'] as $kk => $vv) {?>
    <el-radio v-model="<?=$model?>.<?=$name?>" label="<?=$vv['value']?>"><?=$vv['label']?></el-radio>
    <?php }
        }?>
</el-form-item>