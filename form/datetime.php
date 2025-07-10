<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-date-picker v-model="<?=$model?>.<?=$name?>" type="datetime" value-format="yyyy-MM-dd HH:mm:ss"
        <?=$attr_element?>>
    </el-date-picker>
</el-form-item>