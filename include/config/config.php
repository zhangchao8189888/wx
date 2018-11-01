<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 15-3-27
 * Time: 下午1:25
 */
return array(
    'pageSize'  => 20,
    'employ_status' => array(
        '1' =>  '在职',
        '2' =>  '离职',
        '3' =>  '退休',
    ),
    'employ_type' => array(
        0=> "未缴纳保险",
        1 => "本市城镇职工",
        2 => "外埠城镇职工",
        3 => "本市农村劳动力",
        4 => "外地农村劳动力",
        5 => "本市农民工",
        6 => "外地农民工",
    ),
    'employ_type_val' => array(
        '未缴纳保险'     => 0,
        '本市城镇职工'   => 1,
        '外埠城镇职工'   => 2,
        '本市农村劳动力' => 3,
        '外地农村劳动力' => 4,
        '本市农民工'    => 5,
        '外地农民工'    => 6,
    ),
    'employ_sex' => array(
        1 => "男",
        2 => "女",
    ),
    'salary_head' => array(
        1 => "男",
        2 => "女",
    ),
    'uploadPath' => '/var/www/zc.orderStorage/upload/',
    // 工资审核状态
    'examine_status' => array(
        '0' => '未申请发放',
        '1' => '申请批准中',
        '2' => '批准通过',
        '3' => '未通过',
    ),
    // 工资发放状态
    'grant_status' => array(
        0 => '暂无审核',
        1 => '处理审核',
        2 => '同意发放',
        3 => '拒绝发放',
    ),
    'salary_type' => array(
        'SALARY_ER' => 5,
        'SALARY_NIAN' => 6,
    )
);