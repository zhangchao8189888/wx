<?php
/**
 * 派遣管理
 *
 */
class SocialController extends FController
{
    private $m_social_model;
    private $m_social_exception_model;
    private $m_gjjin_model;
    private $customer_model;
    private $m_employ_model;
    private $m_employ_construct_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->m_social_model = new MSocial();
        $this->m_social_exception_model = new MSocialException();
        $this->m_gjjin_model = new MGjjin();
        $this->customer_model = new Customer();
        $this->m_employ_model = new MEmploy();
        $this->m_employ_construct_model = new MEmployConstruct();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }
    //增加社保视图
    public function actionGetSocialList () {

        $this->render('socialList');
    }
    //增加社保
    public function actionSaveSocialList () {
        $error_list = array();
        $success_list = array();
        $table_data = $this->request->getParam("data");
        $date = $this->request->getParam("date")."-01";
        foreach ($table_data as $key=>$row) {
            $condition = array(
                'condition' => 'customer_name=:customer_name',
                'params' => array(':customer_name'=>$row[1]),
            );
            $res = $this->customer_model->find($condition);
            if (!empty($res)) {
                $e_company_id = $res->id;
                $c = new EMongoCriteria;
                $c->e_num = $row[3];
                $employ = $this->m_employ_model->find($c);
                if (!empty($employ)) {

                    $this->m_social_model = new MSocial();
                    $this->m_social_model->e_company_id = $e_company_id;
                    $this->m_social_model->e_name = $employ->e_name;
                    $this->m_social_model->e_num = $employ->e_num;
                    $this->m_social_model->e_type = $employ->e_type;
                    $this->m_social_model->e_address = $row[7];
                    $this->m_social_model->e_social_base = $row[5];
                    $this->m_social_model->is_new_social = $row[6];
                    $this->m_social_model->e_memo = $row[8];
                    $this->m_social_model->add_status = 1;
                    $this->m_social_model->sub_status = 0;
                    $this->m_social_model->belong_company_name = $row[0];
                    $this->m_social_model->date = $date;
                    $this->m_social_model->save();
                    $error = $this->m_social_model->getErrors();
                    if (!empty($error)){
                        $error_list[] = array(
                            'key' => $key,
                            'message' =>$error['message']
                        );
                    } else {
                        $success_list[] = $key;
                    }
                } else {
                    $error_list[] = array(
                        'key' => $key,
                        'message' =>$row[3]."身份证不存在\n"
                    );
                }
            } else {
                $error_list[] = array(
                    'key' => $key,
                    'message' =>$row[1]."单位不存在\n"
                );
            }
        }
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['error_list'] = array_slice($error_list,0,-1);
            $response['content']['success_list'] = $success_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    // ajax 社保增员
    public function actionAjaxAddSocial () {
        $eNum = $this->request->getParam('e_num'); // 身份证号
        $social_base = $this->request->getParam('social_base'); // 社保基数
        $add_date = $this->request->getParam('add_date')."-01"; // 增加日期
        $memo = $this->request->getParam('memo'); // 备注
        $employ = $this->getEmploy($eNum);
        $this->m_social_model->e_company_id = $employ->e_company_id;
        $this->m_social_model->e_name = $employ->e_name;
        $this->m_social_model->e_num = $employ->e_num;
        $this->m_social_model->e_type = $employ->e_type;
        $this->m_social_model->e_social_base = $social_base;
        $this->m_social_model->add_status = 1;
        $this->m_social_model->sub_status = 0;
        $this->m_social_model->date = $add_date;
        $this->m_social_model->e_memo = $memo;
        if ($this->m_social_model->save()) {
            $params['status'] = 100000;
            $params['content'] = '增员成功';
        } else {
            $error = $this->m_social_model->getError("message");
            $params['status'] = 100001;
            $params['content'] = '增员失败'.$error;
        }
        Yii::app()->end(FHelper::json($params['content'], $params['status']));
    }
    // ajax 社保减员
    public function actionAjaxSubSocial () {
        $id = $this->request->getParam('e_num');
        $c = new EMongoCriteria();
        $c->e_num = $id;
        $res = $this->m_social_model->find($c);
        if (!$res) {
            $response['status'] = 100001;
            $response['content'] = '身份证不存在！';

            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $this->m_social_model = new MSocial('update');

        $this->m_social_model->_id = $res->_id;
        $this->m_social_model->sub_status = 1;
        $attr = array('sub_status');
        $this->m_social_model->setIsNewRecord(false);
        $this->m_social_model->update($attr,true);
        $error = $this->m_social_model->getErrors();
        if (!empty($error)) {
            $response['status'] = 100001;
            $response['content'] = $error;
        } else {
            $response['status'] = 100000;
            $response['content'] = '减员成功';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    // ajax 公积金增员
    public function actionAjaxAddGjjin () {
        $eNum = $this->request->getParam('e_num'); // 身份证号
        $social_base = $this->request->getParam('social_base'); // 社保基数
        $add_date = $this->request->getParam('add_date')."-01"; // 增加日期
        $memo = $this->request->getParam('memo'); // 备注
        $employ = $this->getEmploy($eNum);
        $this->m_gjjin_model->e_company_id = $employ->e_company_id;
        $this->m_gjjin_model->e_name = $employ->e_name;
        $this->m_gjjin_model->e_num = $employ->e_num;
        $this->m_gjjin_model->e_type = $employ->e_type;
        $this->m_gjjin_model->e_gjjin_base = $social_base;
        $this->m_gjjin_model->add_status = 1;
        $this->m_gjjin_model->sub_status = 0;
        $this->m_gjjin_model->date = $add_date;
        $this->m_gjjin_model->e_memo = $memo;
        if ($this->m_gjjin_model->save()) {
            $params['status'] = 100000;
            $params['content'] = '增员成功';
        } else {
            $error = $this->m_gjjin_model->getError("message");
            $params['status'] = 100001;
            $params['content'] = '增员失败'.$error;
        }
        Yii::app()->end(FHelper::json($params['content'], $params['status']));
    }
    // ajax 公积金减员
    public function actionAjaxSubGjjin(){
        $id = $this->request->getParam('id');

        $this->m_gjjin_model = new MGjjin('update');
        $this->m_gjjin_model->_id = new MongoId($id);
        $this->m_gjjin_model->add_status = 0;
        $this->m_gjjin_model->sub_status = 1;
        $attr = array('add_status','sub_status');
        $this->m_gjjin_model->setIsNewRecord(false);
        $this->m_gjjin_model->update($attr,true);
        $error = $this->m_gjjin_model->getErrors();
        if (!empty($error)) {
            $response['status'] = 100001;
            $response['content'] = $error;
        } else {
            $response['status'] = 100000;
            $response['content'] = '减员成功';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    // 通过身份证查员工表
    public function getEmploy ($eNum) {
        $c = new EMongoCriteria;
        $c->e_num = $eNum;
        $employ = $this->m_employ_model->find($c);
        if (empty($employ->attributes)) {
            $params['status'] = 100001;
            $params['content'] = '该员工不存在';
            Yii::app()->end(FHelper::json($params['content'], $params['status']));
        }
        return $employ;
    }
    //增加公积金视图
    public function actionGetGjjinList(){
        $this->render("gjjin");
    }
    //增加公积金
    public function actionSaveGjjinList(){
        $error_list = array();
        $success_list = array();
        $table_data = $this->request->getParam("data");
        $date = $this->request->getParam("date")."-01";
        foreach ($table_data as $key=>$row) {
            $condition = array(
                'condition' => 'customer_name=:customer_name',
                'params' => array(':customer_name'=>$row[1]),
            );
            $res = $this->customer_model->find($condition);
            if (!empty($res)) {
                $e_company_id = $res->id;
                $c = new EMongoCriteria;
                $c->e_num = $row[3];
                $employ = $this->m_employ_model->find($c);
                if (!empty($employ)) {

                    $this->m_gjjin_model = new MGjjin();
                    $this->m_gjjin_model->e_company_id = $e_company_id;
                    $this->m_gjjin_model->e_name = $employ->e_name;
                    $this->m_gjjin_model->e_num = $employ->e_num;
                    $this->m_gjjin_model->e_type = $employ->e_type;
                    $this->m_gjjin_model->e_address = $row[7];
                    $this->m_gjjin_model->e_gjjin_base = $row[5];
                    $this->m_gjjin_model->is_new_gjjin = $row[6];
                    $this->m_gjjin_model->e_memo = $row[8];
                    $this->m_gjjin_model->add_status = 1;
                    $this->m_gjjin_model->sub_status = 0;
                    $this->m_gjjin_model->belong_company_name = $row[0];
                    $this->m_gjjin_model->date = $date;
                    $this->m_gjjin_model->save();
                    $error = $this->m_gjjin_model->getErrors();
                    if (!empty($error)){
                        $error_list[] = array(
                            'key' => $key,
                            'message' =>$error['message']
                        );
                    } else {
                        $success_list[] = $key;
                    }
                } else {
                    $error_list[] = array(
                        'key' => $key,
                        'message' =>$row[3]."身份证不存在\n"
                    );
                }
            } else {
                $error_list[] = array(
                    'key' => $key,
                    'message' =>$row[1]."单位不存在\n"
                );
            }
        }
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['error_list'] = array_slice($error_list,0,-1);
            $response['content']['success_list'] = $success_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    //查询社保视图
    public function actionShowSocialList(){
        //查询公司
        $data=$this->actionSearchCompany();
        $this->render("showSocialList",$data);
    }
    //查询公积金视图
    public function actionShowGjjinList(){
        //查询公司
        $data=$this->actionSearchCompany();
        $this->render("showGjjinList",$data);
    }
    //查询社保和公积金
    public function actionGetData(){
        $type=$this->request->getParam("type");//按类别查询[公积金/社保]
        $is_reduce=$this->request->getParam("isReduce");//是否减员
        $model="m_".$type."_model";
        $title="e_".$type."_base";
        $title2="is_new_".$type;
        $companyID=$this->request->getParam("companyID");//按公司查询
        $time=$this->request->getParam("time");//按时间查询
        $c = new EMongoCriteria;
        if(empty($companyID) && !empty($time)){
            $c->date = $time;
            $status=1;//增员查0,减员查1
            if($is_reduce=="add"){
                $c->add_status = 1;
                $status="";
            }
            $c->sub_status = $status;
            //查询所有的信息
            $res=$this->$model->findAll($c);
        }else if(empty($companyID) && empty($time)){
            $status=1;//增员查0,减员查1
            if($is_reduce=="add"){
                $c->add_status = 1;
                $status="";
            }
            $c->sub_status = $status;
            //查询所有的信息
            $res=$this->$model->findAll($c);
        }else{
            if(empty($time)){
                $c->e_company_id = $companyID;
                $status=1;//增员查0,减员查1
                if($is_reduce=="add"){
                    $c->add_status = 1;
                    $status="";
                }
                $c->sub_status = $status;
            }else{
                $c->e_company_id = $companyID;
                $c->date = $time;
                $status=1;//增员查0,减员查1
                if($is_reduce=="add"){
                    $c->add_status = 1;
                    $status="";
                }
                $c->sub_status = $status;
            }

            $res=$this->$model->findAll($c);
        }
        if(!empty($res)){
            $data=array();
            foreach($res as $v){
                $po = array();
                $this->customer_model = new Customer();
                $po['row_id']=$v->_id->{'$id'};
                $po['e_company_id']=$v->e_company_id;
                $po['belong_company_name']=$v->belong_company_name;
                $comID = empty($companyID)?$v->e_company_id:$companyID;
                $po['company_name']=$this->customer_model->findByPk($comID)->customer_name;
                $po['e_name']=$v->e_name;
                $po['e_num']=$v->e_num;
                $po['e_type']=$v->e_type;
                $po["$title"]=$v->$title;
                $po['e_address']=empty($v->e_address) ||$v->e_address=="null" ?"":$v->e_address;
                $po['e_memo']=empty($v->e_memo) ||$v->e_memo=="null" ?"":$v->e_memo;
                $po["$title2"]=empty($v->$title2) || $v->$title2=="null"?"":$v->$title2;
                $po["date"]=$v->date;
                $data[] = $po;
            }
            if(!empty($data)){
                $response['status'] = 100000;
                $response['content'] = $data;
            }else{
                $response['status'] = 100001;
                $response['content'] = '操作失败！';
            }
        }else{
            $response['status'] = 100001;
            $response['content'] = '暂无数据！';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
    //查询社保和公积金 异常
    public function actionAjaxSocialException(){
        $type=$this->request->getParam("type");//按列别查询[公积金/社保]
        $date=$this->request->getParam("date");//按时间查询
        $c = new EMongoCriteria;
        if(empty($date)){
            $date = date('Y-m-d');
        }
        $c->date = $date;
        if ($type > 0) {
            $c->type = $type=='1' ? '社保' :  '公积金';
        }

        $c->sort('_id',EMongoCriteria::SORT_ASC);
        //查询所有的信息
        $res=$this->m_social_exception_model->findAll($c);
        if(!empty($res)){
            $data=array();
            foreach($res as $v){
                $po = array();
                $this->customer_model = new Customer();
                $po['row_id']=$v->_id->{'$id'};
                $po['company_name']=$v->company_name;
                $po['belong_company_name']=$v->belong_company_name;
                $po['section']=$v->section;
                $po['e_name']=$v->e_name;
                $po['e_num']=$v->e_num;
                $po['date_month']=$v->date_month;
                $po['check_man']=$v->check_man;
                $po['exception_note']=$v->exception_note;
                $po['type']=$v->type;
                $po['date']=$v->date;

                $data[] = $po;
            }
            if(!empty($data)){
                $response['status'] = 100000;
                $response['content'] = $data;
            }else{
                $response['status'] = 100001;
                $response['content'] = '操作失败！';
            }
        }else{
            $response['status'] = 100001;
            $response['content'] = '暂无数据！';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }

    /**
     * 社保/公积金异常情况申请
     */
    public function actionShowSocialException (){
        $this->render("showSocialException");
    }
    /**
     * 保存社保/公积金异常
     */
    public function actionSaveSocialException(){
        $error_list = array();
        $success_list = array();
        $table_data = $this->request->getParam("data");
        foreach ($table_data as $key=>$row) {
            $c = new EMongoCriteria;
            $c->e_num = $row['e_num'];
            $employ = $this->m_employ_model->find($c);
            if (!empty($employ)) {
                if (empty($row['row_id']) || $row['row_id'] == 'null') {
                    $mod = 'insert';
                } else {
                    $mod = 'update';
                }
                $this->m_social_exception_model = new MSocialException($mod);
                $this->m_social_exception_model->belong_company_name = $row['belong_company_name'];
                $this->m_social_exception_model->company_name = $row['company_name'];
                $this->m_social_exception_model->section = $row['section'];
                $this->m_social_exception_model->e_name = $row['e_name'];
                $this->m_social_exception_model->e_num = $row['e_num'];
                $this->m_social_exception_model->date_month = $row['date_month'];
                $this->m_social_exception_model->check_man = $row['check_man'];
                $this->m_social_exception_model->exception_note = $row['exception_note'];
                $this->m_social_exception_model->type = $row['type'];
                if (empty($row['row_id']) || $row['row_id'] == 'null') {
                    $this->m_social_exception_model->setIsNewRecord(true);
                    $this->m_social_exception_model->date = date('Y-m-d');
                } else {
                    $this->m_social_exception_model->setIsNewRecord(false);
                    $this->m_social_exception_model->_id = new MongoId($row['row_id']);
                    $this->m_social_exception_model->date = $row['date'];
                }
                $this->m_social_exception_model->save();
                $error = $this->m_social_exception_model->getErrors();
                if (!empty($error)){
                    $error_list[] = array(
                        'key' => $key,
                        'message' =>$error['message'][0]
                    );
                } else {
                    $success_list[] = $key;
                }
            } else {
                $error_list[] = array(
                    'key' => $key,
                    'message' =>$row[3]."身份证不存在\n"
                );
            }
        }
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['error_list'] = $error_list;
            $response['content']['success_list'] = $success_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content'] = '保存成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    //修改社保表和公积金表
    public function actionAjaxUpdateSocial(){
        $type=$this->request->getParam("type");//按列别查询[公积金/社保]
        $model="m_".$type."_model";
        $newModel="M".ucfirst($type);
        $base="e_".$type."_base";
        $base2="e_".$type."_base";
        $is_new="is_new_".$type;
        $is_new2="is_new_".$type;
        $eMemo="e_memo";
        //接收数据
        $data=$this->request->getParam("data");
        foreach($data as  $v){
            $row_id=$v['row_id'];
            $base_data = $v[$base];
            $is_new_data = $v[$is_new];
            $beizhu = $v['e_memo'];
            $this->$model = new $newModel('update');
            $this->$model->_id = new MongoId($row_id);
            $this->$model->$base2 = $base_data;
            $this->$model->$is_new2 = $is_new_data;
            $this->$model->$eMemo = $beizhu;
            $attr = array("$base2","$is_new2","$eMemo");
            $this->$model->setIsNewRecord(false);
            $res = $this->$model->update($attr,true);
            $error = $this->$model->getErrors();
        }
        if (!empty($error)) {
            $response['status'] = 100001;
            $response['content'] = $error;
        } else {
            $response['status'] = 100000;
            $response['content'] = '保存成功';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
    //社保减员列表
    public function actionShowSocialReduceList(){
        //查询公司
        $data=$this->actionSearchCompany();
        //载入视图
        $this->render("showSocialReduceList",$data);
    }
    //公积金减员列表
    public function actionShowGjjinReduceList(){
        $data=$this->actionSearchCompany();
        //载入视图
        $this->render("showGjjinReduceList",$data);
    }


}