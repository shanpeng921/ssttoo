<?php

namespace app\components;

use app\models\UcUser;
use Yii;

class AppServerApi {


    public static function sendToApp($params, $messageType = 'notifications')
    {
        //return false;
        $appConfig = Yii::$app->params['appConfig'];
        $appSecret = $appConfig['secret'];

        $sign = self::generateSign($appSecret);

        $addition = array(
            'client_key' => $appConfig['key'],
            'sign' => $sign,
        );

        /*
        $receiverUniqueId = $params['receiver_unique_id'];
        if (!$receiverUniqueId) {
            return ;
        }
        $receiveInfo = UcUser::getUserInfo($receiverUniqueId);
        if (!$receiveInfo) {
            return ;
        }
        unset($params['receiver_unique_id']);
        $params['receivers'] = $receiveInfo->username;
        */

        $params = array_merge($params, $addition);
        $url = $appConfig['url'];
        switch($messageType) {
            case 'task':
                $url .= $appConfig['taskPath'];
                break;
            case 'alarms':
                $url .= $appConfig['alarmsPath'];
                break;
            case 'notifications':
                $url .= $appConfig['notifyPath'];
                break;
        }
        return self::curlPost($url, $params);
    }

    public static function curlPost($url, $data)
    {
        //return false;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,5);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 不需要知道任务是否执行完成
        $result = curl_exec($ch);
        if ($result === false) {
            $resultString = 'Curl error : ' . curl_error($ch);
        } else {
            //echo $result;
            //$resultString  = var_export($result, true);
            return json_decode($result,true);
        }
        //Yii::log($resultString, 'info', 'customize');
        //var_dump($result);
        //echo $resultString;
        //die;
        curl_close($ch);
        //return ($resultString);
    }

    public static function generateSign($appSecret)
    {
        $sso = new Sso();
        $token = array(
            time(),
            $sso->get_ip(),
            $sso->callback_url
        );
        $token = implode(',', $token);
        $encToken = $sso->des_encrypt_hash($appSecret, $token);
        return $encToken;
    }
}
