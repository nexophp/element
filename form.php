<?php

namespace element;

class form
{
    public static $model = 'form';

    public static function create($arr = [])
    {
        global $vue;
        $vue->add_date();
        $str = '';
        foreach ($arr as $v) {
            if ($v['type'] == 'open') {
                $str .= self::open($v);
            } elseif ($v['type'] == 'close') {
                $str .= self::close();
            } else {
                $str .= self::element($v);
            }
        }
        return $str;
    }
    /**
     * 生成表单
     */
    public static function element($v = [])
    {
        global $vue;
        $file = $v['file'] ?? __DIR__ . '/form/' . $v['type'] . '.php';
        $content = '';
        if (file_exists($file)) {
            $model = self::$model;
            $label = $v['label'] ?? '';
            $name  = $v['name'] ?? '';
            $attr  = $v['attr'] ?? [];
            $js  = $v['js'] ?? '';
            $attr_element      = $v['attr_element'] ?? '';
            $attr_element_pre  = $v['attr_element_pre'] ?? '';
            $attr_element_next = $v['attr_element_next'] ?? '';
            $multiple = $v['multiple'] ?? '';
            $mime     = $v['mime'] ?? '';
            unset($v['mime']);
            $size     = $v['size'] ?? '';
            unset($v['size']);
            $url      = $v['url'] ?? '';
            unset($v['url']);
            $item_attr     = element_to_str($attr);
            $attr_element  = element_to_str($attr_element);
            $attr_element_pre   = element_to_str($attr_element_pre);
            $attr_element_next  = element_to_str($attr_element_next);
            ob_start();
            include $file;
            $content = ob_get_contents();
            ob_end_clean();
        }else{
            return $v['html']??'';
        }
        return $content;
    }

    /**
     * 合并原字段及自定义字段
     * @param $field 原字段
     * @param $form  配置的新字段
     */
    public static function merge_field($field = [], $form = [])
    {
        if (!$form) {
            return $field;
        }
        $form = self::get_field_keys($form);
        if ($form['id']) {
            throw new Exception("字段配置异常");
        }
        if (!$field) {
            return array_unique($form);
        }
        $new_field = array_merge($field, $form);
        $new_field = array_unique($new_field);
        sort($new_field);
        return $new_field;
    }
    /**
     * 表单字段key数组
     */
    public static function get_field_keys($form = [])
    {
        $f = [];
        foreach ($form as $v) {
            $f[] = $v['field'];
        }
        return $f;
    }
    /**
     * builder 字段
     */
    public static function field_type()
    {
        return [
            ['label' => 'input', 'element' => 'input'],
            ['label' => 'text', 'element' => 'text'],
            [
                'label' => 'select',
                'element' => 'select',
                'multiple' => true,
                'value' => [
                    ['label' => '选项1', 'value' => '1'],
                    ['label' => '选项2', 'value' => '2'],
                ],
            ],
            ['label' => 'checkbox', 'element' => 'checkbox', 'default' => 1, 'value' => [
                ['label' => '状态1', 'value' => '1'],
                ['label' => '状态2', 'value' => '2'],
            ]],
            ['label' => 'number', 'element' => 'number'],
            ['label' => 'radio', 'element' => 'radio'],
            ['label' => 'cascader', 'element' => 'cascader', 'url' => '/form/test/cascader'],
            ['label' => 'color', 'element' => 'color'],
            ['label' => 'time', 'element' => 'time', 'options' => [
                'start' => '08:30',
                'step' => '00:15',
                'end'  => '18:30'
            ]],
            ['label' => 'datetime', 'element' => 'datetime'],
            ['label' => 'upload', 'element' => 'upload'],
            ['label' => 'autocomplete', 'element' => 'autocomplete'],
            ['label' => 'attr', 'element' => 'attr'],
            ['label' => 'sku', 'element' => 'sku'],
        ];
    }
    /**
     * 省市区
     */
    public static function get_city()
    {
        $file = __DIR__ . '/data/city.json';
        if (file_exists($file)) {
            ob_start();
            include $file;
            $d = ob_get_contents();
            ob_end_clean();
            $d = json_decode($d, true);
            return $d;
        } else {
            $file = __DIR__ . '/data/city_raw.json';
            if (!file_exists($file)) {
                return;
            }
            ob_start();
            include $file;
            $d = ob_get_contents();
            ob_end_clean();
            $d = json_decode($d, true);
            $list = [];
            $i = 1;
            $j = count($d) + 10;
            foreach ($d as $k1 => $v1) {
                $list[] = ['id' => $i, 'name' => $k1, 'label' => $k1, 'pid' => 0];
                foreach ($v1 as $k2 => $v2) {
                    $list[] = ['id' => $j, 'name' => $k2, 'label' => $k2, 'pid' => $i];
                    $k = 1;
                    foreach ($v2 as $k3 => $v3) {
                        $list[] = ['id' => 'k' . $k++, 'name' => $v3, 'label' => $v3, 'pid' => $j];
                        $k++;
                    }
                    $j++;
                }
                $i++;
            }
            $list = array_to_tree($list);
            $list = array_values($list);
            return $list;
        }
    }
    /**
     * 根据城市地址库数组key，返回 value
     */
    public static function get_city_value($arr = [])
    {
        $list = [];
        $d = self::get_city();
        foreach ($d as &$v) {
            $v['id'] = (string)$v['id'];
            if ($v['id'] == $arr[0]) {
                $list[] = $v['name'];
                foreach ($v['children'] as &$v1) {
                    $v1['id'] = (string)$v1['id'];
                    if ($v1['id'] == $arr[1]) {
                        $list[] = $v1['name'];
                        foreach ($v1['children'] as &$v2) {
                            $v2['id'] = (string)$v2['id'];
                            if ($v2['id'] == $arr[2]) {
                                $list[] = $v2['name'];
                            }
                        }
                    }
                }
            }
        }
        return $list;
    }

    public static function open($arr = [])
    {
        unset($arr['model'], $arr['name']);
        return "<el-form " . element_to_str($arr) . ">\n";
    }

    public static function close()
    {
        return "</el-form>\n";
    }
}
