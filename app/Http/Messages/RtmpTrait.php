<?php

namespace App\Http\Messages;


trait RtmpTrait{

    /**
     * 节目正常和错误判断规则: task_list中 upload_speed <= 10 * 1024为异常，否则为正常错误数
     * @param $taskList
     */
    public function getProgrammeCount($taskList){
        $normalCount = 0;
        $abnormalCount = 0;
        foreach ($taskList as $item){
            if($item['upload_speed'] > 10240){
                $normalCount++;
            }else{
                $abnormalCount++;
            }
        }

        return [$normalCount, $abnormalCount];
    }

    /**
     * 获取运行时间
     * @param $startTime
     * @return string
     */
    public function getRuntime($startTime){
        $runTime = time() - strtotime($startTime);
        $day = intval($runTime / (3600 * 24));//运行天数
        $hour = intval(($runTime % (3600 * 24)) / 3600);//运行小时
        $minute = intval(($runTime % (3600 * 24)) % 3600 / 60);

        return $day . "天" . $hour . "时" . $minute . "分";
    }

    /**
     * 匹配是否包含某内容
     * @param $taskList
     * @param $str
     * @return bool
     */
    public function getRegexContent($taskList, $str){
        $isContain = false;
        foreach ($taskList as $item){
            $url = $item['url'];
            if(str_contains($url, $str)){
                $isContain = true;
                break;
            }
        }

        return $isContain;
    }




}

