<?php 
$select_name ='spec_type';
if(strpos($name,',') !== false){
    $arr = explode(",",$name);
    $select_name = $arr[0];
    $name = $arr[1];
} 
$is_image = false;
if($attr && is_array($attr) && in_array('image',$attr)){
    $is_image = true;
}
$is_stock = false;
if($attr && is_array($attr) && in_array('stock',$attr)){
    $is_stock = true;
}
$is_status = false;
if($attr && is_array($attr) && in_array('status',$attr)){
    $is_status = true;
}
?>
<el-form-item label="<?=$label?>"> 
    <div style="display: flex;margin-top: 15px;"> 
        <el-radio v-model="<?=$model?>.<?=$select_name?>" label="1">单规格</el-radio>
        <el-radio style="margin-left: 10px;" v-model="<?=$model?>.<?=$select_name?>" label="2">多规格</el-radio>
    </div>
    <div v-if="<?=$model?>.<?=$select_name?> == 1">
        <el-form label-position="top" @submit.native.prevent label-width="180px" style="padding-right:20px;">
            <el-form-item label="划线价" required>
                <el-input style="width:200px" v-model="<?=$model?>.price_mart" type="number"></el-input>
            </el-form-item>
            <el-form-item label="销售价" required>
                <el-input style="width:200px" v-model="<?=$model?>.price" type="number"></el-input>
            </el-form-item>
            <?php if($is_stock){?>
            <el-form-item label="库存" required>
                <el-input style="width:200px" v-model="<?=$model?>.stock" type="number"></el-input>
            </el-form-item>
            <?php }?>
        </el-form>
    </div>

    <table v-if="<?=$model?>.<?=$select_name?> == 2" style="width:100%;" class="pure-table pure-table-bordered">
        <thead>
            <tr>
                <th>规格名(<span @click="push_spec('<?=$name?>')" class="hand link">+</span>)</th>
                <?php if($is_image){?><th>图片</th><?php }?>
                <th>价格</th>
                <?php if($is_stock){?><th>库存</th><?php }?>
                <?php if($is_status){?><th style="width:50px;">状态</th><?php }?>
                <th style="width:50px;">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(v,index) in <?=$model?>.<?=$name?>">
                <td>
                    <el-input style="width:200px;" size="small" v-model="<?=$model?>.<?=$name?>[index].spec_name">
                    </el-input>
                </td>
                <?php if($is_image){?>
                <td>
                    <div style="display:flex;align-items: center;">
                        <el-image style="width: 50px; height:50px" v-if="<?=$model?>.<?=$name?>[index].img"
                            :src="<?=$model?>.<?=$name?>[index].img"></el-image>
                        <a v-if="<?=$model?>.<?=$name?>[index].img" href="javascript:void(0);" class="hand link ml10"
                            @click="upload_spec('<?=$name?>',index)" title="替换">替换</a>
                        <a v-else href="javascript:void(0);" class="hand link ml10"
                            @click="upload_spec('<?=$name?>',index)" title="上传">上传</a>
                    </div>
                </td>
                <?php }?>
                <td>
                    <el-input style="width:200px;" size="small" type="number"
                        v-model="<?=$model?>.<?=$name?>[index].price"></el-input>
                </td>
                <?php if($is_stock){?>
                <td>
                    <el-input style="width:200px;" size="small" type="number"
                        v-model="<?=$model?>.<?=$name?>[index].stock" style="width: 100px;"></el-input>
                </td>
                <?php }?>
                <?php if($is_status){?>
                <td>
                    <el-switch size="small" v-model="<?=$model?>.<?=$name?>[index].status" active-value="1"
                        inactive-value="-1" active-color="#13ce66" inactive-color="#ff4949">
                    </el-switch>
                </td>
                <?php }?>
                <td>
                    <el-button type="danger" size="small" @click="del_spec('<?=$name?>',index)" icon="el-icon-delete"
                        circle></el-button>
                </td>
            </tr>
        </tbody>
    </table>
</el-form-item>
<?php  
$vue->data_form($name,"[
    {spec_name:'',price:'',stock:'',status:'1'}, 
]");
$vue->method("push_spec(field)"," 
    this.".$model."[field].push({spec_name:'',price:'',stock:'',status:'1'});
");
$vue->method("del_spec(field,index)","
    this.".$model."[field].splice(index,1);
");  
$vue->data("upload_spec_index",'');
$vue->data("upload_spec_field",'');
$vue->method("upload_spec(field,index)"," 
    app.upload_spec_field = field;
    app.upload_spec_index = index;
".$js); 
?>
