<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Messages\RtmpTrait;
use DB;

class RtmpController extends Controller
{
    //
    use RtmpTrait;

    public function normalResponse($code, $data = array()){
        return [
            'ok' => $code?true : false,
            'create_time' => date("Y-m-d H:i"),
            'data' => $data
        ];
    }

    public function getSummaryData(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_' . date('Ymd'))->get();

        $re = ['sum_server_count' => 0,
            'sum_server_normal_count' => 0,
            'sum_server_abnormal_count' => 0,
            'sum_programme_normal_count' => 0,
            'sum_programme_all_count' => 0,
            'sum_conn_count' => 0,
            'sum_upload_speed' => 0,
            'sum_download_speed' => 0,
            'sum_fail_count' => 0,
            'sum_warn_count' => 0
        ];
        foreach ($data as $item){
            if(!isset($item['type'])){
                $re['sum_server_abnormal_count']++;//总服务异常数
                $re['sum_server_count']++;
                continue;
            }

            list($normalCount, $abnormalCount) = $this->getProgrammeCount($item['task_list']);//获取节目正常数
            $re['sum_server_count'] += count($item['server_list']);//总服务器数
            $re['sum_server_normal_count'] += count($item['server_list']);//总服务正常数
            $re['sum_programme_normal_count'] += $normalCount;//总正常节目数
            $re['sum_programme_all_count'] += count($item['task_list']);//总节目数
            $re['sum_conn_count'] += count($item['conn_num']);//总连接数
            $re['sum_upload_speed'] += count($item['upload_speed']);//总上传速度
            $re['sum_download_speed'] += count($item['download_speed']);//总下载速度
            $re['sum_fail_count'] += count($item['task_fail_list']);//总错误数
            $re['sum_warn_count'] += isset($item['task_warn_list'])? count($item['task_warn_list']) : 0;//总警告数
        }

        return $this->normalResponse(true, $re);
    }

    public function getConditionData(){
        $mongo = DB::connection('mongodb');
        $data = $mongo->table('rtmp_' . date('Ymd'))->get();

        $re = array();
        foreach ($data as $item){
            if(!isset($item['type'])){
                continue;
            }

            list($normalCount, $abnormalCount) = $this->getProgrammeCount($item['task_list']);//获取节目正常数
            $tmp = array();
            $tmp['ip'] = $item['addition']['ip'];//ip
            $tmp['isp'] = $item['addition']['operator'];//运营商
            $tmp['programme_normal_count'] = $normalCount;//节目正常数
            $tmp['programme_all_count'] = count($item['task_list']);//节目总数
            $tmp['conn_num'] = $item['conn_num'];//连接数
            $tmp['single_speed'] = $item['conn_num'] > 0?round($item['upload_speed'] * 8 / 1024 / 1024 / $item['conn_num'], 2) : 0;//单连接速度(single_speed): upload_speed * 8 / 1024 / 1024 / conn_num
            $tmp['upload_speed'] = round($item['upload_speed'] * 8 / 1024 / 1024, 2);//上传速度
            $tmp['download_speed'] = round($item['download_speed'] * 8 / 1024 / 1024, 2);//下载速度
            $tmp['bandwidth'] = $item['addition']['bandwidth'];//预计带宽
            $tmp['actual_bandwidth'] = $item['addition']['actual_bandwidth'];//峰值带宽
            $tmp['version'] = $item['version'];//版本号
            $tmp['type'] = $item['type'];//类型
            $tmp['run_time'] = $this->getRuntime($item['start_time']);//运行时间
            $tmp['build_time'] = $item['build_time'];//更新时间　
            $tmp['config_version'] = $item['config_version'];//版本配置

            $re[] = $tmp;
        }

        return $this->normalResponse(true, $re);
    }

}
