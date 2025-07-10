<?php
if($v['index']) {
    $cascader_i = $v['index'];
} else {
    if(!$cascader_i) {
        $cascader_i = 1;
    } else {
        $cascader_i++;
    }
}
$cascader_option = "cascader_option_".$cascader_i;
$cascader_change = "cascader_change_".$cascader_i;
$cascader_ajax = "cascader_ajax_".$cascader_i;
?>
<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-cascader style="width:100%;" @change="<?=$cascader_change?>" v-model="<?=$model ?: 'form'?>.<?=$name?>"
        :options="<?=$cascader_option?>" <?=$attr_element?>></el-cascader>
</el-form-item>
<?php
if($v['value']) {
    $vue->data($cascader_option, json_encode($v['value']));
} else {
    $vue->data($cascader_option, "[]");
}
if($url) {
    $vue->method($cascader_ajax."()", "
    ajax('".$url."',{},function(res){
      _this.".$cascader_option."  = res.data;
    });    
  ");
    $vue->created([$cascader_ajax."()"]);
}
$cascader_last_field = $v['field2'] ?: 'pid';
$vue->method($cascader_change."(value)", "
  let len = value.length;
  console.log(value);
  if(len > 0){ 
    this.\$set(this.".$model.",'level',len);  
    this.\$set(this.".$model.",'".$cascader_last_field."',value[len-1]);  
  }else{
    this.\$set(this.".$model.",'".$cascader_last_field."',0);
    this.\$set(this.".$model.",'level',0);  
  } 
");
