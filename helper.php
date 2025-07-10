<?php

define('VEN_ELEMENT_FORM_PATH', __DIR__ . '/form');

function element($name, $data)
{
    $cls = "\\element\\" . $name;
    return $cls::create($data);
}

function element_to_str($arr)
{
    if (!$arr) {
        return;
    }
    unset($arr['name']);
    $str = "";
    foreach ($arr as $k => $v) {
        if (is_numeric($k)) {
            $str .=  " " . $v . " ";
        } elseif ($v) {
            $str .= $k . "=\"" . $v . "\" ";
        }
    }
    return $str;
}

function element_vue()
{
    element_open_pdf();
    element_open_office();
}

function element_open_pdf()
{
?>
    <div v-if="is_pdf" style="position:fixed;top: 0;left:-20px;width:70%;height: 100vh; z-index: 99999;">
        <div style="position: relative;">
            <iframe :src="pdf_url" style="width:100%;height: 100vh;border:0px;"></iframe>
            <el-button @click="is_pdf = false" size="small" style="position:absolute;right: 130px;z-index: 999999;top:15px;">关闭</el-button>
        </div>
    </div>
<?php
    global $vue;
    $vue->data('is_pdf', false);
    $vue->data('pdf_url', '');
    $vue->method("open_pdf(url)", "
    this.is_pdf = true;
    this.pdf_url = url;
");
}

function element_open_office()
{
    global $vue;
    $vue->method("open_office(url)", "
        window.open('https://view.officeapps.live.com/op/view.aspx?src='+encodeURIComponent(url),'_blank');
    ");
}
