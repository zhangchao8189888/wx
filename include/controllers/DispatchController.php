<?php
/**
 * 派遣管理
 *
 */
class DispatchController extends FController
{
    private $employ_model;
    private $employInfo_model;
    private $customer_model;
    private $m_employ_model;
    private $m_employ_construct_model;
    private $m_social_model;
    private $m_gjjin_model;
    private $admin_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->employ_model = new Employ();
        $this->employInfo_model = new EmployInfo();
        $this->customer_model = new Customer();
        $this->m_employ_model = new MEmploy();
        $this->m_employ_construct_model = new MEmployConstruct();
        $this->m_social_model = new MSocial();
        $this->m_gjjin_model = new MGjjin();
        $this->admin_model = new Admin();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }

    public function actionCustomerList () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');
        $customer_name = $this->request->getParam('customer_name');
        $condition_arr = array(
            //'condition'=>"op_id=:op_id",
            /*'params' => array(
                ':op_id'=>$this->user->id,
            ),*/
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        if ($customer_name) {
            $condition_arr['condition'] = "customer_name like '%{$customer_name}%'";
        }
        //分页
        $data['count'] = $this->customer_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['customList'] = $this->customer_model->findAll($condition_arr);
        $data['adminList']  = $this->admin_model->findAll();
        $data['page'] = $pages;
        $this->render('customList',$data);
    }
    function actionAddOrUpdateCustom () {
        $customer_name = $this->request->getParam('customer_name');
        $customer_principal = $this->request->getParam('customer_principal');
        $customer_principal_phone = $this->request->getParam('customer_principal_phone');
        $customer_address = $this->request->getParam('customer_address');
        $canbaojin = floatval($this->request->getParam('canbaojin'));
        $service_fee = floatval($this->request->getParam('service_fee'));
        $remark = $this->request->getParam('remark');
        $id = $this->request->getParam('id');
        $op_id = $this->request->getParam('op_id');
        if ($op_id == 0) {
            $op_id = $this->user->id;
        }
        $date_rang_json = json_encode($this->request->getParam('date_rang_json'));
        $condition_arr = array(
            'customer_name' => $customer_name,
            'customer_principal' => $customer_principal,
            'customer_principal_phone' => $customer_principal_phone,
            'customer_address' => $customer_address,
            'canbaojin' => $canbaojin,
            'service_fee' => $service_fee,
            'date_rang_json' => $date_rang_json,
            'op_id' => $op_id,
            'remark' => $remark,
        );
        if (!empty($id)) {

            $res = $this->customer_model->updateByPk($id,$condition_arr);
        } else {

            $this->customer_model->attributes = $condition_arr;
            $res = $this->customer_model->save();
        }
        if($res){
            $response['status'] = 100000;
            $response['content'] = '保存成功！';
        }else{

            $response['status'] = 100001;
            $response['content'] = '确认失败！';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    function actionGetCustom () {
        $id = $this->request->getParam('id');
        $res = $this->customer_model->findByPk($id);
        if ($res) {
            $data['customer_name'] = $res->customer_name;
            $data['customer_principal'] = $res->customer_principal;
            $data['customer_principal_phone'] = $res->customer_principal_phone;
            $data['customer_address'] = $res->customer_address;
            $data['canbaojin'] = $res->canbaojin;
            $data['service_fee'] = $res->service_fee;
            $data['date_rang_json'] = json_decode($res->date_rang_json);
            $data['remark'] = $res->remark;
            $data['op_id'] = $res->op_id;
            $response['status'] = 100000;
            $response['content'] = $data;
        }else {
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    function actionEmployManage () {
        $this->layout = 'main_no_menu';
        $condition_arr = array(
            'condition'=>"op_id=:op_id",
            'params' => array(
                ':op_id'=>$this->user->id,
            ),
        );
        $res = $this->customer_model->findAll($condition_arr);
        $i = 0;
        foreach ($res as $row) {
            $data['custom_list'][$i]['id'] = $row->id;
            $data['custom_list'][$i]['name'] = $row->customer_name;
            $i++;
        }

        $searchCompanyData = $this->searchCompany();
        $data['jsonList'] = $searchCompanyData['list'] ? $searchCompanyData['list'] : array();
        $data['employInfo_header'] = (array)FConfig::item('header_name.employInfo_header');
        $data['employInfo_header_property'] = (array)FConfig::item('header_name.employInfo_header_property');
        $this->render('employList',$data);
    }
    function actionGetDepartmentTreeJson() {
        $id = $this->request->getParam('id');
        if (empty($id)) {
            $treeJson['data']['company_id'] = -1;
            $treeJson['data']['name'] = '派遣员工花名册';
            $treeJson['data']['pid'] = 0;
            $treeJson['data']['isParent'] = 'true';
            $treeJson['data']['id'] = -1;
        } elseif ($id == -1) {
            $condition_arr = array(
                'condition'=>"op_id=:op_id",
                'params' => array(
                    ':op_id'=>$this->user->id,
                ),
            );
            $res = $this->customer_model->findAll($condition_arr);
            $i = 0;
            foreach ($res as $row) {
                $treeJson['data'][$i]['id'] = $row->id;
                $treeJson['data'][$i]['name'] = $row->customer_name;
                $treeJson['data'][$i]['pid'] = -1;
                $treeJson['data'][$i]['isParent'] = 'false';
                $i++;
            }
        }

       /* $response['status'] = 100000;
        $response['content'] = $treeJson;*/
        echo json_encode($treeJson);
        exit;
    }
    function actionSaveOrUpdateEmployList () {
        $updateData = $this->request->getParam('data');
        $current_company_id = $this->request->getParam('current_company_id');
        if (!empty($current_company_id)) {
            $c = new EMongoCriteria;
            $c->e_company_id = $current_company_id;
            $employ_construct_po = $this->m_employ_construct_model->find($c);
        }
        $error_list = array();
        $success_list = array();
        foreach ($updateData as $row) {

            if ($row['row_id'] != 'null' && !empty($row['row_id'])) {
                $this->m_employ_model = new MEmploy('update');
                $this->m_employ_model->_id = new MongoId($row['row_id']);
                $this->m_employ_model->e_num = $row[$employ_construct_po->e_num_position];
                $this->m_employ_model->e_hetong_num = intval($row[$employ_construct_po->e_hetong_num_position]);
                $this->m_employ_model->e_name = $row[$employ_construct_po->e_name_position];
                $this->m_employ_model->e_type = $row[$employ_construct_po->e_type_position];
                $this->m_employ_model->e_company_id = $current_company_id;
                $this->m_employ_model->emp_info_row = $row;
                $this->m_employ_model->setIsNewRecord(false);
                if($this->m_employ_model->validate()){
                    $this->m_employ_model->update();
                }
            }else {

                unset($row['row_id']);
                $this->m_employ_model = new MEmploy('insert');
                $this->m_employ_model->e_num = $row[$employ_construct_po->e_num_position];
                $this->m_employ_model->e_hetong_num = intval($row[$employ_construct_po->e_hetong_num_position]);
                $this->m_employ_model->e_name = $row[$employ_construct_po->e_name_position];
                $this->m_employ_model->e_type = $row[$employ_construct_po->e_type_position];
                $this->m_employ_model->e_company_id = $current_company_id;
                $this->m_employ_model->emp_info_row = $row;
                $this->m_employ_model->save();
            }

            $error = $this->m_employ_model->getErrors();
            if (!empty($error)) {
                $error_list[] = $error;
            }
        }

        $response['content']['success_list'] = $success_list;
        if (!empty($error_list)) {
            $response['status'] = 100001;
            $response['content']['error_list'] = $error_list;
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $response['status'] = 100000;
        $response['content']['message'] = '添加成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    function actionGetEmployListBySearch() {
        $e_company = $this->request->getParam('e_company');
        $e_name = $this->request->getParam('e_name');
        $e_num = $this->request->getParam('e_num');
        $department = $this->request->getParam('department');
        $op_user = $this->request->getParam('op_user');
        $contract_no= $this->request->getParam('contract_no');

        $condition = array();
        $where = "1=1";
        if (!empty($department)) {
            $where .= " and department_name like '%{$department}%'";
        }
        if (!empty($e_company)) {
            $where .= " and company_name like '%{$e_company}%'";
        }
        if (!empty($op_user)) {
            $where.=" and op_user_name like '%{$op_user}%'";
        }
        if (!empty($e_name)) {
            $where.=" and employ_name = '{$e_name}'";
        }
        if (!empty($e_num)) {
            $where.=" and e_num = '{$e_num}'";
        }
        if (!empty($contract_no)) {
            $where.=" and contract_no = '{$contract_no}'";
        }
        $condition['condition'] = $where;
        $res = EmployInfo::model()->findAll($condition);
        $data_list = array();
        $sex_map = array("1"=>"男",2=>"女");
        foreach ($res as $val) {
            $val->sex = $sex_map[$val->sex];
            $data_list[] = $val->getAttributes();
        }
        $employInfo_header = (array)FConfig::item('header_name.employInfo_header');
        $employInfo_header_property = (array)FConfig::item('header_name.employInfo_header_property');
        $data['head'] = $employInfo_header;
        $eColumns = array();
        foreach ($employInfo_header_property as $key => $val) {
            if ($key == '姓名') {

                $eColumns[] = array('data'=> $val,'comment'=>'sdfsdfsdf');
            } else {

                $eColumns[] = array('data'=> $val);
            }
        }
        $data['data_list'] = $data_list;
        $data['columns'] = $eColumns;
        echo json_encode($data);
        exit;
    }/*
    function actionGetEmployList () {
        $id = $this->request->getParam('id');
        $c = new EMongoCriteria;
        $c->e_company_id = $id;
        $construct = $this->m_employ_construct_model->find($c);//print_r($construct);exit;
        if (empty($construct)) {
            $response['status'] = 100001;
            $response['content'] = '内容为空！';
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $data['head'] = $construct->head_row;

        $eColumns = array();
        foreach ($construct->head_row as $key => $val) {
            $eColumns[] = array('data'=> $key);
        }

        $c->sort('e_hetong_num',EMongoCriteria::SORT_ASC);
        $res = $this->m_employ_model->findAll($c);
        $eList = array();
        foreach ($res as $row) {
            $row->emp_info_row['row_id'] = $row->_id->{'$id'};
            $row->emp_info_row['e_num'] = $row->e_num;
            $row->emp_info_row['e_name'] = $row->e_name;
            $row->emp_info_row['e_type'] = $row->e_type;
            $row->emp_info_row['row_id'] = $row->_id->{'$id'};
            $eList[] = $row->emp_info_row;

        }

        $data['data_list'] = $eList;
        $data['columns'] = $eColumns;
        echo json_encode($data);
        exit;
    }*/
    function actionGetEmployList () {
        $id = $this->request->getParam('id');
        $condition = array();
        $where = "1=1";
        if (!empty($id)) {
            $where.=" and company_id = '{$id}'";
        }
        $condition['condition'] = $where;
        $res = EmployInfo::model()->findAll($condition);
        $data_list = array();
        $sex_map = array("1"=>"男",2=>"女");
        foreach ($res as $val) {
            $val->sex = $sex_map[$val->sex];
            $data_list[] = $val->getAttributes();
        }
        $employInfo_header = (array)FConfig::item('header_name.employInfo_header');
        $employInfo_header_property = (array)FConfig::item('header_name.employInfo_header_property');
        $data['head'] = $employInfo_header;
        $eColumns = array();
        foreach ($employInfo_header_property as $key => $val) {
            if ($key == 'is_new_social') {
                $eColumns[] = array('data'=> $val);
            }
            $eColumns[] = array('data'=> $val);
        }
        $data['data_list'] = $data_list;
        $data['columns'] = $eColumns;
        echo json_encode($data);
        exit;
    }
    public function actionGetEmployById () {

        $id = $this->request->getParam('id');
        $c = new EMongoCriteria;
        $c->_id = new MongoId($id);
        $employ_po = $this->m_employ_model->find($c);
    }
    private function sumAge($birthday) {
        $age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
        return $age;
    }
    public function actionDelEmployList () {
        $ids = $this->request->getParam('ids');
        $bError = false;
        foreach ($ids as $_id)  {
            $res = $this->m_employ_model->deleteByPk(new MongoId($_id));
            /*if ($bError) {

            }*/
        }
        $response['status'] = 100000;
        $response['content'] = '删除成功！';
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
    public function actionEmployImport () {
        $this->render('employImport');
    }
    public function actionFileImport() {
        $condition_arr = array(
            'condition'=>"op_id=:op_id",
            'params' => array(
                ':op_id'=>$this->user->id,
            ),
        );
        $res = $this->customer_model->findAll($condition_arr);
        $i = 0;
        foreach ($res as $row) {
            $data['custom_list'][$i]['id'] = $row->id;
            $data['custom_list'][$i]['name'] = $row->customer_name;
            $i++;
        }
        $this->render('employImportToView',$data);
    }
    public function actionSaveEmployList_bak() {
        $em_list = $this->request->getParam('data');
        $e_num = $this->request->getParam('e_num')-1;
        $e_hetong_num = $this->request->getParam('e_hetong_num')-1;
        $e_type = $this->request->getParam('e_type')-1;
        $e_name = $this->request->getParam('e_name')-1;
        $custom_id = $this->request->getParam('custom_id');
        //$save_list = array();
        if ($e_num < 0) {
            $response['status'] = 100002;
            $response['content'] = '身份证列无法找到！';
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        if ($e_type < 0) {
            $response['status'] = 100002;
            $response['content'] = '身份类别列无法找到！';
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        if ($e_name < 0) {
            $response['status'] = 100002;
            $response['content'] = '姓名列无法找到！';
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        if ($e_hetong_num < 0) {
            $response['status'] = 100002;
            $response['content'] = '合同号列无法找到！';
            Yii::app()->end(FHelper::json($response['content'],$response['status']));
        }
        $error_list = array();
        $success_list = array();
        //$this->m_employ_construct_model = new MEmployConstruct();
        foreach ($em_list as $key => $row) {
            if ($key == 0) {
                $error_list[] = $row;
                foreach($row as $k => $v)
                {
                    if ($v == 'null') {
                        $row[$k] = '';
                    }
                }
                $hash_str = hash('md5',implode(",", $row));
                $res = $this->m_employ_construct_model->findByAttributes(array("e_company_id" => $custom_id));

                if (!empty($res)) {
                    if ($hash_str != $res->head_hash) {
                        $response['status'] = 100002;
                        $response['content'] = '该单位导入表头与已存在表头不一致！';
                        Yii::app()->end(FHelper::json($response['content'],$response['status']));
                    }
                    $this->m_employ_construct_model->_id = new MongoId($res->_id->{'$id'});
                    $this->m_employ_construct_model->e_company_id = $custom_id;
                    $this->m_employ_construct_model->e_num_position = $e_num;
                    $this->m_employ_construct_model->e_hetong_num_position = $e_hetong_num;
                    $this->m_employ_construct_model->e_name_position = $e_name;
                    $this->m_employ_construct_model->e_type_position = $e_type;
                    $this->m_employ_construct_model->head_row = $row;
                    $this->m_employ_construct_model->head_hash = $hash_str;
                    $this->m_employ_construct_model->setIsNewRecord(false);
                    $this->m_employ_construct_model->update();
                } else {

                    $this->m_employ_construct_model->e_company_id = $custom_id;
                    $this->m_employ_construct_model->e_num_position = $e_num;
                    $this->m_employ_construct_model->e_hetong_num_position = $e_hetong_num;
                    $this->m_employ_construct_model->e_name_position = $e_name;
                    $this->m_employ_construct_model->e_type_position = $e_type;
                    $this->m_employ_construct_model->head_row = $row;
                    $this->m_employ_construct_model->head_hash = $hash_str;
                    $this->m_employ_construct_model->save();
                    $error = $this->m_employ_construct_model->getErrors();
                    if (!empty($error)){
                        $response['status'] = 100002;
                        $response['content'] = $error->message;
                        Yii::app()->end(FHelper::json($response['content'],$response['status']));
                    }
                }

            } else {
                $this->m_employ_model = new MEmploy();
                $this->m_employ_model->e_num = $row[$e_num];
                $this->m_employ_model->e_hetong_num = intval($row[$e_hetong_num]);
                $this->m_employ_model->e_name = $row[$e_name];
                $this->m_employ_model->e_type = $row[$e_type];
                $this->m_employ_model->e_company_id = $custom_id;
                $this->m_employ_model->emp_info_row = $row;
                $this->m_employ_model->save();
                $error = $this->m_employ_model->getErrors();
                if (!empty($error)){
                    $row['message'] = $error['message'][0];
                    $error_list[] = $row;
                } else {
                    $success_list[] = $key;
                }
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
    public function actionSaveEmployList() {
        $em_list = $this->request->getParam('data');
        $em_list = json_decode($em_list);
        $table_info = $this->request->getParam('table_info');
        $header = $em_list[0];
        $data = array_slice($em_list,1);
        $sex_map = array("男"=>1,"女"=>2);
        $is_map = array("是"=>1,"否"=>0);
        $errorInfo = array();
        $successInfo = array();
        $gongjijinjishu = -1;
        $shebaojishu = -1;
        $e_type = -1;
        $e_num = -1;
        $department_name = -1;
        foreach($table_info as $key => $val) {
            if ($val == "gongjijinjishu") {
                $gongjijinjishu = $key;
            } elseif ($val == "shebaojishu") {
                $shebaojishu = $key;
            } elseif ($val == "e_type_name") {
                $e_type = $key;
            } elseif ($val == "e_num") {
                $e_num = $key;
            } elseif ($val == "department_name") {
                $department_name = $key;
            }
        }
        if ($e_num == -1) {
            return false;
        }
        $row = 1;
        $contractInfo = $table_info['contractInfo'];
        $totalSum = count($data);
        $successSum = 0;
        $updateSum = 0;
        foreach ($data as $key => $val) {
            $row ++;
            $val[$e_num] =trim($val[$e_num]);
            $val[$department_name] =trim($val[$department_name]);
            if ($val[$e_num] == 'null' || empty($val[$e_num])) {
                $errorInfo[] =  "第{$row}行 身份证为空\n";
                continue;
            }
            $employInfo_model = new EmployInfo();
            $attr = array();

            $employ = Employ::model()->find(array('condition' =>"e_num = :e_num","params" =>array(":e_num"=>$val[$e_num])));
            $employId= 0;
            if ($employ) {
                //Todo 添加新员工
                $employId = $employ->id;
            }

            $employInfoPO = EmployInfo::model()->find(array('condition' =>"e_num = :e_num","params" =>array(":e_num"=>$val[$e_num])));
            $customer = Customer::model()->find(array('condition' =>"customer_name = :customer_name",
                "params" =>array(":customer_name"=>$val[$department_name])));
            $companyId = 0;

            if ($employInfoPO) {
                $updatePO = array();
                foreach ($val as $k => $v) {
                    if ($v == 'null') {
                        $v = '';
                    }
                    $v = trim($v);
                    if (array_key_exists($table_info[$k],$employInfo_model->attributeLabels()) && !empty($v)) {

                        if ($table_info[$k] == "sex") {
                            $v = $sex_map[$v];
                        } elseif ($table_info[$k] == "is_new_social") {
                            $v = $is_map[$v];
                        }  elseif ($table_info[$k] == "social_add_time" || $table_info[$k] == "social_sub_time" ) {
                            $v = $v."-01";
                        }  elseif ($table_info[$k] == "current_contract_times") {

                        }
                        $updatePO["{$table_info[$k]}"] = $v;

                    }
                }
                $updatePO["op_user_id"] = $this->user->id;

                if (empty($updatePO["op_user_name"])) {

                    $updatePO["op_user_name"] = $this->user->name;
                }
                $updatePO["company_id"] = $companyId;
                $updatePO["employ_id"] = $employId;
                $updatePO["u_time"] = date("Y-m-d h:i:s",time());
                $updatePO["c_time"] = date("Y-m-d h:i:s",time());
                $result = $employInfo_model->updateByPk($employInfoPO->id,$updatePO);
                $error = $employInfo_model->getErrors();

                if (!empty($error)) {
                    foreach ($error as $key => $val){

                        $errorInfo[] = "第{$row}行  $val[$e_num] 修改失败请重试，错误信息：【{$val[0]}】{$employInfo_model->{$key}}\n";
                    }
                } else $updateSum++;
                continue;
            }
            if (!empty($val[$department_name]) && !$customer) {
                //$errorInfo[] = "第{$row}行身份证号{$val[$e_num]}单位名称({$val[$department_name]})无法识别！";
            } else {
                $companyId = $customer->id;
            }
            if (!empty($contractInfo)) {
                foreach ($contractInfo as $con_info) {

                    //echo $val[$con_info['key']]."\n";
                    if (empty($val[$con_info['key']])) {
                        continue;
                    }
                    $val[$con_info['key']] = trim($val[$con_info['key']]);
                    $contractTime = new EmployContractTime();
                    if (preg_match("/(\d{4}-\d{2}-\d{2}|\d{4}\.\d{2}\.\d{2}|\d{4}\/\d{2}\/\d{2})\-(\d{4}-\d{2}-\d{2}|\d{4}\.\d{2}\.\d{2}|\d{4}\/\d{2}\/\d{2})/",$val[$con_info['key']],$res)) {
                        /*echo($val[$con_info['key']]);
                        print_r($res);exit;*/
                        $contractTime->contract_start_time = $res[1];
                        $contractTime->contract_end_time = $res[2];
                    } else {
                        $errorInfo[] = "第{$row}行！{$val[$e_num]}合同日期格式：{$val[$con_info['key']]} 无法识别请重试";
                        $continue = true;
                        continue;
                    }
                    $contractTime->company_id = $companyId;
                    $contractTime->employ_id = $employId;
                    $contractTime->op_id = $this->user->id;

                    $contractTime->contract_name = $val[$con_info['key']];
                    $contractTime->contract_index = $con_info['index'];
                    $contractTime->e_num = $val[$e_num];
                    $contractTime->c_time = date("Y-m-d h:i:s",time());
                    $contractTime->u_time = date("Y-m-d h:i:s",time());
                    $contractTime->save();
                    $error = $contractTime->getErrors();

                    if (!empty($error)) {
                        foreach ($error as $ks => $s){
                            /*print_r($val);
                            print_r($con_info);*/
                            $errorInfo[] = "第{$row}行 $val[$e_num] 添加合同时间失败请重试，错误信息：【{$s[0]}】{$contractTime->{$ks}}\n";
                            continue;
                        }
                    }
                }
                if ($continue == true) {
                    $continue = false;
                    continue;
                }
            }
            //$employ = Employ::model()->find(array('condition' =>"e_num = :e_num","params" =>array(":e_num"=>$val[$e_num])));
            foreach ($val as $k => $v) {
                if ($v == 'null') {
                    $v = '';
                }
                $v = trim($v);
                if (array_key_exists($table_info[$k],$employInfo_model->attributeLabels()) && !empty($v)) {

                    if ($table_info[$k] == "sex") {
                        $v = $sex_map[$v];
                    } elseif ($table_info[$k] == "is_new_social") {
                        $v = $is_map[$v];
                    }  elseif ($table_info[$k] == "social_add_time" || $table_info[$k] == "social_sub_time" ) {
                        $v = $v."-01";
                    }  elseif ($table_info[$k] == "current_contract_times") {

                    }
                    $employInfo_model->{$table_info[$k]} = $v;

                }
            }
            if (empty($employInfo_model->op_user_name)) {
                $employInfo_model->op_user_name = $this->user->name;
            }

            $employInfo_model->op_user_id = $this->user->id;
            $employInfo_model->company_id = $companyId;
            $employInfo_model->employ_id = $employId;
            $employInfo_model->u_time = date("Y-m-d h:i:s",time());
            $employInfo_model->c_time = date("Y-m-d h:i:s",time());
            $result = $employInfo_model->save();
            $error = $employInfo_model->getErrors();

            if (!empty($error)) {
                foreach ($error as $key => $val){

                    $errorInfo[] = "第{$row}行  $val[$e_num] 添加失败请重试，错误信息：【{$val[0]}】{$employInfo_model->{$key}}\n";
                }
            } else $successSum++;
            //print_r($employInfo_model);exit;

        }
        $successInfo['totalSum'] = $totalSum;
        $successInfo['saveSum'] = $successSum;
        $successInfo['updateSum'] = $updateSum;
        $data = array();
        $data['errorInfo'] = $errorInfo;
        $data['successInfo'] = $successInfo;
        echo json_encode($data);
        exit;
    }
    public function actionGetEmploySocialById() {

        $row_id = $this->request->getParam('row_id');
        $c = new EMongoCriteria;
        $c->_id = new MongoId($row_id);
        $employ_po = $this->m_employ_model->find($c);
        if (!empty($employ_po)) {

            $c = new EMongoCriteria;
            $c->e_num = $employ_po->e_num;
            $social_po = $this->m_social_model->find($c);
            if (!empty($social_po)) {
                $response['content']['social'] = $social_po;
            } else {
                $response['content']['social'] = 'empty';
            }
            $gjjin_po = $this->m_gjjin_model->find($c);
            if (!empty($gjjin_po)) {
                $response['content']['gjjin'] = $gjjin_po;
            } else {
                $response['content']['gjjin'] = 'empty';
            }
        }

        $response['status'] = 100000;
        Yii::app()->end(FHelper::json($response['content'],$response['status']));

    }
    public function actionFileImportList () {
        $filePath = FConfig::item('config.uploadPath')."user_".$this->user->id;

        require "include/extensions/filetools/fileTools.php";

        $op = new fileoperate ();
        $data['files'] = $op->list_filename ( $filePath, 1 );
        $this->render('file_upload',$data);
    }
    public function actionSysFileUpload () {
        $filePath = FConfig::item('config.uploadPath')."user_".$this->user->id."/";
        $arr = FHelper::fileUpload($filePath);
        if (!$arr['val']) {
            $data['error'] = $arr['errorMsg'];
        } else {
            $data['success'] = $arr['errorMsg'];
        }
        require "include/extensions/filetools/fileTools.php";
        $op = new fileoperate ();
        $data['files'] = $op->list_filename ( $filePath, 1 );
        $this->render('file_upload',$data);
    }
    public function actionFileDel () {
        $fName = $_GET ['fName'];
        require "include/extensions/filetools/fileTools.php";
        $op = new fileoperate ();
        $filePath = FConfig::item('config.uploadPath')."user_".$this->user->id."/";

        $mess = $op->del_file ( $filePath, $fName );
        if ($mess = '文件删除失败') {
            $data['success'] =  $mess;
        } else {
            $data['error'] =  $mess;
        }
        $data['files'] = $op->list_filename ( $filePath, 1 );
        $this->render('file_upload',$data);
    }
    public function actionFileDown () {
        $fName = $_GET ['fName'];

        $filePath = FConfig::item('config.uploadPath')."user_".$this->user->id."/";
        $file = $filePath.$fName;
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }
    }
    public function getNewContractTime ($arr) {
        $patten = "/^d{4}[\.](0?[1-9]|1[012])[\.](0?[1-9]|[12][0-9]|3[01])";
        $max_date = 0;
        foreach ($arr as $key => $date) {
            if (preg_match ( $patten, $date )) {
                $date = str_replace(".","-",$date);

                $dateStamp = strtotime($date);
                if ($dateStamp > $max_date) {
                    $max_date = $dateStamp;
                }
            }
        }
    }
    public function saveEmployMysqlData ($employ) {
        $employObj = $this->employ_model->findByAttributes(array("e_num"=>$employ['e_num']));
        if ($employObj instanceof Employ) {
            $employObj->e_hetong_date = $employ['contract_date'];
        }
    }
    public function actionContractAlert () {

    }

    public function actionReadExcel () {
        ini_set('memory_limit', '800M');
        ini_set("max_execution_time",30000);
        $filePath = FConfig::item('config.uploadPath')."employ_import/";
        $arr = FHelper::fileUpload($filePath);

        if (!$arr['val']) {
            $data['msg'] = $arr['errorMsg'];
            $data['code'] = 100001;
            echo json_encode($data);
            exit;
        } else {
            $data['msg'] = $arr['errorMsg'];
            $file_path = $arr['file_path'];
        }
        if (!empty($file_path)) {
            $excel_data = $this->excelReaderFun($file_path);

            $return_data = $this->readExcelData($excel_data);

            //$input = array_slice($excel_data,1);
            //$diffArr = $this->checkDiffData($input,$return_data['table_info']);

            $return_data['diff_data'] = array();
            $data['code'] = 100000;
            $data['check_data'] = $return_data;
        }
        echo json_encode($data);
        exit;
    }
    public function readExcelData ($data) {
        $head = $data[0];
        $employInfo_header = (array)FConfig::item('header_name.employInfo_header');
        $employInfo_header_property = (array)FConfig::item('header_name.employInfo_header_property');
        $error_info = array();
        $table_info = array();
        $table_info['contractInfo'] = array();
        $index_map = array("一"=>1,"二"=>2,"三"=>3,"四"=>4,"五"=>5,"六"=>6,"七"=>7,"八"=>8,"九"=>9,"十"=>10,);
        foreach ($head as $key => $val ) {
            $error = array();
            if (empty($val)) {
                $list = FExcelFun::getHeaderListByIndex($data,$key);
                $bool = FExcelFun::checkIsEmptyHeaderList($list);
                if ($bool) {
                    $data = FExcelFun::delArrayList($data,$key);
                } else {
                    $error['head_key'] = $key;
                    $error['error_info'][] = "第{$key}列表头为空";
                }
            }elseif (!in_array($val,$employInfo_header)) {
                if (preg_match("/^第(?P<name>.*?)次合同期限$/",$val,$res)) {
                   $contractInfo['key'] = $key;
                   $contractInfo['index'] = $index_map[$res['name']];
                   $table_info['contractInfo'][] = $contractInfo;
                } else {

                    $error['head_key'] = $key;
                    $error['error_info'] = "第{$key}列[{$val}]文字无法识别，不合法规范";
                }
            } elseif (in_array($val,$employInfo_header)) {
                $table_info[$key] = $employInfo_header_property[$val];
            }
            if (!empty($error)) {

                $error_info[] = $error;
            }
        }
        /*print_r($table_info);
        print_r($error_info);
        print_r($data);*/
        $return_data = array();
        $return_data["table_info"] = $table_info;
        $return_data["error_info"] = $error_info;
        $return_data["data"] = $data;
        return $return_data;
    }
    public function checkDiffData ($data,$tableInfo) {
        $diffArr = array();
        $gongjijinjishu = -1;
        $shebaojishu = -1;
        $e_type = -1;
        $e_num = -1;
        $department_name = -1;
        foreach($tableInfo as $key => $val) {
            if ($val == "gongjijinjishu") {
                $gongjijinjishu = $key;
            } elseif ($val == "shebaojishu") {
                $shebaojishu = $key;
            } elseif ($val == "e_type_name") {
                $e_type = $key;
            } elseif ($val == "e_num") {
                $e_num = $key;
            } elseif ($val == "department_name") {
                $department_name = $key;
            }
        }
        if ($e_num == -1) {
            return false;
        }
        //print_r($tableInfo);exit;
        foreach ($data as $key=>$val) {
            $employ = Employ::model()->find(array('condition' =>"e_num = :e_num","params" =>array(":e_num"=>$val[$e_num])));
            $customer = Customer::model()->find(array('condition' =>"customer_name = :customer_name",
                "params" =>array(":customer_name"=>$val[$department_name])));
            //print_r($employ);
            if ($employ) {
                if (!empty($val[$department_name]) && $customer->customer_name != $val[$department_name]) {
                    $diffArr[] = "第{$key}行身份证号{$val[$e_num]}单位名称({$val[$department_name]})和系统({$customer->customer_name})不一致是否需要修改";
                }elseif (!empty($val[$e_type]) && $employ->e_type_name != $val[$e_type]) {
                    $diffArr[] = "第{$key}行身份证号{$val[$e_num]}身份类别({$val[$e_type]})和系统({$employ->e_type_name})不一致是否需要修改";
                } /*elseif (!empty($val[$gongjijinjishu]) && $employ->gongjijinjishu != $val[$gongjijinjishu]) {
                    $diffArr[] = "第{$key}行身份证号{$val[$e_num]}公积金基数({$val[$gongjijinjishu]})和系统({$employ->gongjijinjishu})不一致是否需要修改";
                } */
            }
        }
        return $diffArr;
    }
    public function getHeaderList () {

    }
}