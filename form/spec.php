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
<el-form-item label="<?= $label ?>" required>
    <div style="display: flex;margin-top: 15px;" v-if="!<?= $model ?>.id" class="mb-3">
        <el-radio @change="spec_change()" v-model="<?= $model ?>.<?= $select_name ?>" label="1"><?= lang('单规格') ?></el-radio>
        <el-radio @change="spec_change()" style="margin-left: 10px;" v-model="<?= $model ?>.<?= $select_name ?>" label="2"><?= lang('多规格') ?></el-radio>
    </div>

    <!-- 多规格名称和值配置 -->
    <div v-if="<?= $model ?>.<?= $select_name ?> == 2" class="spec-config mb-3">
        <h4><?= lang('规格配置') ?></h4>
        <div v-for="(spec, specIndex) in <?= $model ?>.spec_names" :key="specIndex" class="spec-item mb-3" style="border: 1px solid #e4e7ed; padding: 15px; border-radius: 4px;">
            <div class="d-flex align-items-center mb-2">
                <label class="me-2" style="min-width: 60px; font-weight: bold;"><?= lang('规格名') ?>:</label>
                <el-input v-model="spec.name" style="width: 150px;" size="small" placeholder="<?= lang('如：颜色、尺寸') ?>" @input="$forceUpdate()"></el-input>
                <el-button @click="remove_spec_name(specIndex)" type="danger" size="small" icon="el-icon-delete" class="ms-2" v-if="<?= $model ?>.spec_names.length > 1"></el-button>
            </div>
            <div class="d-flex align-items-start">
                <label class="me-2" style="min-width: 60px; font-weight: bold; "><?= lang('规格值') ?>:</label>
                <div class="spec-values d-flex flex-wrap" style="flex: 1;">
                    <div v-for="(value, valueIndex) in spec.values" :key="valueIndex" class="spec-value-item me-2 mb-2 d-flex align-items-center">
                        <el-input v-model="spec.values[valueIndex]" style="width: 100px;" size="small" placeholder="<?= lang('规格值') ?>" @input="$forceUpdate()"></el-input>
                        <el-button @click="remove_spec_value(specIndex, valueIndex)" type="text" size="small" icon="el-icon-close" class="ms-1" v-if="spec.values.length > 1" style="color: #f56c6c;"></el-button>
                    </div>
                    <el-button @click="add_spec_value(specIndex)" type="text" size="small" icon="el-icon-plus" style="color: #409eff;"><?= lang('添加值') ?></el-button>
                </div>
            </div>
        </div>
        <div class="spec-actions">
            <el-button @click="add_spec_name()" type="primary" size="small" icon="el-icon-plus"><?= lang('添加规格') ?></el-button>
            <el-button @click="generate_spec_combinations()" type="success" size="small" class="ms-2" icon="el-icon-s-grid"><?= lang('生成规格组合') ?></el-button>
        </div>
    </div>
    <div v-if="<?= $model ?>.<?= $select_name ?> == 1">
        <el-form label-position="left" @submit.native.prevent label-width="180px" style="padding-right:20px;">
            <el-form-item label="<?= lang('商品唯一码') ?>" required>
                <el-input style="width:200px" v-model="<?= $model ?>.sku"></el-input>
            </el-form-item>
            <!-- <el-form-item label="<?= lang('市场价') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.price_mart" type="number"></el-input>
            </el-form-item> -->
            <el-form-item label="<?= lang('售价') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.price" type="number"></el-input>
            </el-form-item>
            <el-form-item label="<?= lang('库存') ?>" required class="mt-2">
                <el-input style="width:200px" v-model="<?= $model ?>.stock" type="number"></el-input>
            </el-form-item>

        </el-form>
    </div>

    <div v-if="<?= $model ?>.<?= $select_name ?> == 2 && <?= $model ?>.<?= $name ?> && <?= $model ?>.<?= $name ?>.length > 0" class="spec-combinations">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><?= lang('规格组合') ?> <small style="color: #909399;">(共 {{ <?= $model ?>.<?= $name ?>.length }} 个组合)</small></h4>
            <div class="batch-actions">
                <el-button @click="batch_set_price()" size="small" type="primary" plain><?= lang('批量设价格') ?></el-button>
                <?php if ($is_stock) { ?>
                    <el-button @click="batch_set_stock()" size="small" type="success" plain class="ms-2"><?= lang('批量设库存') ?></el-button>
                <?php } ?>
            </div>
        </div>
        <table style="width:100%;" class="table table-bordered table-striped">
            <thead style="background-color: #f5f7fa;">
                <tr>
                    <th v-for="specName in <?= $model ?>.spec_names" :key="specName.name" style="text-align: center;">{{ specName.name }}</th>
                    <th style="text-align: center;"><?= lang('商品唯一码') ?></th>
                    <?php if ($is_image) { ?><th style="text-align: center;"><?= lang('图片') ?></th><?php } ?>
                    <th style="text-align: center;"><?= lang('价格') ?></th>
                    <?php if ($is_stock) { ?><th style="text-align: center;"><?= lang('库存') ?></th><?php } ?>
                    <?php if ($is_status) { ?><th style="width:80px; text-align: center;"><?= lang('状态') ?></th><?php } ?>
                    <th style="width:80px; text-align: center;"><?= lang('默认') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(v,index) in <?= $model ?>.<?= $name ?>" :key="index">
                    <td v-for="(specValue, specIndex) in v.spec_values" :key="specIndex" style="text-align: center; vertical-align: middle;">
                        <el-tag size="small" :type="getTagType(specIndex)">{{ specValue }}</el-tag>
                    </td>
                    <td style="text-align: center;">
                        <el-input style="width:150px;" size="small"
                            v-model="<?= $model ?>.<?= $name ?>[index].sku"
                            placeholder="<?= lang('SKU') ?>"
                            @input="$forceUpdate()">
                        </el-input>
                    </td>
                    <?php if ($is_image) { ?>
                        <td style="text-align: center;">
                            <div style="display:flex;align-items: center; justify-content: center;">
                                <el-image style="width: 50px; height:50px; border-radius: 4px;" v-if="<?= $model ?>.<?= $name ?>[index].image"
                                    :src="<?= $model ?>.<?= $name ?>[index].image" :preview-src-list="[<?= $model ?>.<?= $name ?>[index].image]"></el-image>
                                <div v-else style="width: 50px; height: 50px; border: 1px dashed #d9d9d9; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                    <i class="el-icon-picture" style="color: #c0c4cc;"></i>
                                </div>
                                <div class="ms-2">
                                    <el-button v-if="<?= $model ?>.<?= $name ?>[index].image" size="mini" type="text"
                                        @click="upload_spec('<?= $name ?>',index)"><?= lang('替换') ?></el-button>
                                    <el-button v-else size="mini" type="text"
                                        @click="upload_spec('<?= $name ?>',index)"><?= lang('上传') ?></el-button>
                                </div>
                            </div>
                        </td>
                    <?php } ?>
                    <td style="text-align: center;">
                        <el-input style="width:100px;" size="small" type="number"
                            v-model="<?= $model ?>.<?= $name ?>[index].price"
                            placeholder="0.00"
                            @input="$forceUpdate()"></el-input>
                    </td>
                    <?php if ($is_stock) { ?>
                        <td style="text-align: center;">
                            <el-input style="width:100px;" size="small" type="number"
                                v-model="<?= $model ?>.<?= $name ?>[index].stock"
                                placeholder="0"
                                @input="$forceUpdate()"></el-input>
                        </td>
                    <?php } ?>
                    <?php if ($is_status) { ?>
                        <td style="text-align: center;">
                            <el-switch size="small" v-model="<?= $model ?>.<?= $name ?>[index].status" active-value="1"
                                inactive-value="-1" active-color="#13ce66" inactive-color="#ff4949">
                            </el-switch>
                        </td>
                    <?php } ?>
                    <td style="text-align: center;">
                        <el-switch @change="spec_is_default_change(index)" size="small" v-model="<?= $model ?>.<?= $name ?>[index].is_default" active-value="1"
                            inactive-value="-1" active-color="#13ce66" inactive-color="#ff4949">
                        </el-switch>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</el-form-item>
<?php

// 获取标签类型（为不同规格值设置不同颜色）
$vue->method("getTagType(index)", "
    const types = ['', 'success', 'info', 'warning', 'danger'];
    return types[index % types.length];
");

// 批量设置价格
$vue->method("batch_set_price()", "
    let price = prompt('请输入统一价格:');
    if(price && !isNaN(price) && parseFloat(price) >= 0) {
        app.{$model}.{$name}.forEach(item => {
            item.price = price;
        });
        app.\$forceUpdate();
        app.\$message.success('批量设置价格成功');
    }
");

// 批量设置库存
$vue->method("batch_set_stock()", "
    let stock = prompt('请输入统一库存:');
    if(stock && !isNaN(stock) && parseInt(stock) >= 0) {
        app.{$model}.{$name}.forEach(item => {
            item.stock = stock;
        });
        app.\$forceUpdate();
        app.\$message.success('批量设置库存成功');
    }
");

// 初始化规格名称数据
$vue->method("init_spec_names()", "
    if(!app.{$model}.spec_names){
        app.{$model}.spec_names = [{
            name: '',
            values: ['']
        }];
    }
");

// 添加规格名称
$vue->method("add_spec_name()", "
    if(!app.{$model}.spec_names){
        app.{$model}.spec_names = [];
    }
    app.{$model}.spec_names.push({
        name: '',
        values: ['']
    });
    app.\$forceUpdate();
");

// 删除规格名称
$vue->method("remove_spec_name(specIndex)", "
    app.{$model}.spec_names.splice(specIndex, 1);
    app.\$forceUpdate();
");

// 添加规格值
$vue->method("add_spec_value(specIndex)", "
    app.{$model}.spec_names[specIndex].values.push('');
    app.\$forceUpdate();
");

// 删除规格值
$vue->method("remove_spec_value(specIndex, valueIndex)", "
    if(app.{$model}.spec_names[specIndex].values.length > 1) {
        app.{$model}.spec_names[specIndex].values.splice(valueIndex, 1);
        app.\$forceUpdate();
    }
");

// 生成笛卡尔积规格组合
$vue->method("generate_spec_combinations()", "
    if(!app.{$model}.spec_names || app.{$model}.spec_names.length === 0) {
        app.\$message.warning('请先添加规格名称和规格值');
        return;
    }
    
    // 验证规格名称和值是否完整
    for(let i = 0; i < app.{$model}.spec_names.length; i++) {
        let spec = app.{$model}.spec_names[i];
        if(!spec.name || spec.name.trim() === '') {
            app.\$message.warning('请填写规格名称');
            return;
        }
        let validValues = spec.values.filter(v => v && v.trim() !== '');
        if(validValues.length === 0) {
            app.\$message.warning('请为每个规格添加至少一个规格值');
            return;
        }
        // 更新有效值
        spec.values = validValues;
    }
    
    // 生成笛卡尔积
    let combinations = cartesianProduct(app.{$model}.spec_names.map(spec => spec.values));
    
    // 生成规格组合数据
    app.{$model}.{$name} = combinations.map((combination, index) => {
        return {
            spec_values: combination,
            sku: '',
            price: '',
            stock: '',
            status: '1',
            is_default: index === 0 ? '1' : '0',
            image: ''
        };
    });
    
    app.\$forceUpdate();
    app.\$message.success('规格组合生成成功，共生成 ' + combinations.length + ' 个组合');
    
    // 笛卡尔积算法
    function cartesianProduct(arrays) {
        if (arrays.length === 0) return [[]];
        if (arrays.length === 1) return arrays[0].map(item => [item]);
        
        let result = [[]];
        for (let array of arrays) {
            let temp = [];
            for (let item of array) {
                for (let combination of result) {
                    temp.push([...combination, item]);
                }
            }
            result = temp;
        }
        return result;
    }
");

$vue->method("update_spec()", "
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

$vue->method("spec_change()", "  
    if(app.{$model}.{$select_name} == 2) { 
        // 如果是编辑模式且已有spec_names数据，则不需要重新初始化
        if(!app.{$model}.spec_names || app.{$model}.spec_names.length === 0) {
            app.init_spec_names();
        }
        if(!app.{$model}.{$name}) {
            app.{$model}.{$name} = [];
        }
        // 强制更新视图以确保编辑时的数据能正确显示
        app.\$forceUpdate();
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
$vue->method("spec_is_default_change(index)", " 
    let val = app.{$model}.{$name}[index].is_default;
    //其他的先变成 0 
    app.{$model}.{$name}.forEach((item,index) => {
        item.is_default = 0;
    }); 
    app.{$model}.{$name}[index].is_default = val;
");
?>