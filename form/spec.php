<?php
$select_name = 'spec_type';
if (strpos($name, ',') !== false) {
    $arr = explode(",", $name);
    $select_name = $arr[0];
    $name = $arr[1];
}
$is_image = false;
if ($attr && is_array($attr) && in_array('image', $attr)) {
    $is_image = true;
}
$is_stock = false;
if ($attr && is_array($attr) && in_array('stock', $attr)) {
    $is_stock = true;
}
$is_status = false;
if ($attr && is_array($attr) && in_array('status', $attr)) {
    $is_status = true;
} 
?>
<el-form-item label="<?= $label ?>">
    <div style="display: flex;margin-top: 15px;">
        <el-radio @change="spec_change()" v-model="<?= $model ?>.<?= $select_name ?>" label="1"><?= lang('单规格') ?></el-radio>
        <el-radio @change="spec_change()" style="margin-left: 10px;" v-model="<?= $model ?>.<?= $select_name ?>" label="2"><?= lang('多规格') ?></el-radio>
    </div>
    <div v-if="<?= $model ?>.<?= $select_name ?> == 1" class="mt-3">
        <el-form label-position="left" @submit.native.prevent label-width="180px" style="padding-right:20px;">
            <el-form-item label="<?= lang('SKU') ?>" required>
                <el-input style="width:200px" v-model="<?= $model ?>.sku" type="number"></el-input>
            </el-form-item> 
            <el-form-item label="<?= lang('市场市') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.price_mart" type="number"></el-input>
            </el-form-item>
            <el-form-item label="<?= lang('售价') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.price" type="number"></el-input>
            </el-form-item> 
            <el-form-item label="<?= lang('库存') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.stock" type="number"></el-input>
            </el-form-item> 
            
        </el-form>
    </div>

    <table v-if="<?= $model ?>.<?= $select_name ?> == 2" style="width:100%;" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th><?= lang('规格名') ?><span @click="push_spec()" class="ms-2 hand link bi bi-plus-circle"></span></th>
                <th><?= lang('SKU') ?></th>
                <?php if ($is_image) { ?><th><?= lang('图片') ?></th><?php } ?>
                <th><?= lang('价格') ?></th>
                <?php if ($is_stock) { ?><th><?= lang('库存') ?></th><?php } ?>
                <?php if ($is_status) { ?><th style="width:50px;"><?= lang('状态') ?></th><?php } ?>
                <th style="width:50px;"><?= lang('操作') ?></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(v,index) in <?= $model ?>.<?= $name ?>">
                <td>
                    <el-input style="width:200px;" size="small" 
                        v-model="<?= $model ?>.<?= $name ?>[index].title"
                        @input="$forceUpdate()">
                    </el-input>
                </td>
                <td>
                    <el-input style="width:200px;" size="small" 
                        v-model="<?= $model ?>.<?= $name ?>[index].sku"
                        @input="$forceUpdate()">
                    </el-input>
                </td>
                <?php if ($is_image) { ?>
                    <td>
                        <div style="display:flex;align-items: center;">
                            <el-image style="width: 50px; height:50px" v-if="<?= $model ?>.<?= $name ?>[index].image"
                                :src="<?= $model ?>.<?= $name ?>[index].image" :preview-src-list="[<?= $model ?>.<?= $name ?>[index].image]"></el-image>
                            <a v-if="<?= $model ?>.<?= $name ?>[index].image" href="javascript:void(0);" class="hand link ml10"
                                @click="upload_spec('<?= $name ?>',index)" title="<?= lang('替换') ?>"><?= lang('替换') ?></a>
                            <a v-else href="javascript:void(0);" class="hand link ml10"
                                @click="upload_spec('<?= $name ?>',index)" title="<?= lang('上传') ?>"><?= lang('上传') ?></a>
                        </div>
                    </td>
                <?php } ?>
                <td>
                    <el-input style="width:100px;" size="small" type="number"
                        v-model="<?= $model ?>.<?= $name ?>[index].price"
                        @input="$forceUpdate()"></el-input>
                </td>
                <?php if ($is_stock) { ?>
                    <td>
                        <el-input style="width:100px;" size="small" type="number"
                            v-model="<?= $model ?>.<?= $name ?>[index].stock"
                            @input="$forceUpdate()" style="width: 100px;"></el-input>
                    </td>
                <?php } ?>
                <?php if ($is_status) { ?>
                    <td>
                        <el-switch size="small" v-model="<?= $model ?>.<?= $name ?>[index].status" active-value="1"
                            inactive-value="-1" active-color="#13ce66" inactive-color="#ff4949">
                        </el-switch>
                    </td>
                <?php } ?>
                <td>
                    <el-button type="danger" size="small" @click="del_spec(index)" icon="el-icon-delete"
                        circle></el-button>
                </td>
            </tr>
        </tbody>
    </table>
</el-form-item>
<?php
 
$vue->method("update_spec()","
    app.{$model}.{$name} = [
        {title:'',price:'',stock:'',status:'1'}, 
    ];
    app.\$forceUpdate();
");

$vue->method("push_spec()", "  
    if(!app.{$model}.{$name}){
        app.{$model}.{$name} = [];
    }
    app.{$model}.{$name}.push({title: '', price: '', stock: '', status: '1'});
    app.\$forceUpdate();
");

$vue->method("del_spec(index)", "
    app.{$model}.{$name}.splice(index,1);
    app.\$forceUpdate();
");

$vue->method("spec_change()","  
    if(app.{$model}.{$select_name} == 2 && !app.{$model}.{$name} ){ 
        app.update_spec(); 
    } 
");

// 添加新的方法来处理输入变化
$vue->method("update_spec_field(index, field, value)", "
    app.\$set(app.{$model}.{$name}[index], field, value);
");
 
$vue->data("upload_spec_index", '');
$vue->data("upload_spec_field", '');

$js =  "
parent.layer.closeAll();
let field = parentVue.upload_spec_field;
let index = parentVue.upload_spec_index; 
parentVue.\$set(parentVue." . $model . "[field][index], 'image', data.url);
parentVue.\$forceUpdate();
";
$js = aes_encode($js);
$vue->method("upload_spec(field,index)", " 
    app.upload_spec_field = field;
    app.upload_spec_index = index;
    layer.open({
        type: 2,
        title: '" . lang('上传图片') . "',
        area: ['90%', '80%'],
        content: '/admin/media/index?js=" . $js . "'
    });
");
?>