<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-cascader class="table_input" v-model="<?=$model?:'form'?>.<?=$name?>" :options="address_cascader">
    </el-cascader>
    <el-input class="table_input mt10" v-model="<?=$model?:'form'?>.<?=$name?>_detail" placeholder="详细地址">
    </el-input>
</el-form-item>

<?php 
$vue->data("address_cascader",json_encode(\element\form::get_city()));
?>