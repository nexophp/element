<?php

namespace element;

class base
{
    public static $js;
    public static function create($filter_pager = 'filter', $arr = [])
    {
        global $vue;
        $per_page = $arr['per_page'] ?? 10;
        $per_page_name = $arr['per_page_name'] ?? 'per_page';
        unset($arr['per_page']);
        $js = $arr['js'] ?? '';
        unset($arr['js']);
        $init = $arr['init'] ?? '';
        unset($arr['init']);
        $is_page = $arr['is_page'] ?? '';
        unset($arr['is_page']);
        $reload_data = $arr['reload_data'] ?? '';
        unset($arr['reload_data']);
        $url = $arr['url'] ?? '';
        unset($arr['url']);
        $ele = $arr['data'] ?? '';
        unset($arr['data']);
        $method = 'load_filter_' . $ele;
        $where  = 'filter_' . $ele;
        $total  = 'filter_total_' . $ele;
        $reload_filter = [];
        if ($reload_data) {
            if (is_array($reload_data)) {
                foreach ($reload_data as $_re) {
                    $reload_filter[] = 'load_filter_' . $_re;
                }
            } else {
                $reload_filter[] = 'load_filter_' . $reload_data;
            }
        }
        if ($filter_pager == 'filter') {
            $method_search = $method . "_search";
            $vue->method($method_search, "
                this." . $where . ".page = 1;
                this." . $method . "();
            ");
            foreach ($arr as &$vv) {
                $change = "change";
                if ($vv['type'] == 'input') {
                    $change = "input";
                }
                $vv['attr_element']['@' . $change] = $method_search . "()";
            }
            form::$model = $where;
            array_unshift($arr, ['type' => 'open', ":inline" => true]);
            $arr[] = ['type' => 'close'];
        }
        $str = self::$js;
        if ($reload_filter) {
            foreach ($reload_filter as $re) {
                $str .= "_this." . $re . "();";
            }
        }
        if ($is_page || $filter_pager == 'pager') {

            $str .= "_this." . $total . " = res.total;";
        }
        $vue->data($ele, "[]");
        $vue->data($total, 0);
        $vue->data($where, "{" . $per_page_name . ":" . $per_page . ",page:1}");
        if (substr($url, 0, 1) == ':') {
            $url = substr($url, 1);
            $vue->method($method, "
                ajax(this." . $url . ",this." . $where . ",function(res){
                    _this." . $ele . " = res.data;
                    " . $str . "
                });
            ");
        } else {
            $vue->method($method, "
                ajax('" . $url . "',this." . $where . ",function(res){
                    _this." . $ele . " = res.data;
                    " . $str . "
                });
            ");
        }

        if ($init) {
            $vue->created([$method . "()"]);
        }
        if ($filter_pager == 'filter') {
            return form::create($arr);
        } else {
            $size_change = $ele . "_size_change";
            $current_change = $ele . "_current_change";

            $vue->method($size_change . "(e)", "
                this." . $where . "." . $per_page_name . " = e;
                this." . $where . ".page = 1;
                this." . $method . "();
            ");
            $vue->method($current_change . "(e)", " 
                this." . $where . ".page = e;
                this." . $method . "();
            ");
            return '<el-pagination class="mb5" background layout="total,prev, pager, next" :page-size="' . $where . '.' . $per_page_name . '"
                :current-page="' . $where . '.page" @size-change="' . $size_change . '" @current-change="' . $current_change . '"
                :total="' . $total . '"  v-if=" ' . $total . ' > 0">
            </el-pagination>';
        }
    }
}
