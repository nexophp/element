<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-select class="table_input" v-model="<?=$model?>.<?=$name?>" <?=$attr_element?>>
        <?php
    if($v['value'] && is_array($v['value'])) {
        foreach($v['value'] as $kk => $vv) {?>
        <el-option label="<?=$vv['label']?>" value="<?=$vv['value']?>"></el-option>
        <?php }
        }?>
        <?php if($v['inner']) {
            echo $v['inner'];
        }?>
    </el-select>
</el-form-item>