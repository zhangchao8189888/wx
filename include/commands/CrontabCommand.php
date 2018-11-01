<?php
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', '9000');
ini_set('ignore_user_abort', 'on');

date_default_timezone_set("Asia/Shanghai");
if (!defined('YII_CMD')) define('YII_CMD', true);
/**
 * Cron
 * 脚本文件
 * @author zhangtao
 */
class CrontabCommand extends CConsoleCommand {

	protected $_api_client;

	protected $_api_deal_comment_url;

	protected $_del_comments_url;

	protected $_recover_url;

	protected $_put_deal_data_url;

    protected $_api_url_index_set_updateuser;


	public function  __construct($name,$runner){
	    parent:: __construct($name,$runner);
	    
	    $this -> _api_client = new JApiClient();

	    //请求网安处理结果
	    $this -> _api_deal_comment_url = 'http://wm.interface.focus.cn/api/focus/deal.php';

	    //删除一条评论
        $this ->_del_comments_url       = 'http://focus-pinge.apps.sohuno.com/jjInteractComment/delete';

        //恢复评论接口（接受批量逗号分隔）
        $this ->_recover_url			= 'http://focus-pinge.apps.sohuno.com/jjInteractComment/updateStatusByIds';

        //发送品格处理数据到网安
        $this ->_put_deal_data_url 		= "http://wm.interface.focus.cn/api/focus/delete.php";

        //修改用户信息
        $this->_api_url_index_set_updateuser = 'http://focus-pinge.apps.sohuno.com/jjUser/update';          

	}

	public function actionDealcomment()
    {
       $request_data = array('product'  =>'focus-pinge',
	                         'sign'     =>'ef4928f99244eaaaeecbd42321d37241',
	                         'type'     =>'article',
	                         'debug'	=>'false');
       $result = Fn::simpleRequest($this->_api_deal_comment_url,$request_data);
       $time = date("Y-m-d H:i:s",time());
        if ($result!=="[]") {
        	$re = CJSON::decode($result);
            echo "---请求网安处理结果列表**S**--time:--$time\n";
            print_r($re);
            echo "---请求网安处理结果列表**E**--time:--$time\n";
        	foreach ($re as $key => $value) {
        		if ($value['warn'] == 2) { //删除
        			$callback = CJSON::decode($value['callback']);
        			$bac = $this->_api_client->send($this->_del_comments_url,array('id'=>$callback['related_id']),'POST');
                    echo "数据中心删除结果：--commentid:--".$callback['related_id']."--time:--".$time."---\n";
        			if ($bac['errorCode'] === JConfig::item('config.errorcode.right_port')) {
        				$request_data['id'] = $value['id'];
        				$put_re = Fn::simpleRequest($this->_put_deal_data_url,$request_data);
                        $put_re = CJSON::decode($put_re);
                        if($put_re['state'])
                        {
                           echo "caseid:--".$callback['caseid']."--commentid:---".$callback['related_id']."---delete succ--time:--$time\n"; 
                        }else
                        {
                           echo "提交网安数据失败： caseid:--".$callback['caseid']."--commentid:---".$callback['related_id']."---delete fail--time:--$time\n";
                        }
        				
        			}

        		}elseif ($value['warn'] == 3) {//通过

        			$request_data['id'] = $value['id'];
        			$callback = CJSON::decode($value['callback']);
        			$data = array('status' =>1,'auditAdminId' =>'10086','ids'=>$callback['related_id']);
        			$put_re_data = Fn::simpleRequest($this->_put_deal_data_url,$request_data);
                    echo "网安通过结果：--".$put_re_data."--time:--$time\n";
      				$put_re_data = CJSON::decode($put_re_data);
                    
        			if ($put_re_data['state']) {
        				$bac = $this->_api_client->send($this ->_recover_url,$data,'POST');
        				if ($bac['errorCode'] === JConfig::item('config.errorcode.right_port')) 
        				{
        					echo "caseid:--".$callback['caseid']."--commentid:---".$callback['related_id']."---recover succ--time:--$time\n";
        				}else
        				{
        					echo "caseid:--".$callback['caseid']."--commentid:---".$callback['related_id']."---recover fail--time:--$time\n";
        				}
        			}else
        			{
        				echo "id:".$value['id']."--warn:".$value['warn']."--comment_id:".$callback['related_id']."--program not to deal anything--time:--$time\n";
        			}
        		}
        	}
        }else {
        	echo "time:".date('Y-m-d H:i:s')."--result:deal succ or have no data\n";
            var_dump(CJSON::decode($result));
        }   
    }

    /**
     * 品格账户设置和头像接入网安函数
     * @return [type] [description]
     */
    public function actionDealuserinfo() {

        $request_data = array(
            'product'  => 'focus-passport',
            'sign'     => '3ff42ed70c6595ac56596c5c75479a33',
            'type'     => 'focus-pinge_user'
        );
       
        $result = Fn::simpleRequest($this->_api_deal_comment_url, $request_data);
        $time = date("Y-m-d H:i:s",time());
        if ($result!=="[]") {
            $re = CJSON::decode($result);
            echo "---请求网安处理结果列表**S**--time:--$time\n";
            print_r($re);
            echo "---请求网安处理结果列表**E**--time:--$time\n";
            foreach ($re as $key => $value) {
                if ($value['warn'] == 3) { //删除
                    $callback = CJSON::decode($value['callback']);

                    $condition_arr = array(
                        'uid' => $callback['uid'],
                        'nickName' => $callback['uid'], 
                        'description' => '', 
                        'headImageId' => 0,
                    );

                    $bac = $this->_api_client->send($this->_api_url_index_set_updateuser, $condition_arr, 'post');

                    echo "数据中心处理结果：--uid:--".$callback['uid']."--time:--".$time."---\n";

                    if ($bac['errorCode'] === JConfig::item('config.errorcode.right_port')) {
                        $request_data['id'] = $value['id'];
                        $put_re = Fn::simpleRequest($this->_put_deal_data_url, $request_data);
                        $put_re = CJSON::decode($put_re);

                        if($put_re['state']) {
                           echo "uid:--".$callback['uid']."---update succ--time:--".$time."\n";
                        } else {
                           echo "提交网安数据失败： uid:--".$callback['uid']."---update fail--time:--".$time."\n"; 
                        }
                        
                    }

                }elseif ($value['warn'] == 2) {//通过

                    $request_data['id'] = $value['id'];
                    $put_re = Fn::simpleRequest($this->_put_deal_data_url, $request_data);
                    $put_re = CJSON::decode($put_re);

                    if($put_re['state']) {
                       echo "uid:--".$callback['uid']."---update succ--time:--".$time."\n";
                    } else {
                       echo "提交网安数据失败： uid:--".$callback['uid']."---update fail--time:--".$time."\n"; 
                    }
                }
            }
        }else {
            echo "time:".date('Y-m-d H:i:s')."--result:deal succ or have no data\n";
            var_dump(CJSON::decode($result));
        }   
    }

	public function actionTest() {
       echo $this -> _api_prefix . "\n";
       echo Fn::CountStrChar('TEST');
    } 

}
?>