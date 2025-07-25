 

<el-form-item label="<?=$label?>" <?=$item_attr?>>   
    <div style="display: block;clear: both;" ></div>
    <?php  
    if($multiple){
        echo vue_upload_all($name,'form');
    }else{
        echo vue_upload_one($name,'form');
    }
    ?>
    <?php  if($v['append']){?><?=$v['append']?><?php }?>
</el-form-item>

 