<?php
/**
 * 封装Yii创建和获取cookie
 *
 */
class FCookie
{
    /**
     * 设置COOKIE
     *
     * @param string $name
     * @param mixed $value
     * @param int $time 以秒为单位，以当前时间为相对点
     */
    public static function set($name, $value, $time = 0)
    {
        $cookie = new CHttpCookie($name, $value);
        //不能设置expire=time()+0;
        if ($time) {
            $cookie->expire = time() + $time;
        }
        $cookie->secure = $_SERVER['SERVER_PORT'] == 443 ? 1 : 0;
        $cookie->domain = '182.92.81.13';
        Yii::app()->request->cookies[$name] = $cookie;
    }

    /**
     * 获取 COOKIE
     * @param string $name
     * @return string
     */
    public static function get($name)
    {
        $cookie = Yii::app()->request->cookies[$name];
        //print_r(Yii::app()->request->cookies);
        return isset($cookie->value) ? $cookie->value : '';
    }

    /**
     * 删除COOKIE
     *
     * @param string $name
     */
    public static function del($name)
    {
        $cookie = Yii::app()->request->getCookies();
        unset($cookie[$name]);
    }
}