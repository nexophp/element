<?php 
$upload_success = [
    'field'=>$name,
    'method'=>"upload_success_".$name,
    'multiple'=>$multiple,
    'mime'=>$mime, 
    'thumb'=>$thumb, 
]; 
$upload_before = [
    'field'=>$name,
    'method'=>"upload_before_".$name,
    'multiple'=>$multiple,
    'mime'=>$mime,
    'size'=>$size,
    'thumb'=>$thumb, 
]; 
$upload_remove = [
    'field'=>$name,
    'method'=>"upload_remove_".$name,
    'multiple'=>$multiple,
    'mime'=>$mime,
    'thumb'=>$thumb, 
];
if($multiple){
  $vue->data_form($name,"[]");
} 
?>

<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <el-upload action="<?=$url?>" :before-upload="<?=$upload_before['method']?>"
        accept="<?= lib\Mime::get($upload_success['mime'])?>" :on-success="<?=$upload_success['method']?>"
        :show-file-list="false" <?=$attr_element?>>
        <el-button type="text" class="link hand">上传</el-button>
    </el-upload>
    <div style="display: flex; margin-top: 10px;">
        <?php if($v['sortable'] && $v['multiple']){?>
        <draggable v-if="<?=$model?>.<?=$name?>" style="display: flex;flex-wrap: wrap;" v-model="<?=$model?>.<?=$name?>"
            @start="drag=true" @end="drag=false">
            <?php }?>
            <?php 
        $show_image_thumb_field = $name;
        if($thumb){
          $show_image_thumb_field =  $name.'_thumb';
        }
        if($v['multiple']){
            //多文件显示

          ?>
            <div style="margin-right:5px;position: relative;" v-for="(v,k) in <?=$model?>.<?=$show_image_thumb_field?>">
                <div style="position: relative;">
                    <?php if($v['show_type'] == 'image'){ 
                ?>
                    <img :src="v" style="width:100px;height: 100px;">
                    <div @click="<?=$upload_remove['method']?>(k)" class="remove_link hand"
                        style="position: absolute;bottom: 0px;text-align: center;color: #FFF;width: 100%;font-size: 10px;">
                        删除</div>
                    <?php }else{?>
                    {{v}}
                    <div @click="<?=$upload_remove['method']?>(k)" class="remove_link hand"
                        style="position: absolute;bottom: 0px;text-align: center;color: #FFF;width: 100%;font-size: 10px;">
                        删除</div>
                    <?php }?>
                </div>
            </div>
            <?php }else{
            //单个文件显示
          ?>
            <div style="position: relative;" v-if="<?=$model?>.<?=$name?>">
                <?php if($v['show_type'] == 'image'){?>
                <img :src="<?=$model?>.<?=$show_image_thumb_field?>" style="width:100px;height: 100px;">
                <div @click="<?=$upload_remove['method']?>(0)" class="remove_link hand"
                    style="position: absolute;bottom: 0px;text-align: center;color: #FFF;width: 100%;font-size: 10px;">
                    删除</div>
                <?php }else{?>
                <span class="link hand" :title="<?=$model?>.<?=$name?>" v-if="get_ext(<?=$model?>.<?=$name?>) == 'pdf'"
                    @click="open_pdf(<?=$model?>.<?=$name?>)" >
                    <img @click="open_pdf(<?=$model?>.<?=$name?>)" src="/img/pdf.png" style="width:100px;height: 100px;">
                </span>

                <span class="link hand" :title="<?=$model?>.<?=$name?>" v-if="get_ext(<?=$model?>.<?=$name?>) == 'xls' || get_ext(<?=$model?>.<?=$name?>) == 'xlsx'"
                    @click="open_office(<?=$model?>.<?=$name?>)" >
                    <img @click="open_office(<?=$model?>.<?=$name?>)" src="/img/xls.png" style="width:100px;height: 100px;">
                </span>


                <span class="link hand" :title="<?=$model?>.<?=$name?>" v-if="get_ext(<?=$model?>.<?=$name?>) == 'doc' || get_ext(<?=$model?>.<?=$name?>) == 'docx'"
                    @click="open_office(<?=$model?>.<?=$name?>)" >
                    <img @click="open_office(<?=$model?>.<?=$name?>)" src="/img/doc.png" style="width:100px;height: 100px;">
                </span>

                <span class="link hand" :title="<?=$model?>.<?=$name?>" v-if="get_ext(<?=$model?>.<?=$name?>) == 'ppt' || get_ext(<?=$model?>.<?=$name?>) == 'pptx'"
                    @click="open_office(<?=$model?>.<?=$name?>)" >
                    <img @click="open_office(<?=$model?>.<?=$name?>)" src="/img/ppt.png" style="width:100px;height: 100px;">
                </span>


                <span class="link hand" :title="<?=$model?>.<?=$name?>"
                    v-else-if="get_ext(<?=$model?>.<?=$name?>) == 'png' ||get_ext(<?=$model?>.<?=$name?>) == 'jpg' || get_ext(form.<?=$name?>) == 'gif' || get_ext(<?=$model?>.<?=$name?>) == 'webp' || get_ext(<?=$model?>.<?=$name?>) == 'bmp' || get_ext(form.<?=$name?>) == 'jpeg'     ">
                    <img :src="<?=$model?>.<?=$name?>" style="width:100px;height: 100px;">
                </span>
 

                <span class="link hand" :title="<?=$model?>.<?=$name?>"
                    v-else-if="get_ext(<?=$model?>.<?=$name?>) == 'mp4' || get_ext(<?=$model?>.<?=$name?>) == 'webp'">
                    <video :src="form.<?=$name?>" style="width:100px;height: 100px;background: #000;"></video>
                </span> 
                <div @click="<?=$upload_remove['method']?>(0)" class="remove_link hand"
                    style="position: absolute;bottom: 0px;text-align: center;color: #FFF;width: 100%;font-size: 10px;">
                    删除</div>
                <?php }?>
            </div>
            <?php }?>
            <?php if($v['sortable'] && $v['multiple']){?>
        </draggable>
        <?php }?>
    </div>
    <?php  if($v['append']){?><?=$v['append']?><?php }?>
</el-form-item>


<?php    
$upload_size = $size?:5;
$vue->method($upload_before['method']."(file)","js:
    const isMaxSize = file.size / 1024 / 1024 < ".$upload_size.";
    if (!isMaxSize) {
        this.\$message.error('上传图片大小不能超过 ".$upload_size."MB!');
        return false
    }
    this.full_loading = this.\$loading({
      lock: true,
      text: '上传文件中',
      spinner: 'el-icon-loading',
      background: 'rgba(0, 0, 0, 0.7)'
    });
    return true;
");  
$string = ''; 
if($multiple){
    if($thumb){
        $string = "
        if(!_this.".$model.".".$name."_thumb){
          _this.".$model.".".$name."_thumb = [];
        }
        _this.".$model.".".$name."_thumb.push(res.data[0]);
        "; 
    }
    $string .= "_this.".$model.".".$name.".push(res.data[0]);"; 
}else{
    if($thumb){
      $string = "_this.".$model.".".$name."_thumb = res.data[0];"; 
    }
    $string .= "_this.".$model.".".$name." = res.data[0];"; 
}
$vue->method($upload_success['method']."(res,f,files)","js:
    _this.full_loading.close();
    if(res.code != 0){
      this.\$message.error(res.msg);
      return;
    }
    if(!_this.".$model.".".$name."){
      _this.".$model.".".$name."   = []; 
      _this.".$model.".".$name."_thumb   = []; 
    }  
    ".$string."      
    _this.\$forceUpdate();   
");  
  $string = '';
  if($multiple){
      if($thumb){
        $string = "_this.".$model.".".$name."_thumb.splice(k,1);"; 
      }
      $string .= "_this.".$model.".".$name.".splice(k,1);"; 
  }else{
      if($thumb){
        $string = "_this.".$model.".".$name."_thumb = '';"; 
      }
      $string .= "_this.".$model.".".$name." = '';"; 
  }
  $vue->method($upload_remove['method']."(k)","js:  
      ".$string." 
      _this.\$forceUpdate();   
  "); 
?>