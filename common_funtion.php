<?php
/**
 * Created by 2020/5/26.
 * User: Joneq
 * Info: 2020/5/26
 * Time: 下午5:19
 */


class CommonLogic
{


    //检测值是否为空
    static public function checkEmpty($key,$data)
    {
        foreach ($key as $value){
            if (!isset($data[$value]) || empty($data[$value])){
                throw new \Exception($value.'is null');
            }
        }
    }



    //获取存在key的数据
    static public function getHaveKeyCn($data)
    {
        foreach ($data as $key=>$value){
            if ( ($cn = self::getKeyCn($key,$value)) !== false){
                $data[$key."_cn"] = $cn;
            }
        }
        return $data;
    }
    
    //获取key对应的中文
    static public function getKeyCn($key,$value)
    {
        switch ($key){
            case 'confirm_user_id'://确认人
                $value = '暂未录入';break;
            case 'out_store_status':
                $value = OutStoreLogic::getOutStoreStatus($value);break;
            default:
                $value = false;
                break;
        }
        return $value;
    }



    //curl请求
    static public function curl($url, $data, &$response = '')
    {
        $Domain = 'baidu.com';
        $url = $Domain . $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        if(!empty($header)){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if (is_array($data)) {
            $result = array_filter($data, function($v){
                return is_object($v) && get_class($v) == 'CURLFile';
            });
            if (empty($result)) {
                $data = http_build_query($data);
            }
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);
        if ($response === FALSE) {
            return false;
        }
        curl_close($ch);
        return json_decode($response, true);
    }



}