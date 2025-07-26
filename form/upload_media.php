 

<el-form-item label="<?=$label?>" <?=$item_attr?>>    
    <?php  
    if($multiple){
        echo vue_upload_images($name,'form');
    }else{
        echo vue_upload_image($name,'form');
    }
    ?>
    <?php  if($v['append']){?><?=$v['append']?><?php }?>
</el-form-item>

 