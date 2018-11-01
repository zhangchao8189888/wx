<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16-4-23
 * Time: 下午6:46
 */
class FSalary {
    public static function getSalaryHead ($head_arr) {
        $count_add = count ($head_arr);
        $head = $head_arr;
        // 增加字段1·
        // 个人失业 个人医疗 个人养老 个人合计 单位失业 单位医疗 单位养老 单位工伤 单位生育 单位合计
        // 2011-10-14增加字段 姓名 身份证号 银行卡号 身份类别 社保基数 公积金基数
        $head [($count_add + 0)] = " 银行卡号";
        $head [($count_add + 1)] = "身份类别";
        $head [($count_add + 2)] = " 社保基数";
        $head [($count_add + 3)] = "公积金基数";
        // 再次算出字段总列数
        $count = count ( $head );
        $head [($count + 0)] = "个人应发合计";
        $head [($count + 1)] = "个人失业";
        $head [($count + 2)] = "个人医疗";
        $head [($count + 3)] = "个人养老";
        $head [($count + 4)] = "个人公积金";
        $head [($count + 5)] = "代扣税";
        $head [($count + 6)] = "个人扣款合计";
        $head [($count + 7)] = "实发合计";
        $head [($count + 8)] = "单位失业";
        $head [($count + 9)] = "单位医疗";
        $head [($count + 10)] = "单位养老";
        $head [($count + 11)] = "单位工伤";
        $head [($count + 12)] = "单位生育";
        $head [($count + 13)] = "单位公积金";
        $head [($count + 14)] = "单位合计";
        $head [($count + 15)] = "劳务费";
        $head [($count + 16)] = "残保金";
        $head [($count + 17)] = "档案费";
        $head [($count + 18)] = "交中企基业合计";
        if (! empty ( $freeTex )) {
            $head [($count + 19)] = "免税项";
        }
        if (! empty ( $_POST ['shifajian'] )) {
            $head [($count + 20)] = "实发合计减后项";
            $head [($count + 21)] = "交中企基业减后项";
        }
        return $head;
    }
}