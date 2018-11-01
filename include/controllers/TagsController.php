<?php
/**
 * 登录、退出test
 *
 */
class TagsController extends FController
{
    private $tag_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->tag_model = new Tag();
    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);

        return true;
    }

    public function actionIndex () {

        $condition_attr = array(
            'order' => 'tag_sort asc',
        );
        $result = $this->tag_model->findAll($condition_attr);
        foreach($result as  $value){
            if($value['parent_id'] == 0){
                $data['parent'][$value['id']] = $value->getAttributes();
            }else{
                $data['list'][$value['parent_id']][] = $value->getAttributes();
            }
        }

        foreach($value->getAttributes() as $k=>$v){
            $arr[] = $v;
        }
        $data['tagTypes'] = FConfig::item("config.tag_type");
        $data['tagGains'] = FConfig::item("config.tag_gain");
        $this->render('index',$data);
    }

    /**
     * 标签排序
     */
    public function actionTagSort(){
        $id         = $this->request->getParam('id');               //原记录id
        $new_sort       = $this->request->getParam('new_sort');             //添加的sort值


        //修改原纪录的type_sort
        $condition_attr = array(
            'tag_sort'     =>  $new_sort,
        );
        $result = $this->tag_model->updateByPk($id,$condition_attr);
        if($result){
            $response['status']  = 100000;
            $response['content'] = success;
        }else{

            $response['status']  = 100002;
            $response['content'] = error;
        }
        Yii::app()->end(FHelper::json($response['content'], $response['status']));
    }

    /**
     * 添加
     */
    public function actionAddTag () {
        $tag_name = $this->request->getParam('tag_name');
        $parent_id = $this->request->getParam('parent_id');
        $tag_val = $this->request->getParam('tag_val');
        $tag_type = $this->request->getParam('tag_type');
        $tag_sort = $this->request->getParam('tag_sort');
        $condition_attr = array(
            'tag_name'  => $tag_name,
            'parent_id' => $parent_id,
            'tag_val'   => $tag_val,
            'tag_type'  => $tag_type,
            'tag_sort' => $tag_sort,
        );
        $this->tag_model->attributes = $condition_attr;
        $res = $this->tag_model->save();

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
     * 删除
     */
    public function actionTagDelete () {
        $id = $this->request->getParam('id');

        $condition_attr = array(
            'id' => $id,

        );

        $res = $this->tag_model->deleteByPk($condition_attr);
        if($res){
            $response['status'] = 100000;
            $response['content'] = 'success';

        }else{
            $response['status'] = 100001;
            $response['content'] = 'error';
        }
        Yii::app()->end(FHelper::json($response['content'],$response['status']));
    }

}