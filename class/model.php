<?php
/**
 * Created by 2020/4/18.
 * User: Joneq
 * Info: 2020/4/18
 * Time: 下午5:25
 */

namespace App\Model;



class PickTaskModel
{
    //判断是否继续
    static public function checkContinue($val,$key='')
    {
        if ($val ===''){
            return true;
        }
        return false;
    }

    static public function getBaseWhereObj($data)
    {
        $obj = self::where('is_del','=',0);

        foreach ($data as $key=>$value){
            if (self::checkContinue($value))continue;
            switch ($key){
                case 'create_time':
                    $obj = $obj->where('add_time','>=',strtotime($value));break;
                case 'end_time':
                    $obj = $obj->where('add_time','<=',strtotime($value));break;
                default:
                    continue;
            }
        }
        return $obj;
    }
}