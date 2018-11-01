<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/8/20
 * Time: 下午12:34
 */

class SalaryConst {
    // 增加字段1·
    // 个人失业 个人医疗 个人养老 个人合计 单位失业 单位医疗 单位养老 单位工伤 单位生育 单位合计
    // 2011-10-14增加字段 姓名 身份证号 银行卡号 身份类别 社保基数 公积金基数
    const COMPANY_NAME = 0;
    const PER_NAME = 1;
    const ID_CARD = 2;

    const BANK_NO = 0;
    const PERSON_TYPE = 1;
    const SHEBAO_JISHU = 2;
    const GONGJIJN_JISHU = 3;


    const PER_YINGFAHEJI = 0;
    const PER_SHIYE = 1;
    const PER_YILIAO = 2;
    const PER_YANGLAO = 3;
    const PER_GONGJIJIN = 4;
    const DAIKOUSHUI = 5;
    const PER_KOUKUANHEJI = 6;
    const SHIFAHEJI = 7;
    const DANWEI_SHIYE = 8;
    const DANWEI_YILIAO = 9;
    const DANWEI_YANGAO = 10;
    const DANWEI_GONGSHANG = 11;
    const DANWEI_SHENGYU = 12;
    const DANWEI_GONGJIJIN = 13;
    const DANWEI_HEJI = 14;

    const LAOWUFEI = 0;
    const CANBAOJIN = 1;
    const DANGANFEI = 2;
    const JIAOZHONGQIHEJI = 3;
    const FREE_TEX = 4;
    const SHIFAHEJI_JIANHOUXIANG = 5;
    const JIAOZHONGQJIYE_JIANHOUXIANG = 6;

    const SALARY_ER_TYPE = 5;
    const SALARY_NIAN_TYPE = 6;

    public static $salary_base_name_list = array(
        self::COMPANY_NAME=>'公司名称',
        self::PER_NAME=>'姓名',
        self::ID_CARD=>'身份证号',
    );
    public static $salary_required_name_list = array(
        self::BANK_NO=>'银行卡号',
        self::PERSON_TYPE=>'身份类别',
        self::SHEBAO_JISHU=>'社保基数',
        self::GONGJIJN_JISHU=>'公积金基数',
    );
    public static  $salary_head_name_list = array(
     self::PER_YINGFAHEJI=>'个人应发合计',
     self::PER_SHIYE=>'个人失业',
     self::PER_YILIAO=>'个人医疗',
     self::PER_YANGLAO=>'个人养老',
     self::PER_GONGJIJIN=>'个人公积金',
     //self::PER_HEJI=>'个人合计',
     self::DAIKOUSHUI=>'代扣税',
     self::PER_KOUKUANHEJI=>'个人扣款合计',
     self::SHIFAHEJI=>'实发合计',
     self::DANWEI_SHIYE=>'单位失业',
     self::DANWEI_YILIAO=>'单位医疗',
     self::DANWEI_YANGAO=>'单位养老',
     self::DANWEI_GONGSHANG=>'单位工伤',
     self::DANWEI_SHENGYU=>'单位生育',
     self::DANWEI_GONGJIJIN=>'单位公积金',
     self::DANWEI_HEJI=>'单位合计',
    );
    public static $zhongqi_fee_head_name_list = array(
        self::LAOWUFEI=>'劳务费',
        self::CANBAOJIN=>'残保金',
        self::DANGANFEI=>'档案费',
        self::JIAOZHONGQIHEJI=>'交中企基业合计',
    );
    public static $zhongqi_addition_head_name_list = array(
        self::FREE_TEX=>'劳务费',
        self::SHIFAHEJI_JIANHOUXIANG=>'实发合计减后项',
        self::JIAOZHONGQJIYE_JIANHOUXIANG=>'交中企基业减后项',
    );
    public static $salary_er_head_name_list = array(
        '0'  => "二次工资合计",
        '1'  => "当月发放工资",
        '2'  => "实际应发合计",
        /*'3'  => "失业",
        '4'  => "医疗",
        '5'  => "养老",
        '6'  => "公积金",*/
        '3'  => "应扣税",
        '4'  => "已扣税",
        '5'  => "补扣税",
        '6' => "双薪进卡",
        '7' => "缴中企基业合计",
    );
    public static $salary_nian_head_name_list = array(
        '0'  => "当月应发合计",
        '1'  => "当月实发合计",
        '2'  => "年终奖代扣税",
        '3'  => "实发进卡",
        '4'  => "缴纳中企基业合计",
    );

} 