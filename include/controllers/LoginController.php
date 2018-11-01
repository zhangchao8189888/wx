<?php
/**
 * 登录、退出test
 *
 */
class LoginController extends FController
{
    public $layout='//layouts/login';
    private $access = array('login','register','getLogin');
    private $user_model;
    private $admin_model;
    private $userInfo_model;

    public function __construct($id, $module = null) {

        parent::__construct($id, $module);
        $this->admin_model = new Admin();

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

        $res = $this->admin_model->find($attr);
        //print_r($res);echo $res->id;exit;
        if ($res && $res->id) {
            $this->saveCookie($res->id,$userName,$rememberme);
            if ($res->admin_type == 3) {

                $this->redirect(FF_DOMAIN);
            } else {

                $this->redirect(FF_DOMAIN);
            }

        } else {
            $data['status'] = 100001;
            $data['content'] = '用户名或密码错误';
            $this->render('login',$data);
        }

    }
    private function saveCookie ($uid,$uname) {
        $salt = 'ZHONG_QI_OA';
        $token = FHelper::auth_code("$uid", 'ENCODE', $salt);
//        $attr =array(
//            'token' =>  $token,
//        );
//        $this->user_model->updateByPk($uid,$attr);

        FCookie::set('auth', $token);


    }
    /*登出*/
    public function actionLogout()
    {
        FCookie::set('auth', '' ,-3600);
        $this->render('login');

    }
}