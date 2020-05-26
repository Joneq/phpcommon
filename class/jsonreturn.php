<?php
/**
 * Created by 2020/4/16.
 * User: Joneq
 * Info: 2020/4/16
 * Time: 下午6:14
 */

namespace App\Model\Logic;


class JsonLogic
{

    static public $returnCode = 0;
    static public $returnMsg = '';

    /*
     * json返回的入口函数
     * */
    static public function returnData($data)
    {
        //是数组或者对象视为成功
        if(is_array($data) || is_object($data)){
            return self::successJson($data);
        }

        //是字符串视为失败
        return self::falseJson($data);
    }

    //返回正确的json
    static public function successJson($data)
    {
        return ['code'=>self::$returnCode, 'data'=>$data, 'msg'=>empty(self::$returnMsg)?'查询成功':self::$returnMsg];
    }

    //返回错误的json，如果有其他code,则自己赋值修改
    static public function falseJson($falseString)
    {
        $msg = explode('|',$falseString);
        if (isset($msg[0])){
            $msg = $msg[0];
        }else{
            $msg = $falseString;
        }
        return ['code'=>empty(self::$returnCode)?-1:self::$returnCode, 'data'=>[], 'msg'=>$msg];
    }
}