<el-form-item label="<?=$label?>" <?=$item_attr?>>
    <div :id="'<?=$name?>_editor'" style="min-height: 300px; border: 1px solid #ddd;"></div>
    <input type="hidden" v-model="<?php echo $model . '.' . $name;?>" />
</el-form-item>

内容：{{<?php echo $model . '.' . $name;?>}}

<?php
// 为每个编辑器实例生成唯一的方法名
$editor_id = $name . '_editor';
$upload_method = 'upload_editor_image_' . $name;

// 图片上传的回调JS代码
$js = "
parent.layer.closeAll();
let editorInstance = parentVue.ckeditorInstances['".$editor_id."'];
if(editorInstance) {
    editorInstance.model.change(writer => {
        const imageElement = writer.createElement('imageBlock', {
            src: data.url
        });
        editorInstance.model.insertContent(imageElement, editorInstance.model.document.selection);
    });
}
";
$js = aes_encode($js);

// 添加Vue数据和方法
$vue->data('ckeditorInstances', 'js:{}');
$vue->data($name . '_editor_content', '');

// 图片上传方法
$vue->method($upload_method . "()", "
    layer.open({
        type: 2,
        title: '" . lang('上传图片') . "',
        area: ['90%', '80%'],
        content: '/admin/media/index?js=" . $js . "'
    });
");

// 编辑器初始化方法
$vue->method('init_ckeditor_' . $name . '()', "
    if(this.ckeditorInstances['".$editor_id."']) {
        this.ckeditorInstances['".$editor_id."'].destroy();
    }
    
    ClassicEditor.create(document.querySelector('#".$editor_id."'), {
        language: 'zh-cn',
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'uploadImage', '|',
                'undo', 'redo'
            ]
        },
        image: {
            toolbar: [
                'imageStyle:inline',
                'imageStyle:block',
                'imageStyle:side',
                '|',
                'toggleImageCaption',
                'imageTextAlternative'
            ]
        },
        table: {
            contentToolbar: [
                'tableColumn',
                'tableRow',
                'mergeTableCells'
            ]
        }
    })
    .then(editor => {
        this.ckeditorInstances['".$editor_id."'] = editor;
        
        // 设置初始内容
        if(this.".$model.'.'.$name.") {
            editor.setData(this.".$model.'.'.$name.");
        }
        
        // 监听内容变化
        editor.model.document.on('change:data', () => {
            this.".$model.'.'.$name." = editor.getData();
        });
        
        // 自定义图片上传按钮
        editor.ui.componentFactory.add('uploadImage', locale => {
            const view = new editor.ui.ButtonView(locale);
            view.set({
                label: '上传图片',
                icon: '<svg viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M6.91 10.54c.26-.23.64-.21.88.03l3.36 3.14 2.23-2.06a.64.64 0 0 1 .87 0l2.52 2.97V4.5H3.2v10.12l3.71-4.08zm10.27-7.51c.6 0 1.09.47 1.09 1.05v11.84c0 .59-.49 1.06-1.09 1.06H2.79c-.6 0-1.09-.47-1.09-1.06V4.08c0-.58.49-1.05 1.09-1.05h14.39zm-5.22 5.56a1.96 1.96 0 1 1 3.4-1.96 1.96 1.96 0 0 1-3.4 1.96z\"/></svg>',
                tooltip: true
            });
            
            view.on('execute', () => {
                this.".$upload_method."();
            });
            
            return view;
        });
    })
    .catch(error => {
        console.error('CKEditor初始化失败:', error);
    });
");

// 在mounted中初始化编辑器
$vue->mounted('ckeditor_' . $name, "
    setTimeout(() => {
        this.init_ckeditor_".$name."();
    }, 500);
");

// 只在第一次使用时加载CKEditor5资源
static $ckeditor_loaded = false;
if (!$ckeditor_loaded) {
    $ckeditor_loaded = true;
?>
<!-- CKEditor5 CSS和JS -->
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/40.2.0/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/translations/zh-cn.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

<style>
.ck-editor__editable {
    min-height: 300px;
}
.ck.ck-toolbar {
    border-top: 1px solid #ddd;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
}
.ck.ck-editor__editable {
    border-bottom: 1px solid #ddd;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
}
</style>
<?php
}
?>

<!-- 在HTML中保留版权声明 -->
<div id="editor"></div>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/translations/zh-cn.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>
<script>
 ClassicEditor.create(document.querySelector('#editor'), {
  language: 'zh-cn',
  toolbar: ['bold', 'italic', 'link', 'uploadImage'],
  image: {
    upload: {
      handler: file => Promise.resolve({ 
        default: URL.createObjectURL(file) 
      })
    }
  }
})
</script>