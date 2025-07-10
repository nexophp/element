<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <table style="width:100%;" class=" pure-table pure-table-bordered">
        <thead>
            <tr>
                <th style="width:180px;">属性名 （<span @click="form_push_attr('<?=$name?>')" class="hand link"
                        style="font-size: 16px;">添加</span>）</th>
                <th>属性值</th>
                <th style="width:50px;">操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(v,index) in <?=$model?:'form'?>.<?=$name?>">
                <td>
                    <el-input placeholder="属性名如：温度" v-model="<?=$model?:'form'?>.<?=$name?>[index].name"></el-input>
                </td>
                <td>
                    <el-input placeholder="多值用,、或空格分隔如：标准冰、少冰、去冰、温、热"
                        v-model="<?=$model?:'form'?>.<?=$name?>[index].values"></el-input>
                </td>
                <td>
                    <el-button type="danger" size="small" @click="form_del_attr('<?=$name?>',index)"
                        icon="el-icon-delete" circle></el-button>
                </td>
            </tr>

        </tbody>
    </table>
</el-form-item>
<?php 
$default = "[
	{name:'',values:''}, 
]";
$vue->data_form($name,"");
 
$vue->method("form_push_attr(field)","
	if(!this.".$model."[field]){
		this.".$model."[field] = ".$default.";
	} 
	this.".$model."[field].push({name:'',values:''});
	this.\$forceUpdate();
");
$vue->method("form_del_attr(field,index)","
	this.".$model."[field].splice(index,1);
");
$vue->method("load_attr()","
	this.\$set(this.".$model.",'".$name."',".$default."); 
");
?>