<?php
/**
 * 登录、退出test
 *
 */
class BaseDataController extends FController
{
    private $user_model;
    private $userInfo_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->user_model = new User();

    }
//注释test
    protected function beforeAction($action) {

        parent::beforeAction($action);
        //若登录则无法访问
        if($this -> is_login() && in_array($action -> getId(), $this -> access)){

            $this->request->redirect(FF_DOMAIN);
        }

        return true;
    }
    public function actionLogin () {
        $this->render('login');
    }

    public function actionGetLogin(){
        $userName=$this->request->getParam('usrname');
        $rememberme=$this->request->getParam('rememberme');
        $password=$this->request->getParam('password');
        $attr =array(
            'condition'=>"name=:name  and password=:password",
            'params' => array(
                ':name'=>$userName,
                //':password'=>md5($password),
                ':password'=>$password,
            ),
        );

        $res = $this->user_model->find($attr);

        if ($res->id) {
            $this->saveCookie($res->id,$userName,$rememberme);
            $identity=new UserIdentity($userName, $password);
            Yii::app()->user->login($identity);
            $this->redirect(FF_DOMAIN);

        } else {
            $data['status'] = 100001;
            $data['content'] = '用户名或密码错误';
            $this->render('login',$data);
        }

    }
    private function saveCookie ($uid,$uname) {
        $salt = 'FIREFLY';
        $token = FHelper::auth_code("$uid", 'ENCODE', $salt);
//        $attr =array(
//            'token' =>  $token,
//        );
//        $this->user_model->updateByPk($uid,$attr);

        FCookie::set('auth', $token);


    }
}