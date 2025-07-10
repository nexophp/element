<?php

namespace element;

class table
{
    public static $scope = 'slot-scope';
    public static function create($arr = [])
    {
        $str = '';
        foreach ($arr as $k => $v) {
            $name = $v['name'];
            $str .= self::$name($v);
        }
        return $str;
    }
    public static function open($arr = [])
    {
        $arr['type'] = $arr['type'] ?? 'small';
        return "<el-table " . element_to_str($arr)." border>\n";
    }
    public static function column($arr = [])
    {
        $tpl = $arr['tpl'] ?? [];
        unset($arr['tpl']);
        $str = "<el-table-column " . element_to_str($arr).">\n";
        if ($tpl) {
            $str .= "<template ".self::$scope."='scope'>\n";
            if (isset($tpl['type'])) {
                if ($tpl['type'] == 'html' || $tpl['html']) {
                    $str .= $tpl['html'];
                } else {
                    $str .= self::element($tpl);
                }
            } else {
                foreach ($tpl as $k => $v) {
                    if (isset($v['type']) && $v['type'] == 'html') {
                        $str .= $v['html'];
                    } else {
                        $str .= self::element($v);
                    }
                }
            }
            $str .= "</template>\n";
        }
        $str .= "</el-table-column>\n";
        return $str;
    }
    public static function element($arr = [])
    {
        $name = $arr['name'];
        $label = $arr['label'];
        unset($arr['name'],$arr['label']);
        $arr['type'] = $arr['type'] ?? 'text';
        $arr['size'] = $arr['size'] ?? 'small';
        $html = $arr['html'];
        if ($html) {
            return $html;
        }
        return '<el-'.$name.' '. element_to_str($arr).'>'.self::scope($label).'</el-'.$name.'>'."\n";
    }
    public static function scope($label)
    {
        if (strpos($label, 'scope.') !== false && strpos($label, '{') === false) {
            $label = "{{".$label."}}";
        }
        return $label;
    }
    public static function span($arr = [])
    {
        $name = $arr['name'];
        $label = $arr['label'];
        unset($arr['name'],$arr['label']);
        $label = self::scope($label);
        return '<span '. element_to_str($arr).'>'.$label.'</span>'."\n";
    }
    public static function close()
    {
        return "</el-table>";
    }
}
