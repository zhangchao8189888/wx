<?php
/* @var $this JController */
$this->pageTitle = '首页';
?>
<div>
    <div id="content-header">
        <div id="breadcrumb">
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        </div>
    </div>
    <!--Action boxes-->
    <div class="container-fluid">

        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <?php
                $memu_admin = FConfig::item('admin.memu');
                if(!empty($memu_admin)){
                    $color = array('b','g','y','o','s','r');
                    foreach ($memu_admin as $a_k => $a_v) {
                        if(in_array($a_k,$this->user_menu_list)){
                            $random_num = rand(0,count($color)-1);
                            ?>
                            <li class="bg_l<?php echo $color[$random_num]?> span2"> <a href="<?php echo $a_v['controller']?>"> <i class="icon-<?php echo $a_v['icon']?>"></i><?php echo $a_v['resource']?> </a> </li>
                        <?php
                        }
                    }
                }?>
            </ul>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-picture"></i> </span>
                        <h5>最新数据</h5>
                    </div>
                    <div class="widget-content">
                        <ul class="thumbnails">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End-Action boxes-->
    <script type="text/javascript">
        $(document).ready(function(){
            // === jQeury Gritter, a growl-like notifications === //
            $.gritter.add({
                title:  '哗啦科技有你更精彩！',
                text: '期待您de意见，反馈邮箱：<br><br><a href="mailto:admin@aladdin-holdings.com">admin@aladdin-holdings.com</a>',
                image:  'upload/img/demo/envelope.png',
                sticky: true
            });
        });
    </script>
       