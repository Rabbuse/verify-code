<?php
namespace stMeteor\code;
/**
 * Created by PhpStorm.
 * User: Meteor
 * Date: 2019/4/2
 * Time: 23:05
 */
class Util{
    //是否直接返回json数据给接口
    protected static $api = true;

    /**
     * 设置是否直接返回数据到接口
     * @param $value
     */
    public static function returnApi($value){
        self::$api = $value;
    }

    /**
     * api接口json格式返回成功
     * @param string $msg
     * @param null $data
     * @param int $status 200正常
     * @return array
     */
    public static function returnSuccess($data = null, $msg = '操作成功', $status = 200){
        $data = [
            'code' => 0,
            'data' => $data,
            'msg' => $msg,
            'status' => $status,
        ];
        if(self::$api){
            throw new \think\exception\HttpResponseException(\think\Response::create($data, 'json'));
        }
        return $data;
    }

    /**
     * api接口json格式返回错误
     * @param string $msg
     * @param null $data
     * @param int $status 200正常
     * @return array
     */
    public static function returnError($msg = '操作失败', $data = null, $status = 200){
        $data = [
            'code' => 1,
            'data' => $data,
            'msg' => $msg,
            'status' => $status,
        ];
        if(self::$api){
            throw new \think\exception\HttpResponseException(\think\Response::create($data, 'json'));
        }
        return $data;
    }


    /**
     * curl请求
     * @param $url 请求地址
     * @param bool $post 是否post方式
     * @param array $data post请求参数
     * @param array $headers 请求头部参数
     * @return bool|string
     */
    public static function doCurl($url, $post = false, $data = [], $headers = []){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if($post){
            curl_setopt($ch, CURLOPT_POST, 1);
            if(is_array($data)){
                $data = http_build_query($data);
            }else{
                if(is_array(json_decode($data, true))){
                    $data = http_build_query(json_decode($data, true));
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    return true;
        return curl_exec($ch);
    }

    /**
     * 检验手机号格式
     * @param $tel
     * @return false|int
     */
    public static function checkMobile($tel){
        return preg_match("/^1[3|4|5|7|8|9][0-9]{9}$/", $tel);
    }
}