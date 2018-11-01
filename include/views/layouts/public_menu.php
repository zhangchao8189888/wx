  <div id="sidebar">
        <ul>
            <li <?php if($this->_controller == 'site'){ ?>class="active"<?php } ?>><a href="<?php echo FF_DOMAIN;?>"><i class="icon icon-home"></i> <span>首&nbsp;页</span></a> </li>
        	 <?php 
	            $memu_admin = FConfig::item('admin.memu');
				if(!empty($memu_admin)){
		            foreach ($memu_admin as $a_k => $a_v) {

		                if(in_array($a_k,$this->user_menu_list)){
		        ?>
                    <li class="<?php 
                    	$controller_class = "";
                    	if(isset($a_v['son'])){
                    		$controller_class = "submenu";
                    		if($a_v['controller'] == $this->_controller){
                    			$controller_class .= " open";
                    		}
                    	}else{
                    		if($a_v['controller'] == $this->_controller){
                    			$controller_class = "active";
                    		}
                    	}
                    	echo $controller_class;
                     ?>">
                          <a href="<?php echo FF_DOMAIN.'/'.$a_v['controller'].'/'?>">
                          <i class="icon icon-<?php echo $a_v['icon']?>"></i>
                          <span><?php echo $a_v['resource']?></span>
                          <?php if(isset($a_v['son'])){?>
                          <span class="label label-important"><?php echo count($a_v['son'])?></span>
                          <?php }?>
                          </a>
                          <?php if(isset($a_v['son'])){?>
                          <ul>
                          <?php 
                          	$parm_flag = false;
                          	if(in_array($a_v['controller'], array('fragment','tag'))){
                          		$parm_flag = true;
                          	}
                          	foreach ($a_v['son'] as $s_k => $s_v) { 
                          		$active_flag = false;
                          		$url = '/'.$a_v['controller'].'/'.$s_v['action'].'/';
                          		if($parm_flag){
                          			$url .= $s_k.'/';
                          		}
                          		if($parm_flag){
                          			$id = $this->request->getParam('id') ? $this->request->getParam('id') : 1;
                          			if($a_v['controller'].$s_v['action'] == $this->_controller.$this->_action && $id == $s_k){
                          				$active_flag = true;
                          			}
                                //碎片特殊处理
                                if($this->_controller == 'fragment' && $this->_action =="edit" && $s_k == $this->request->getParam('type')){
                                  $active_flag = true;
                                }
                          		}else{
                          			if($a_v['controller'].$s_v['action'] == $this->_controller.$this->_action){
                          				$active_flag = true;
                          			}
                          		}
                                $target = '';
                                if (!empty($s_v['is_ref'])) {
                                    $target = 'target="_blank"';
                                } else {
                                    $target = 'target="_self"';
                                }
                          	?>
					        <li class="<?php echo $active_flag?'active' : ' ' ?>">
					        <a href="<?php echo FF_DOMAIN.$url?>" <?php echo $target;?>><?php echo $s_v['resource'];?></a>
					        </li>
					      <?php }?>  
					      </ul>
                          <?php }?>
                    </li>
		        <?php
	            }}
             }
	        ?>
        </ul>
</div>

<!--sidebar-menu-->
<script>
    
</script>