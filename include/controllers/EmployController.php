<?php
/**
 * 用户列表
 *
 */
class EmployController extends FController
{
    private $employ_model;
    private $employInfo_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->employ_model = new Employ();
        $this->employInfo_model = new EmployInfo();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }
    public function actionIndex(){
        $model = new MongoTest();
        $model->addInfo();
        $res = $model->findAll();
        print_r($res);
    }
    public function actionDelEmploy() {
        $id = $this->request->getParam("ids");
        $employ_model = new Employ();
        $result = $employ_model->deleteByPk($id);

        if ($result) {
            $response['content'] = "删除成功";
            $response['status'] = "100000";
        } else {
            $response['content'] = "删除失败";
            $response['status'] = "100001";
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionWorkOut() {
        $id = $this->request->getParam("row_id");
        $employ_model = new Employ();
        $condition_arr = array(
            '$e_status' => 2,

        );
        $result = $employ_model->updateByPk($id,$condition_arr);

        if ($result) {
            $response['content'] = "修改成功";
            $response['status'] = "100000";
        } else {
            $response['content'] = "修改失败";
            $response['status'] = "100001";
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionSaveEmploy(){
        $employ_model = new Employ();

        $e_type = FConfig::item('config.employ_type');
        $condition_arr = array(
            'e_type' => $this->request->getParam("e_type"),
            'e_type_name' => $e_type[$this->request->getParam("e_type")],
            'bank_name' => $this->request->getParam("bank_name"),
            'bank_num' => $this->request->getParam("bank_num"),
            'shebaojishu' => $this->request->getParam("shebaojishu"),
            'gongjijinjishu' => $this->request->getParam("gongjijinjishu"),
            'laowufei' => $this->request->getParam("laowufei"),
            'canbaojin' => $this->request->getParam("canbaojin"),
            'danganfei' => $this->request->getParam("danganfei"),
            'memo' => $this->request->getParam("memo"),

        );
        $id = $this->request->getParam("row_id");
        if (empty($id)) {
            $condition_arr['e_company_id'] =  $this->request->getParam("e_company_id");
            $company = Customer::model()->findByPk($condition_arr['e_company_id']);
            $condition_arr['e_company'] =  $company->customer_name;
            $condition_arr['e_name'] =  $this->request->getParam("e_name");
            $condition_arr['e_num'] =  $this->request->getParam("e_num");
            $condition_arr['e_num'] = trim($condition_arr['e_num']);
            $condition_arr['e_num'] = trim($condition_arr['e_num']);
            $condition_arr['update_time'] = date("Y-m-d H:i:s",time());
            $condition_arr['e_type_name'] = $e_type[$condition_arr['e_type']];
            $employ = $this->employ_model->find('e_num = :e_num',array(":e_num"=>$condition_arr['e_num']));
            if ($employ) {
                $response['content'] = $condition_arr['e_name'].":".$condition_arr['e_num']."身份身份证号已经添加过了！";
                $response['status'] = "100001";
                Yii::app()->end(FHelper::json($response['content'],$response['status']));
            }
            $employ_model->attributes = $condition_arr;
            $result = $employ_model->save();

        } else {
            $result = Employ::model()->updateByPk($id,$condition_arr);
            //print_r($employ_model);
            //print_r($id);
        }
        if ($result) {
            $response['content'] = "修改成功";
            $response['status'] = "100000";
        } else {
            $response['content'] = "修改失败";
            $response['status'] = "100001";
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionToImportEmploy () {
        $data["companyList"] = $this->getAdminCompanyList();
        $data['company']=Customer::model()->findAll(array(
            'select'=>'id,customer_name'
        ));
        $this->render('employImport',$data);
    }
    public function actionEmployImportAjax () {
        $data = $this->request->getParam("data");
        $errorList = array();
        $successList = array();
        $userType = FConfig::item("config.employ_type_val");
        foreach ($data as $val) {
            $val['e_num'] = trim($val['e_num']);
            $employ = $this->employ_model->find('e_num = :e_num',array(":e_num"=>$val['e_num']));
            if ($employ) {
                $errorList[] = $val['e_name'].":".$val['e_num']."身份身份证号已经添加过了！";
                continue;
            }
            $val['e_type_name'] = trim($val['e_type_name']);
            if (!isset($userType[$val['e_type_name']])) {
                $errorList[] = $val['e_name'].":".$val['e_type_name']."身份类别不存在！";
                continue;
            }
            $val['e_type'] = $userType[$val['e_type_name']];
            $res = Customer::model()->find("customer_name = :customer_name",array(":customer_name"=>$val['e_company']));
            if (!$res) {
                $errorList[] = $val['e_name'].":".$val['e_company']."公司名称不存在！";
                continue;
            }
            $val['e_company_id'] = $res->id;
            $val['canbaojin'] = empty($val['canbaojin']) ? 0 : trim($val['canbaojin']);
            $val['danganfei'] = empty($val['danganfei']) ? 0 : trim($val['danganfei']);
            $val['laowufei'] = empty($val['laowufei']) ? 0 : trim($val['laowufei']);
            $val['e_hetongnian'] = empty($val['e_hetongnian']) ? 0 : intval(trim($val['e_hetongnian']));
            $val['update_time'] = date("Y-m-d H:i:s",time());
            $employ_model = new Employ();
            $employ_model->attributes = $val;
            $result = $employ_model->save();
            if (!$result) {
                print_r($employ_model);
                $errorList[] = $val['e_name'].":".$val['e_company']."导入失败请重试，或联系管理员！";
                continue;
            } else {
                $successList[] = $val['e_name'].":".$val['e_company']."导入成功！";
            }

        }
        $res_data['error'] = $errorList;
        $res_data['success'] = $successList;
        echo json_encode($res_data);
        exit;
    }
    public function actionToEmployList () {
        $data["companyList"] = $this->getAdminCompanyList();
        $this->render('employList',$data);
    }
    public function actionGetEmployListAjax () {
        $companyId = $this->request->getParam("company_id");
        $e_name = $this->request->getParam('e_name');
        $e_num = $this->request->getParam('e_num');
        if(!empty($e_num)){

            $condition['condition'] = "e_num = :e_num";

            $condition['params'] = array(":e_num" => $e_num);
        } elseif (!empty($e_name)) {
            $condition['condition']     = "e_name  like '%{$e_name}%'";
            //$condition['params'] = array(":e_name" => $e_name);
        } else {

            $condition['condition'] = "e_company_id = :e_company_id";

            $condition['params'] = array(":e_company_id" => $companyId);
        }
        $employList = $this->employ_model->findAll($condition);
        $list = array();
        foreach ($employList as $v) {
            $list[] = $v->getAttributes();
        }
        if ($employList) {
            $response['content'] = $list;
            $response['status'] = "100000";
        } else {
            $response['content'] = "暂无员工";
            $response['status'] = "100001";
        }

        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionUpdateEmployList () {
        $updateData = $this->request->getParam("data");
        $bool = true;
        foreach ($updateData as $update) {
            $attribute = $update;
            $result = $this->employ_model->updateByPk($update['id'],$attribute);
            if (!$result) {
                print_r($this->employ_model);
                $bool = false;
            }
        }
        if ($bool) {
            $response['content'] = "修改成功";
            $response['status'] = "100000";
        } else {
            $response['content'] = "修改失败";
            $response['status'] = "100001";
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionEmployList () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'condition'=>"e_company_id= 8",
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //分页
        $data['count'] = $this->employ_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $data['employ_status'] = FConfig::item("config.employ_status");

        $data['empList'] = $this->employ_model->findAll($condition_arr);
        $data['page'] = $pages;
        $this->render('employList',$data);
    }
    public function actionGetEmployInfo () {
        $this->layout = 'main_no_menu';
        $id = $this->request->getParam('eid');
        $res = $this->employ_model->findByPk($id);
        $data['employInfo'] = $res;
        $data['employ_type'] = FConfig::item("config.employ_type");

        $this->render('employInfo',$data);
    }
    public function actionGetEmployByIds () {
        $this->layout = 'main_no_menu';
        $ids = $this->request->getParam('ids');
        //$type = $this->request->getParam('type');
        $type = 3;
        $ids = explode("e",$ids);
        foreach($ids as $val){
            $res = $this->employ_model->findByPk($val);
            if ($res) {
                $data['empList'][] = $res;
            }
        }

        $data['employ_status'] = FConfig::item("config.employ_status");
        $this->render('employOutWork',$data);


    }
    public function actionToBaseNumUpdate () {
        $data = array();
        $this->render('baseNumUpdate',$data);
    }
    public function actionBaseNumUpdate () {
        $data = $this->request->getParam('data');
        $employ = new Employ();
        $e_type = FConfig::item('config.employ_type_val');
        foreach ($data as $k=>$val) {
            if (empty($val['e_num']) || $val['e_num']=="null") {
                if($val['e_num']=="null") {
                    unset($data[$k]);
                }
                continue;
            }
            $attribute = array();
            if (!empty($val['shebaojishu']) && $val['shebaojishu'] != "null" || $val['shebaojishu'] == 0) {
            $attribute["shebaojishu"] = $val['shebaojishu'];
            }
            if (!empty($val['gongjijinjishu']) && $val['gongjijinjishu'] != "null" || $val['shebaojishu'] == 0) {
                $attribute["gongjijinjishu"] = $val['gongjijinjishu'];
            }

            if (!empty($val['e_type']) && $val['e_num']!= "null" ) {
                $attribute["e_type"] =$e_type[$val['e_type']];
                $attribute["e_type_name"] =$val['e_type'];
            }

            /*$attribute = array(
                "shebaojishu"=>$val['shebaojishu'],
                "gongjijinjishu"=>$val['gongjijinjishu'],
            );*/
            if (!empty($attribute)) {

                $result = $employ->updateAll($attribute," e_num = :e_num",array(":e_num"=>$val['e_num']));
            }
            /*$e_num = trim($val['e_num']);
            $arrTotal = array(
                "condition" => "e_num = '{$e_num}'"
            );
            $salSumPo = $employ->find($arrTotal);
            //print_r($employ);
            //print_r($arrTotal);
            $salSumPo->e_type = $e_type[$val['e_type']];
            $salSumPo->e_type_name = $val["e_type"];
            $salSumPo->save();*/
            //print_r($salSumPo);
           // print_r($attribute);exit;
            //$employ->update($attribute);
            //print_r($employ);
            //exit;
            if (!$result) {
                //print_r($attribute);
                $data[$k]['memo'] = "修改失败或是已经修改过，请检查身份证号是否正确";
            } else {
                $data[$k]['memo'] = "修改成功";
            }
        }
        $response['content'] = $data;
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    public function actionToEmploySalarySearch () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $customer_name = $this->request->getParam('customer_name');
        $e_num = $this->request->getParam('e_num');
        $condition_arr = array(
            //'condition'=>"op_id=:op_id",
            /*'params' => array(
                ':op_id'=>$this->user->id,
            ),*/
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if ($e_num) {
            $condition_arr['condition'] = "e_num = '{$e_num}' ";
        }
        if ($customer_name) {

            $comRes = Customer::model()->findByAttributes(array("customer_name"=> $customer_name));
            if (!empty($comRes)) {
                $condition_arr['condition'] = "company_id = {$comRes->id}";
            }
        }
        //分页
        $data['count'] = OldUser::model()-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();
        $condition_arr['order'] = "id desc";
        $result = OldUser::model()->findAll($condition_arr);
        $dataList = array();
        foreach ($result as $row) {

            $user = $row->attributes;
            $employModel = Employ::model()->findByAttributes(array("e_num"=>$row->e_num));
            $user['e_company'] = $employModel->e_company;
            $user['e_company_id'] = $employModel->e_company_id;
            $user['e_name'] = $employModel->e_name;
            $user['e_id'] = $employModel->id;
            $dataList[] = $user;
        }
        $data['data_list'] = $dataList;
        $data['search_name']['customer_name'] = $customer_name;
        $data['search_name']['e_num'] = $e_num;
        $data['page'] = $pages;
        $this->render('salarySearchUser',$data);
    }

    public function actionAddUserSearchSalaryAjax()
    {
        $companyName = $this->request->getParam('customer_name');;


        $result = Employ::model()->findAllByAttributes(array("e_company"=>$companyName));
        //print_r($result);
        foreach($result as $row) {
            $row['e_num'] = trim($row['e_num']);

            $userName = $row['e_num'];


            $employPO=OldUser::model()->findByAttributes(array("e_num"=>$row['e_num']));

            if (!$employPO) {
                $pass = 'Hello@1234';
                $iSql = "insert into OA_user  (user_id,name,password,user_type,e_num,create_time) values ({$row['id']},'{$userName}','{$pass}',2,'{$userName}',now())";
                $command = Yii::app()->db_zhongqiOA->createCommand($iSql);
                $result = $command->execute();
                /*echo $iSql."\r\n";
                echo $row['e_name']."#####".$userName.'#####'.$pass."\r\n";
                exit;*/
            }


        }
        $response['content'] = "修改成功";
        $response['status'] = "100000";
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
}