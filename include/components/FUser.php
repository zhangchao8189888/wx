<?php
/**
 * 用户身份认证
 *  
 */
class FUser
{
	private $_user ; 
	
	public function init(){

	}
	// Load user model.
	public function loadUser()
 	{
        $token = FCookie::get("auth");
        $salt = 'ZHONG_QI_OA';
        list($uid) = explode("\t", FHelper::auth_code($token, 'DECODE', $salt));
      if($uid){
          $userModel = new Admin();
          $attr = array(
              'condition'=>"id=:id",
              'params' => array(':id'=>$uid,),

          );
          $user = $userModel->find($attr);
          $this ->_user = $user;
         if(!$this ->_user){
         Yii::app()->getRequest() ->redirect('/login/error');
       	 }
      }           
        return $this ->_user;
    } 
}
