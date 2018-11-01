<?php
/**
 * 产品类型
 *
 */
class ProductController extends FController
{
    private $productType_model;
    private $product_model;
    private $productPublish_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->productType_model = new ProductType();
        $this->product_model = new Product();
        $this->productPublish_model = new ProductPublish();

    }
//注释
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }

    /**
     * 产品类型列表
     */
    public function actionIndex () {
        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
            'order' =>  'type_sort asc'
        );
        //分页
        $data['count'] = $this->productType_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();


        $data['tdataList'] = $this->productType_model->findAll($condition_arr);
        $data['page'] = $pages;

        $this->render('index',$data);
    }


    /**
     * 产品类型添加
     */
    public function actionAdd () {
        $type_name = $this->request->getParam('type_name');
        $type_code = $this->request->getParam('type_code');
        $type_desc = $this->request->getParam('type_desc');
        $type_sort = $this->request->getParam('type_sort');
        $create_time = $update_time = FF_DATE_TIME;

        $condition_attr = array(
            'condition'=>"type_name=:type_name",
            'params' => array(':type_name'=>$type_name,),
        );
        $count = $this->productType_model->count($condition_attr);
        if($count > 0){
            $response['status'] = 100002;
            $response['content'] = '用户名已存在';
            Yii::app()->end(FHelper::json($response['content'], $response['status']));
        }
        $condition_attr = array(
            'type_name' =>  $type_name,
            'type_code' =>  $type_code,
            'description' =>  $type_desc,
            'type_sort' =>  $type_sort,
            'create_time' =>  $create_time,
            'update_time' =>  $update_time,
        );

        $this->productType_model->attributes = $condition_attr;
        $res = $this->productType_model->save();

        if ($res) {
            $response['status'] = 100000;
            $response['content'] = 'success';

        } else {
            $response['status'] = 100001;
            $response['content'] = 'error';
        }

        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 产品列表
     */
    public function actionProductList () {
        $search_earn_days = trim($this->request->getParam('search_earn_days') ? $this->request->getParam('search_earn_days') : '');
        $product_name = trim($this->request->getParam('search_product_name') ? $this->request->getParam('search_product_name') : '');
        $product_type = trim($this->request->getParam('search_product_type') ? $this->request->getParam('search_product_type') : '');
        $_product_type=$this->productType_model->findByAttributes(array('type_name' => $product_type));
        $product_type_id = $_product_type->id;

        //分页参数
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //查询
        $where ='1=1';
        if ($product_type) {
            $where.= " and product_type_id = '$product_type_id' ";
        }
        if ($product_name) {
            $where.= " and product_name like :product_name ";
        }
        if ($search_earn_days) {
            $where.= " and earn_days <= '$search_earn_days' ";
        }

        $condition_arr['condition'] = $where;
        $condition_arr['params'] = array(
            ':product_name' => "%".$product_name."%"
        );

        //分页
        $data['count'] = $this->product_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['proTypeList'] = $this->productType_model->findAll();
        $data['dataList'] = $this->product_model->findAll($condition_arr);

        $data['guarantee_levels'] = FConfig::item('config.guarantee_levels');
        $data['invest_start_types'] = FConfig::item('config.invest_start_types');
        $data['invest_date_types'] = FConfig::item('config.invest_date_types');
        $data['invest_issue_types'] = FConfig::item('config.invest_issue_types');
        $data['config_earn_days'] = FConfig::item('config.search_earn_days');

        $data['product_type'] = $product_type;
        $data['product_name'] = $product_name;
        $data['earn_days'] = $search_earn_days;

        $data['page'] = $pages;

        $this->render('productList',$data);
    }
    /**
     * 产品添加
     */
    public function actionProductAdd(){
        $earn_days_sign = '';
        $product_name           = $this->request->getParam('product_name');
        $product_type_id        = $this->request->getParam('product_type_id');
        $yield_rate_year        = $this->request->getParam('yield_rate_year');
        $fund_min_val           = $this->request->getParam('fund_min_val');
        $guarantee_level        = $this->request->getParam('guarantee_level');
        $upper_limit            = $this->request->getParam('upper_limit');
        $invest_start_type    = $this->request->getParam('invest_start_type');
        if($invest_start_type ==1){

            $invest_date_type     = $this->request->getParam('invest_date_type');
            $invest_days          = $this->request->getParam('invest_days');
            $earn_days          = $this->request->getParam('earn_days');
        }else{

            $invest_start_date    = $this->request->getParam('invest_start_date');
            $invest_end_date    = $this->request->getParam('invest_end_date');
            $startDate=strtotime($invest_start_date);
            $endDate=strtotime($invest_end_date);
            $days=round(($endDate-$startDate)/3600/24) ;
            $earn_days=$days;
        }
        if($earn_days<30){
            $earn_days_sign .= $earn_days.'天';
        }else{
            $earn_days_sign .=ceil($earn_days/30).'个月';
        }
        $invest_issue_type    = $this->request->getParam('invest_issue_type');
        $create_time            = $update_time = FF_DATE_TIME;

        //找类型编码
        $condition_type = array(
            'select'=>'MAX(id) as id',
        );
        $resMax = $this->product_model->find($condition_type);
        $result = $this->productType_model->findByPk($product_type_id);
        $maxId = $resMax->id;
        if(empty($resMax->id)){
            $maxId = 1;
        } else {
            $maxId += 1;
        }
        $product_code = $result->type_code.$maxId;

        $condition_attr = array(
            'condition'=>"product_name=:product_name",
            'params' => array(':product_name'=>$product_name,),
        );
        $count = $this->product_model->count($condition_attr);
        if($count > 0){
            $response['status'] = 100002;
            $response['content'] = '产品名已存在';
            Yii::app()->end(FHelper::json($response['content'], $response['status']));
        }
        $condition_attr = array(
            'product_code'          =>  $product_code,
            'product_name'          =>  $product_name,
            'product_type_id'       =>  $product_type_id,
            'yield_rate_year'       =>  $yield_rate_year,
            'fund_min_val'          =>  $fund_min_val,
            'guarantee_level'       =>  $guarantee_level,
            'upper_limit'           =>  $upper_limit,
            'invest_issue_type'   =>  $invest_issue_type,
            'invest_start_type'   =>  $invest_start_type,
            'invest_date_type'    =>  $invest_date_type,
            'invest_days'         =>  $invest_days,
            'earn_days'         =>  $earn_days,
            'invest_start_date'   =>  $invest_start_date,
            'invest_end_date'   =>  $invest_end_date,
            'earn_days_sign'   =>  $earn_days_sign,     //投资期限类型

            'create_time'           =>  $create_time,
            'update_time'           =>  $update_time,
        );

        $this->product_model->attributes = $condition_attr;
        $res = $this->product_model->save();
        if ($res) {
            $response['status'] = 100000;
            $response['content'] = 'success';

        } else {
            $response['status'] = 100001;
            $response['content'] = '添加失败！';
        }

        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 修改前  获取原有数据
     */
    public function actionGetUpdate(){
        $id = $this->request->getParam('id');
        $data['oldDataList'] = $this->product_model->findByPk($id);
        if(!empty($data['oldDataList'])){
            $response['status'] = 100000;
            $response['content'] = $data['oldDataList']->getAttributes();

        } else {

            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 产品修改
     */
    public function actionProductUpdate(){

        $id = $this->request->getParam('id');
        $product_name           = $this->request->getParam('product_name');
        $product_type_id        = $this->request->getParam('product_type_id');
        $yield_rate_year        = $this->request->getParam('yield_rate_year');
        $fund_min_val           = $this->request->getParam('fund_min_val');
        $guarantee_level        = $this->request->getParam('guarantee_level');
        $upper_limit            = $this->request->getParam('upper_limit');
        $invest_issue_type    = $this->request->getParam('invest_issue_type');
        $invest_start_type    = $this->request->getParam('invest_start_type');
        $invest_start_date    = $this->request->getParam('invest_start_date');
        $invest_end_date    = $this->request->getParam('invest_end_date');
        $earn_days          = $this->request->getParam('earn_days');
        if($invest_start_type==2){
            $startDate=strtotime($invest_start_date);
            $endDate=strtotime($invest_end_date);
            $days=ceil(($endDate-$startDate)/3600/24) ;
            $earn_days=$days;
        }
        $invest_date_type     = $this->request->getParam('invest_date_type');
        $invest_days          = $this->request->getParam('invest_days');
        $earn_days_sign          = $this->request->getParam('earn_days_sign');
        $create_time            = $update_time = FF_DATE_TIME;

        $condition_attr = array(
            'product_name'          =>  $product_name,
            'product_type_id'       =>  $product_type_id,
            'yield_rate_year'       =>  $yield_rate_year,
            'fund_min_val'          =>  $fund_min_val,
            'guarantee_level'       =>  $guarantee_level,
            'upper_limit'           =>  $upper_limit,
            'invest_issue_type'   =>  $invest_issue_type,
            'invest_start_type'   =>  $invest_start_type,
            'invest_date_type'    =>  $invest_date_type,
            'invest_days'         =>  $invest_days,
            'earn_days'         =>  $earn_days,
            'invest_start_date'   =>  $invest_start_date,
            'invest_end_date'   =>  $invest_end_date,
            'earn_days_sign'   =>  $earn_days_sign,
            'create_time'           =>  $create_time,
            'update_time'           =>  $update_time,
        );

        $res = $this->product_model->updateByPk($id,$condition_attr);
        if ($res) {
            $response['status'] = 100000;
            $response['content'] = 'success';
        } else {
//            print_r($this->product_model);
            $response['status'] = 100001;
            $response['content'] = 'error';
        }

        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 产品删除
     */
    public function actionProductDelete () {
        $id = $this->request->getParam('id');
        $condition_attr = array(
            'id' => $id,

        );

        $res = $this->product_model->deleteByPk($condition_attr);
        if($res){
            $response['status'] = 100000;
            $response['content'] = 'success';

        }else{
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }


    /**
     * 产品类型删除
     */
    public function actionDelete () {
        $tid = $this->request->getParam('tid');
        $condition_attr = array(
            'id' => $tid,

        );

        $res = $this->productType_model->deleteByPk($condition_attr);
        if($res){
            $response['status']  = 100000;
            $response['content'] = 'success';

        }else{
            $response['status']  = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

    /**
     * 产品类型排序
     */
    public function actionProTypeSort(){
        $id         = $this->request->getParam('id');               //原记录id
        $new_sort       = $this->request->getParam('new_sort');             //添加的sort值

        //修改原纪录的type_sort
        $condition_attr = array(
            'type_sort'     =>  $new_sort,
        );
        $result = $this->productType_model->updateByPk($id,$condition_attr);
        if($result){
            $response['status']  = 100000;
            $response['content'] = success;
        }else{

            $response['status']  = 100002;
            $response['content'] = error;
        }
        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }
    public function actionPublish (){
        $page = ($this->request->getParam('page') > 0) ? (int) $this->request->getParam('page') : 1;
        $page_size = ($this->request->getParam('size') > 0) ? (int) $this->request->getParam('size') : FConfig::item('config.pageSize');

        $condition_arr = array(
            'limit' => $page_size,
            'offset' => ($page - 1) * $page_size ,
        );
        //分页
        $data['count'] = $this->productPublish_model-> count($condition_arr);
        $pages = new FPagination($data['count']);
        $pages->setPageSize($page_size);
        $pages->setCurrent($page);
        $pages->makePages();

        $data['proTypeList'] = $this->productType_model->findAll();
        $data['publishList'] = array();
        $data['productList'] = array();

        //查询产品
        $proRes = $this->product_model->findAll();
        foreach($proRes as $val){
            $data['productList'][$val['id']] = $val->getAttributes();
        }

        $res = $this->productPublish_model->findAll($condition_arr);
        if (is_array($res)) {
            foreach ($res as $val) {
                $publish = $val->getAttributes();
                $publish['product'] = $data['productList'][$val['product_id']];
                $data['publishList'][] = $publish;
            }
        }

        $data['invest_issue_types'] = FConfig::item('config.invest_issue_types');
        $data['publish_status'] = FConfig::item('config.publish_status');

        $data['page'] = $pages;

        $this->render('publish',$data);
    }
    public function actionProductPublish(){
        $proId = $this->request->getParam('id');
        $product = $this->product_model->findByPk($proId);

        $code_prefix = $product['product_code'];

        $condiation = array(
            'condition'=>"product_id=:product_id",
            'params' => array(':product_id'=>$proId,),
        );
        $count = $this->productPublish_model->count($condiation);
        if ($count) {
            $count+=1;
            $count = $count>10 ? $count :'0'.$count;
        } else {
            $count = '01';
        }

        $condition_attr = array(
            'product_id' =>  $proId,
            'publish_code' =>  $code_prefix.$count,
            'create_time' =>  FF_DATE_TIME,
            'publish_status' =>  1,
        );
        $this->productPublish_model->attributes = $condition_attr;
        $res = $this->productPublish_model->save();
        if ($res) {
            $response['status'] = 100000;
            $response['content'] = 'success';

        } else {
//            print_r($this->product_model);
            $response['status'] = 100001;
            $response['content'] = 'error';
        }

        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 状态 开启和停用
     */
    public function actionModifyProduct () {
        $id = $this->request->getParam('id');
        $publish_status = $this->request->getParam('publish_status');
        $condition = array(
            'publish_status'   =>  $publish_status,
        );
        $res = $this->productPublish_model->updateByPk($id,$condition);
        if($res){
            $response['status'] = 100000;
            $response['content'] = 'success';
        }else{
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    /**
     * 推送
     */
    public function actionPushProduct(){
        $id = $this->request->getParam('id');
        $publish_personal = $this->request->getParam('publish_personal');
        $publish_index = $this->request->getParam('publish_index');
        $condition_attr = array(
            'publish_personal' => $publish_personal,
            'publish_index' => $publish_index
        );
        $res = $this->productPublish_model->updateByPk($id,$condition_attr);
        if($res){
            $response['status'] = 100000;
            $response['content'] = 'success';
        }else{
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }
    /**
     * 获取推送状态
     */
    public function actionGetPush () {
        $id = $this->request->getParam('id');
        $res = $this->productPublish_model->findByPk($id);
        if(!empty($res)){
            $response['status'] = 100000;
            $response['content'] = $res->getAttributes();
        } else {
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }
}