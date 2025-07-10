<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-input class="table_input" <?=$attr_element?> v-model="<?=$model ?: 'form'?>.<?=$name?>"></el-input>
</el-form-item>