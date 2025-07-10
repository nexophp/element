<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-date-picker v-model="<?=$model?>.<?=$name?>" type="date" value-format="yyyy-MM-dd" <?=$attr_element?>>
    </el-date-picker>
</el-form-item>