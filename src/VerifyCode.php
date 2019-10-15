<?php

namespace stMeteor\code;
/**
 * Created by PhpStorm.
 * User: Meteor
 * Date: 2019/4/2
 * Time: 23:05
 */

use stMeteor\code\model\VerifyCode as CodeModel;

class VerifyCode{

    //验证码长度
    protected $length = 4;
    //验证码文本
    protected $text = '【签名】您的验证码是#code#。如非本人操作，请忽略本短信';
    //云片网apikey
    protected $apikey = 'apikey';
    //过期时间(秒)
    protected $overdue = 1800;
    //是否直接返回json数据给接口
    protected $api = true;

    /**
     * 设置配置参数
     * @param $name 参数名称
     * @param null $value 参数值
     * @throws \Exception
     */
    public function setConfig($name, $value = null){
        if(empty($name)){
            throw new \Exception('value cannot be empty!');
        }
        if(is_array($name)){
            foreach ($name as $key => $v){
                if(is_null($v)){
                    throw new \Exception('value cannot be null!');
                }
                isset($this->{$key}) && $this->{$key} = $v;
            }
        }else{
            if(is_null($value)){
                throw new \Exception('value cannot be null!');
            }
            isset($this->{$name}) && $this->{$name} = $value;
        }
    }

    //获取验证码
    //tel 手机号
    public function getCode($tel)
    {
        Util::returnApi($this->api);
        if(!Util::checkMobile($tel)){
            return Util::returnError('手机号不正确');
        }
        $code = new CodeModel();
        //验证码发送间隔一分钟
        $info = $code->findData([['tel', '=', $tel]]);
        if ($info && (time() - $info['send_time'] < 60)) {
            return Util::returnError('验证码发送时间间隔不得小于一分钟');
        }
        //随机指定位数的验证码
        $random = random_int(
            str_pad(1, intval($this->length),'0',STR_PAD_RIGHT),
            str_pad(9, intval($this->length),'9',STR_PAD_RIGHT)
        );
        //curl云片网接口
        $data = ['text' => str_replace('#code#', $random, $this->text), 'apikey' => $this->apikey, 'mobile' => $tel];
        $result = Util::doCurl('https://sms.yunpian.com/v2/sms/single_send.json', true, $data);
//                //解析返回结果（json格式字符串）
//            $array = json_decode($json_data,true);
        var_dump($result);
        $data = [
            'tel' => $tel,
            'code' => $random,
            'send_time' => time(),
        ];
        if($info){
            $data['id'] = $info['id'];
        }
        $code->saveData($data);
        return Util::returnSuccess('验证码已发送');
    }

    /*
      * 检验验证码
      * tel 电话
      * code 验证码
      * */
    public function checkCode($tel, $code)
    {
        Util::returnApi($this->api);
        if(!Util::checkMobile($tel)){
            return Util::returnError('手机号不正确');
        }
        if(empty($code)){
            return Util::returnError('请传入验证码');
        }
        $model = new CodeModel();
        $where[] = ['tel', '=', $tel];
        $where[] = ['code', '=', $code];
        $info = $model->findData($where);
        if ($info) {
            //验证码过期时间
            if ($info['send_time'] < (time() - $this->overdue)) {
                return Util::returnError('验证码已过期');
            } else {
                $info->send_time = 0;
                $info->save();
                return Util::returnSuccess('验证成功');
            }
        } else {
            return Util::returnSuccess('验证码错误');
        }
    }
}