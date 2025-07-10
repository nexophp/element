<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-time-select v-model="<?=$model?>.<?=$name?>" type="time" value-format="HH:mm" <?=$attr_element?>>
    </el-time-select>
</el-form-item>