<el-form-item label="<?= $label ?>" <?= $item_attr ?>>
    <textarea id="editor_<?= $name ?>" name="<?= $model ?>[<?= $name ?>]" placeholder=""><?= $value ?></textarea>
</el-form-item>

<?php
$toolbar = [
    'undo',
    'redo',
    '|',
    'bold',
    'italic',
    '|',
    'fontColor',
    'fontBackgroundColor',
    'insertImage',
    'insertTable',
];
$language = 'zh-cn';
?>

<script type="importmap">
    {
        "imports": {
            "ckeditor5": "/misc/ckeditor5/ckeditor5.js",
            "ckeditor5/": "/misc/ckeditor5/"
        }
    }
</script>

<script type="module">
    import {
        ClassicEditor,
        Essentials,
        Paragraph,
        Bold,
        Italic,
        Image,
        ImageInsert,
        Table,
        TableToolbar,
        FileRepository,
        Font
    } from 'ckeditor5';
    ClassicEditor
        .create(document.querySelector('#editor_<?= $name ?>'), {
            plugins: [Essentials, Paragraph, Bold, Italic, Font, Image, ImageInsert, Table, TableToolbar, FileRepository],
            toolbar: <?= json_encode($toolbar) ?>,
            language: '<?= $language ?>',
        })
        .then(editor => {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new MyUploadAdapter(loader);
            };
            window.editor_<?= $name ?> = editor;
        })
        .catch(error => {
            console.error(error);
        });
</script>