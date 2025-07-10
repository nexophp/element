<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-autocomplete class="table_input" v-model="<?=$model?>.<?=$name?>"
        :fetch-suggestions="autocomplete_suggestions<?=$name?>" @select="autocomplete_select<?=$name?>($event)"
        <?=$attr_element?>></el-autocomplete>
</el-form-item>

<?php 
$vue->method("autocomplete_suggestions".$name."(queryString, cb)","js:   
  ajax('".$url."',{q:queryString},function(res){
      cb(res);
  });
"); 
$vue->method("autocomplete_select".$name."(item)","js: 
    this.".$model.".".$name." = item.value;
    this.".$model.".".$name."_val = item.id; 
    this.\$forceUpdate();
");

?>