<?php
/**
 * Created by 2019/10/8.
 * User: Joneq
 * Info: 导出模型类
 * Time: 下午1:58
 */

namespace App\Model\Logic;

use Excel;
class ExportLogic
{

    //获取excel的title数组
    static public function getExcelZero($titleKey='')
    {
        switch ($titleKey){
            case 'userPondAll':
                $titleArr = ['会员账号','公司名称','客服名称','注册时间','客户来源', '联系类型','是否下单'];
                break;
            case 'userFollowAll':
                $titleArr = ['会员账号','公司名称','注册时间','客户来源', '联系类型','是否下单'];
                break;
            default:
                $titleArr = [];
        }

        return $titleArr;
    }



    //获取excel的data数组
    static public function getDataKeyArr($dataKey='')
    {
        switch ($dataKey){
            case 'userPondAll':
                $dataKeyArr = ['account','com_name','sale_id','create_time','channel_source','type','no_create_order'];
                break;
            case 'userFollowAll':
                $dataKeyArr = ['account','com_name','create_time','channel_source','type','no_create_order'];
                break;
            default:
                $dataKeyArr = [];
        }

        return $dataKeyArr;
    }


    //获得组合的表格数据
    static public function getExcelAllData($exportKey,$excelNeedData)
    {
        //获取需要的数组，以及行头名称
        $excelZeroArr = self::getExcelZero($exportKey);
        $excelKeyArr = self::getDataKeyArr($exportKey);

        $length = count($excelKeyArr);

        $excelData[0] = $excelZeroArr;

        //拼接数据
        foreach ($excelNeedData as $key=>$value){

            for ($i=0;$i<$length;$i++){
                $excelData[$key+1][$i] = self::getDefaultValue($excelKeyArr[$i],array_get($value,$excelKeyArr[$i],' '));
            }
        }

        return $excelData;
    }

    //获取默认值以及特殊的默认值
    static public function getDefaultValue($name,$value)
    {
        switch ($name){
            case 'is_get':
                $defaultValue = empty($value)?'否':'是';
                break;
            case 'is_need_buy':
                $defaultValue = empty($value)?'否':'是';
                break;
            default:
                $defaultValue = $value;
                break;
        }

        return $defaultValue;
    }

    //输出一般的表格
    static public function exportExcelNormal($exportKey,$excelNeedData,$excelTitle = '表格模板')
    {
        try{

            $excelData = self::getExcelAllData($exportKey,$excelNeedData);

            Excel::create($excelTitle,function($excel) use ($excelData){
                $excel->sheet('sheet1', function($sheet) use ($excelData){
                    foreach(range("A","AH") as $L){
                        $sheet->setWidth($L,20);
                    }
                    $sheet->rows($excelData);
                });
            })->export('xls');

        }catch(\Exception $e){
            return $e->getMessage();
        }

    }


    //输出一般的表格
    static public function saveExcelNormal($data)
    {
        try{

            $header_arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            $objPHPExcel = new \PHPExcel();//实例化一个要保存的phpExcel对象

            //在激活的工作区写入数据 (数组写入数据演示)
            $startRow = 1;
            foreach ($data as $indexKey=>$row) {
                foreach ($row as $key => $value){
                    //这里是设置单元格的内容
                    $objPHPExcel->getActiveSheet()->setCellValue($header_arr[$key].$startRow,$value);
                }
                $startRow++;
            }

            $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
            $file = '/excel/problem/'.rand(1,100).time().'.xls';
            $objWriter->save(public_path().$file);
        }catch(\Exception $e){
            return $e->getMessage();
        }

        return $file;
    }

}