<?php
class FSumSalary{
    var $gerenshiye;
    var $gerenyiliao;
    var $gerenyanglao;
    var $gerenheji;
    var $danweishiye;
    var $danweiyiliao;
    var $danweiyanglao;
    var $danweigongshang;
    var $danweishengyu;
    var $danweiheji;
    const  jijinshu=3500;
    function getSumShebao($leibie,$shebao){
        /**社保金额合计
         *
         */
        $jisuan_sum = array();
        $userType=$this->getShenfenleibie($leibie);
        $jisuan_sum['shebaojishu']=$shebao;
        if($userType!=-1){
            $jisuan_sum['gerenshiye']=$this->jisuan_geren_shiye($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['gerenyiliao']=$this->jisuan_geren_yiliao($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['gerenyanglao']=$this->jisuan_geren_yanglao($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['koukuanheji']=($jisuan_sum['gerenshiye']+$jisuan_sum['gerenyiliao']+$jisuan_sum['gerenyanglao']);
            $jisuan_sum['danweishiye']=$this->jisuan_danwei_shiye($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['danweigongshang']=$this->jisuan_danwei_gongshang($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['danweishengyu']=$this->jisuan_danwei_shengyu($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['danweiyanglao']=$this->jisuan_danwei_yanglao($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['danweiyiliao']=$this->jisuan_danwei_yiliao($jisuan_sum['shebaojishu'],$userType);
            $jisuan_sum['danweiheji']=($jisuan_sum['danweishiye']+$jisuan_sum['danweigongshang']+$jisuan_sum['danweishengyu']+$jisuan_sum['danweiyanglao']+$jisuan_sum['danweiyiliao']);
            $jisuan_sum['sum']=$jisuan_sum['koukuanheji']+ $jisuan_sum['danweiheji'];
        }else{
            $jisuan_sum['sum']=-1;
        }
        return  $jisuan_sum['sum'];
    }
    function getSumGongjijin($leibie,$gongjijin){
        /**公积金金额合计
         *
         */
        $jisuan_sum = array();
        $userType=$this->getShenfenleibie($leibie);
        $jisuan_sum['gongjijinjishu']=$gongjijin;
        if($userType!=-1){
            $jisuan_sum['gerengongjijin']=round($this->jisuan_geren_gongjijin($jisuan_sum['gongjijinjishu']));
            $jisuan_sum['danweigongjijin']=round($this->jisuan_geren_gongjijin($jisuan_sum['gongjijinjishu']));
            $jisuan_sum['sum']= $jisuan_sum['gerengongjijin']+ $jisuan_sum['danweigongjijin'];
        }else{
            $jisuan_sum['sum']=-1;
        }
        return  $jisuan_sum['sum'];
    }
    function getSumSalary(&$jisuan_var){
        //$shebaojishu,$shenfenleibie
        //var_dump($jisuan_var);
        /**
         *      1. 应发合计 =基本工资+考核工资

        3. 扣款合计=个人失业+个人医疗+个人养老+个人公积金+代扣税
        4. 实发合计=应发合计-扣款合计
        5. 缴中企基业合计=应发合计+单位失业+单位医疗+单位养老+单位工伤+单位生育+单位公积金+劳务费+残保金+档案费
         */
        for ($i=1;$i<=count($jisuan_var);$i++){
            $userType=$this->getShenfenleibie($jisuan_var[$i]['shenfenleibie']);
            if($userType!=-1){
                $jisuan_var[$i]['yingfaheji']=$jisuan_var[$i]["addValue"]-$jisuan_var[$i]["delValue"];
                $jisuan_var[$i]['gerenshiye']=$this->jisuan_geren_shiye($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['gerenyiliao']=$this->jisuan_geren_yiliao($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['gerenyanglao']=$this->jisuan_geren_yanglao($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['gerengongjijin']=round($this->jisuan_geren_gongjijin($jisuan_var[$i]['gongjijinjishu']));
                $jisuan_var[$i]['daikousui']=$this->jisuan_daikousui_xin2011($jisuan_var[$i]);
                $jisuan_var[$i]['koukuanheji']=($jisuan_var[$i]['gerenshiye']+$jisuan_var[$i]['gerenyiliao']+$jisuan_var[$i]['gerenyanglao']+$jisuan_var[$i]['gerengongjijin']+$jisuan_var[$i]['daikousui']+$jisuan_var[$i]['freeTex']);
                $jisuan_var[$i]['shifaheji']=$jisuan_var[$i]['yingfaheji']-$jisuan_var[$i]['koukuanheji'];
                $jisuan_var[$i]['danweishiye']=$this->jisuan_danwei_shiye($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['danweigongshang']=$this->jisuan_danwei_gongshang($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['danweishengyu']=$this->jisuan_danwei_shengyu($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['danweiyanglao']=$this->jisuan_danwei_yanglao($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['danweiyiliao']=$this->jisuan_danwei_yiliao($jisuan_var[$i]['shebaojishu'],$userType);
                $jisuan_var[$i]['danweigongjijin']=round($this->jisuan_geren_gongjijin($jisuan_var[$i]['gongjijinjishu']));
                $jisuan_var[$i]['danweiheji']=($jisuan_var[$i]['danweishiye']+$jisuan_var[$i]['danweigongshang']+$jisuan_var[$i]['danweishengyu']+$jisuan_var[$i]['danweiyanglao']+$jisuan_var[$i]['danweiyiliao']+$jisuan_var[$i]['danweigongjijin']);
                $jisuan_var[$i]['jiaozhongqiheji']=$jisuan_var[$i]['yingfaheji']+$jisuan_var[$i]['danweishiye']+$jisuan_var[$i]['danweigongshang']+$jisuan_var[$i]['danweishengyu']+$jisuan_var[$i]['danweiyanglao']+$jisuan_var[$i]['danweigongjijin']+$jisuan_var[$i]['danweiyiliao']+$jisuan_var[$i]['laowufei']+$jisuan_var[$i]['canbaojin']+$jisuan_var[$i]['danganfei'];
            }else{
                $jisuan_var[$i]['yingfaheji']=0;
                $jisuan_var[$i]['gerenshiye']="错误";
                $jisuan_var[$i]['gerenyiliao']="错误";
                $jisuan_var[$i]['gerenyanglao']="错误";
                $jisuan_var[$i]['gerengongjijin']=0;
                $jisuan_var[$i]['daikousui']=0;
                $jisuan_var[$i]['koukuanheji']=0;
                $jisuan_var[$i]['shifaheji']=0;
                $jisuan_var[$i]['danweishiye']="错误";
                $jisuan_var[$i]['danweigongshang']="错误";
                $jisuan_var[$i]['danweishengyu']="错误";
                $jisuan_var[$i]['danweiyanglao']="错误";
                $jisuan_var[$i]['danweiyiliao']="错误";
                $jisuan_var[$i]['danweigongjijin']=0;
                $jisuan_var[$i]['danweiheji']="错误";
                $jisuan_var[$i]['jiaozhongqiheji']=0;
            }
        }
        //print_r($jisuan_var);
    }
    function getShenfenleibie($shenfenleibie){
        $userType=0;
        switch ($shenfenleibie){
            case "实习生";
                $userType=0;
                break;
            case "未缴纳保险";
                $userType=0;
                break;
            case "本市城镇职工";
                $userType=1;
                break;
            case "外埠城镇职工";
                $userType=2;
                break;
            case "本市农村劳动力";
                $userType=3;
                break;
            case "外地农村劳动力";
                $userType=4;
                break;
            case "本市农民工";
                $userType=5;
                break;
            case "外地农民工";
                $userType=6;
                break;
            default:
                $userType=-1;
        }
        return $userType;
    }
    /**
     * 2013-06-17养老、失业缴费基数下限为2089、工伤、生育、医疗的缴费基数下限为3134元
     * 2013年度各项保险缴费上限为15669
     * @param unknown_type $shebaojishu
     * @param unknown_type $userType
     */
    function jisuan_geren_shiye($shebaojishu,$userType){
        /**
         *
        //2013-06-17改为2089
         * //2014-7-15 基数修改
         * (F2="本市城镇职工",MAX(MIN(G2,17379),2317)*0.2%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),2317)*0.2%
        ,IF(F2="本市农村劳动力",0,IF(F2="外地农村劳动力",0,IF(F2="本市农民工",0,IF(F2="外地农民工",0,"错误"))))))
         *2015-7-20张超修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),2585)*0.2%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),2585)*0.2%,
         * IF(F2="本市农村劳动力",0,
         * IF(F2="外地农村劳动力",0,
         * IF(F2="本市农民工",0,
         * IF(F2="外地农民工",0,"错误"))))))
         *
         *  2016-07-06 基数2585改成2834
         *  2017-07-16 基数2834改成3082
         *  2018-07-12 基数3082改成3387
         *
         *     2016-07-06 基数19389改成21258
         *     2016-07-16 基数21258改成23118
         *     2018-07-12 基数23118改成25401
         *
         *
         */
        if($userType==1||$userType==2){
            $gerenshiye=$this->max($this->min($shebaojishu,25401),3387)*0.002;
        }else {
            $gerenshiye=0;
        }
        return $gerenshiye;
    }
    function jisuan_geren_yiliao($shebaojishu,$userType){
        /**
         * //2014-7-15 基数修改
         * =IF
        (F2="本市城镇职工",MAX(MIN(G2,17379),3476)*2%+3,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),3476)*2%+3,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),3476)*2%+3,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),3476)*2%+3,
        IF(F2="本市农民工",0,IF(F2="外地农民工",0,"错误"))))))
         *
         * 2015-7-20张超修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),3878)*2%+3,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),3878)*2%+3,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),3878)*2%+3,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),3878)*2%+3,
         * IF(F2="本市农民工",0,IF(F2="外地农民工",0,"错误"))))))
         *
         *   2016-07-06 基数3878改成4252
         *   2017-07-06 基数4252改成4624
         *
         *     2016-07-06 基数19389改成21258
         *     2017-07-19 基数21258改成23118
         * 设置上限23118，养老、失业下限3082，医疗、工伤、生育下限4624
         *
         * 2018-07-12 工伤、生育、医疗下限改为5080，上限改为25401
         *
         */
        if($userType==1||$userType==2||$userType==3||$userType==4){
            $gerenyiliao=$this->max($this->min($shebaojishu,25401),5080)*0.02+3;
        }else {
            $gerenyiliao=0;
        }
        return $gerenyiliao;
    }
    function jisuan_geren_yanglao($shebaojishu,$userType){
        /**
         *
        //2013-06-17改为2809
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),2317)*8%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),2317)*8%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),2317)*8%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),2317)*8%,
        IF(F2="本市农民工",MAX(MIN(G2,17379),2317)*8%,
        IF(F2="外地农民工",MAX(MIN(G2,17379),2317)*8%,"错误"))))))
         *
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),2585)*8%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),2585)*8%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),2585)*8%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),2585)*8%,
         * IF(F2="本市农民工",MAX(MIN(G2,19389),2585)*8%,
         * IF(F2="外地农民工",MAX(MIN(G2,19389),2585)*8%,"错误"))))))
         *
         * 2016-07-06 基数2585改成2834
         *    2016-07-06 基数19389改成21258
         * 2018-07-12 养老、失业下限改为3387，上限改为25401
         *
         */
        $gerenyanglao=$this->max($this->min($shebaojishu,25401),3387)*0.08;
        if($userType==0){
            $gerenyanglao=0;
        }
        return $gerenyanglao;
    }
    function jisuan_danwei_shiye($shebaojishu,$userType){
        /**
         *
        //2013-06-17改为2809
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),2317)*1%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),2317)*1%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),2317)*1%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),2317)*1%,
        IF(F2="本市农民工",MAX(MIN(G2,17379),2317)*1%,
        IF(F2="外地农民工",MAX(MIN(G2,17379),2317)*1%,"错误"))))))
         *
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),2585)*1%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),2585)*1%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),2585)*1%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),2585)*1%,
         * IF(F2="本市农民工",MAX(MIN(G2,19389),2585)*1%,
         * IF(F2="外地农民工",MAX(MIN(G2,19389),2585)*1%,"错误"))))))
         * 2016年6月23单位失业改为0.8%
         *
         * 2016-07-06 基数2585改成2834
         *
         * 2016-07-06 基数19389改成21258
         *
         *  2018-07-12 养老、失业下限改为3387，上限改为25401
         */
        //$danweishiye=$this->max($this->min($shebaojishu,19389),2585)*0.01;
        $danweishiye=$this->max($this->min($shebaojishu,25401),3387)*0.008;
        if($userType==0){
            $danweishiye=0;
        }
        return $danweishiye;
    }
    function jisuan_danwei_yiliao($shebaojishu,$userType){
        /**
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),3476)*10%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),3476)*10%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),3476)*10%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),3476)*10%,
        IF(F2="本市农民工",3476*1%,IF(F2="外地农民工",3476*1%,"错误"))))))
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),3878)*10%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),3878)*10%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),3878)*10%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),3878)*10%,
         * IF(F2="本市农民工",3878*1%,
         * IF(F2="外地农民工",3878*1%,"错误"))))))
         *
         *    2016-07-06 基数3878改成4252
         * 2016-07-06 基数19389改成21258
         * 2018-07-12  工伤、生育、医疗下限改为5080，上限改为25401
         *
         */
        if($userType==1||$userType==2||$userType==3||$userType==4){
            $danweiyiliao=$this->max($this->min($shebaojishu,25401),5080)*0.1;
        }else{
            $danweiyiliao=3476*0.01;

        }
        if($userType==0){
            $danweiyiliao=0;
        }
        return $danweiyiliao;
    }
    function jisuan_danwei_yanglao($shebaojishu,$userType){
        /**
         *
        //2013-06-17改为2809
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),2317)*20%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),2317)*20%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),2317)*20%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),2317)*20%,
        IF(F2="本市农民工",MAX(MIN(G2,17379),2317)*20%,
        IF(F2="外地农民工",MAX(MIN(G2,17379),2317)*20%,"错误"))))))
         *
         *
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),2585)*20%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),2585)*20%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),2585)*20%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),2585)*20%,
         * IF(F2="本市农民工",MAX(MIN(G2,19389),2585)*20%,
         * IF(F2="外地农民工",MAX(MIN(G2,19389),2585)*20%,"错误"))))))
         *
         * 2016年6月23日单位养老改为19%
         *
         *  2016-07-06 基数2585改成2834
         * 2016-07-06 基数19389改成21258
         *
         *  2018-07-12 养老、失业下限改为3387，上限改为25401
         */
        //$danweiyanglao=$this->max($this->min($shebaojishu,19389),2585)*0.2;
        $danweiyanglao=$this->max($this->min($shebaojishu,25401),3387)*0.19;
        if($userType==0){
            $danweiyanglao=0;
        }
        return $danweiyanglao;
    }
    function jisuan_danwei_gongshang($shebaojishu,$userType){
        /**
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="本市农民工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外地农民工",MAX(MIN(G2,17379),3476)*0.8%,"错误"))))))
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="本市农民工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外地农民工",MAX(MIN(G2,19389),3878)*0.8%,"错误"))))))
         *
         *  2016-07-06 基数3878改成4252
         * 2016-07-06 基数19389改成21258
         * 2016-09-20 费率0.8%改为0.4%
         * 2018-07-12  工伤、生育、医疗下限改为5080，上限改为25401 工伤比例改为0.8%
         *
         */
        /*if($userType==1||$userType==2){
            $danweigongshang=$this->max($this->min($shebaojishu,17379),3476)*0.008;
        }else{
            $danweigongshang=$this->max($this->min($shebaojishu,15669),3134)*0.008;
        }*/
        $danweigongshang=$this->max($this->min($shebaojishu,25401),5080)*0.008;
        //echo $danweigongshang."||".$shebaojishu."||".$userType."<br/>";
        if($userType==0){
            $danweigongshang=0;
        }
        return $danweigongshang;
    }
    function jisuan_danwei_shengyu($shebaojishu,$userType){
        /**
         * =IF(F2="本市城镇职工",MAX(MIN(E2,14016),2803)*0.8%,
        IF(F2="外埠城镇职工",0,
        IF(F2="本市农村劳动力",MAX(MIN(E2,14016),2803)*0.8%,
        IF(F2="外地农村劳动力",0,
        IF(F2="本市农民工",MAX(MIN(E2,14016),2803)*0.8%,
        IF(F2="外地农民工",0,"错误"))))))
         * //2014-7-15 基数修改
         * =IF(F2="本市城镇职工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外埠城镇职工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="本市农村劳动力",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外地农村劳动力",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="本市农民工",MAX(MIN(G2,17379),3476)*0.8%,
        IF(F2="外地农民工",MAX(MIN(G2,17379),3476)*0.8%,"错误"))))))
         *
         * =IF(F2="本市城镇职工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外埠城镇职工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="本市农村劳动力",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外地农村劳动力",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="本市农民工",MAX(MIN(G2,19389),3878)*0.8%,
         * IF(F2="外地农民工",MAX(MIN(G2,19389),3878)*0.8%,"错误"))))))
         *
         *    2016-07-06 基数3878改成4252
         * 2016-07-06 基数19389改成21258
         *
         * 2018-07-12  工伤、生育、医疗下限改为5080，上限改为25401
         */
        //if($userType==1||$userType==3||$userType==5){
        $danweishengyu=$this->max($this->min($shebaojishu,25401),5080)*0.008;
        //}else{
        //   $danweishengyu=0;
        //}
        if($userType==0){
            $danweishengyu=0;
        }
        return $danweishengyu;
    }
    function jisuan_geren_gongjijin($nums){
        /**
         * 2014-7-16
         * 设置公积金基数
         * 2015修改公积金基数
         * 19389上限
         *
         * 2016-07-06 上限19389改成21258
         * 2018-07-06 上限21258改成25401
         * 2016-12-23 公积金最低基数调到了2148元
         * 2018-07-26 公积金最低基数调到了2273元
         */
        if ($nums < 2273 && $nums != 0) {
            $nums = 2273;
        } elseif ($nums > 25401) {
            $nums = 25401;
        }
        return $nums*0.12;
    }
    function jisuan_daikousui($jisuan_var){//方法无效
        /**
         * 2. 工资起征点为3500元，工资总额减去3500元，减去保险公积金扣款所得的差
         * 小于等于1500，乘以3%；
        大于1500小于等于4500，乘以10%再减去105；
        大于4500小于等于9000，乘以20%再减去555；
        大于9000小于等于35000，乘以25%再减去1005；
        大于35000小于等于55000，乘以30%再减去2755；
        大于55000小于等于80000，乘以35%再减去5505；
        大于80000，乘以45%再减去13505
         */

        $values=$jisuan_var['yingfaheji']-($jisuan_var['gerenshiye']+$jisuan_var['gerenyiliao']+$jisuan_var['gerenyanglao']+$jisuan_var['gerengongjijin']+3500);
        //echo $jisuan_var['yingfaheji']."/////////////".$values.">>>>>>>>>>>>>>><br />";
        if($values<=1500){
            $values=$values*0.03;
        }elseif($values>1500&&$values<=4500){
            $values=$values*0.1-105;
        }elseif($values>4500&&$values<=9000){
            $values=$values*0.2-555;
        }elseif($values>9000&&$values<=35000){
            $values=$values*0.25-1005;
        }elseif($values>35000&&$values<=55000){
            $values=$values*0.3-2755;
        }elseif($values>55000&&$values<=80000){
            $values=$values*0.35-5505;
        }elseif($values>80000){
            $values=$values*0.45-13505;
        }
        if($values<0){
            $values=0;
        }
        return $values;
    }
    function jisuan_daikousui_xin2011($jisuan_var){
        /**
         * 工资起征点为3500元，工资总额减去3500元，减去保险公积金扣款所得的差
         * 小于等于1500，乘以3%；
        大于1500小于等于4500，乘以10%再减去105；
        大于4500小于等于9000，乘以20%再减去555；
        大于9000小于等于35000，乘以25%再减去1005；
        大于35000小于等于55000，乘以30%再减去2755；
        大于55000小于等于80000，乘以35%再减去5505；
        大于80000，乘以45%再减去13505
         */
        if(empty($jisuan_var['freeTex'])){
            $jisuan_var['freeTex']=0.00;
        }
        $values=$jisuan_var['yingfaheji']-($jisuan_var['gerenshiye']+$jisuan_var['gerenyiliao']+$jisuan_var['gerenyanglao']+$jisuan_var['gerengongjijin']+$jisuan_var['freeTex']+3500);
        if($values<=1500){
            $values=$values*0.03;
        }elseif($values>1500&&$values<=4500){
            $values=$values*0.1-105;
        }elseif($values>4500&&$values<=9000){
            $values=$values*0.2-555;
        }elseif($values>9000&&$values<=35000){
            $values=$values*0.25-1005;
        }elseif($values>35000&&$values<=55000){
            $values=$values*0.3-2755;
        }elseif($values>55000&&$values<=80000){
            $values=$values*0.35-5505;
        }elseif($values>80000){
            $values=$values*0.45-13505;
        }
        if($values<0){
            $values=0;
        }
        return $values;
    }
    function max($num1,$num2){
        if($num1>$num2){
            return $num1;
        }else{
            return $num2;
        }
    }
    function min($num1,$num2){
        if($num1<$num2){
            return $num1;
        }else{
            return $num2;
        }
    }

    //FIXME 计算年终奖
    function sumNianSal(&$jisuan_var){
        $cha=0.0;
        if($jisuan_var['shifaheji']<3500){
            $cha=3500-$jisuan_var['shifaheji'];
        }
        $pingjun=($jisuan_var['nianzhongjiang']-$cha)/12;

        if($pingjun<=1500){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.03;
        }elseif($pingjun>1500&&$pingjun<=4500){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.1-105;
        }elseif($pingjun>4500&&$pingjun<=9000){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.2-555;
        }elseif($pingjun>9000&&$pingjun<=35000){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.25-1005;
        }elseif($pingjun>35000&&$pingjun<=55000){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.3-2755;
        }elseif($pingjun>55000&&$pingjun<=80000){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.35-5505;
        }elseif($pingjun>80000){
            $jisuan_var['niandaikoushui']=($jisuan_var['nianzhongjiang']-$cha)*0.45-13505;
        }
        if($jisuan_var['niandaikoushui']<0){
            $jisuan_var['niandaikoushui']=0;
        }
        $jisuan_var['shifajinka']=$jisuan_var['nianzhongjiang']-$jisuan_var['niandaikoushui'];
        $jisuan_var['jiaozhongqi']=$jisuan_var['nianzhongjiang'];

    }
    function sumErSal(&$jisuan_var){
        /**
         * $jisuan_var['ercigongziheji']=$addValue;
        $jisuan_var['yingfaheji']=$employ['per_yingfaheji'];
        $jisuan_var['shijiyingfaheji']=$jisuan_var['ercigongziheji']+$jisuan_var['yingfaheji'];
        $jisuan_var['shiye']=$employ['per_shiye'];
        $jisuan_var['yiliao']=$employ['per_yiliao'];
        $jisuan_var['yanglao']=$employ['per_yanglao'];
        $jisuan_var['gongjijin']=$employ['per_gongjijin'];
        $jisuan_var['yikoushui']=$employ['per_daikoushui'];
        //失业	医疗	养老	公积金	应扣税	已扣税	补扣税	2010年1次双薪进卡	缴中企基业合计
         */
        $values=$jisuan_var['shijiyingfaheji']-$jisuan_var['shiye']-$jisuan_var['yiliao']-$jisuan_var['yanglao']-$jisuan_var['gongjijin']-3500;
        if($values<=1500){
            $values=$values*0.03;
        }elseif($values>1500&&$values<=4500){
            $values=$values*0.1-105;
        }elseif($values>4500&&$values<=9000){
            $values=$values*0.2-555;
        }elseif($values>9000&&$values<=35000){
            $values=$values*0.25-1005;
        }elseif($values>35000&&$values<=55000){
            $values=$values*0.3-2755;
        }elseif($values>55000&&$values<=80000){
            $values=$values*0.35-5505;
        }elseif($values>80000){
            $values=$values*0.45-13505;
        }
        if($values<0){
            $values=0;
        }
        $jisuan_var['yingkoushui']=$values;
        $jisuan_var['bukoushui']=$jisuan_var['yingkoushui']-$jisuan_var['yikoushui'];
        $jisuan_var['shuangxinjinka']=$jisuan_var['ercigongziheji']-$jisuan_var['bukoushui'];
        $jisuan_var['jiaozhongqi']=$jisuan_var['ercigongziheji'];
    }
}
?>