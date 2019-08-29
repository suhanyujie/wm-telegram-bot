<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/6/1
 * Time: 13:38
 */

namespace App\Common\Http;


class HttpRequest
{
    /**
     * 发http请求：get/post
     * @param array $params
     * @return array
     */
    public static function curlHttp($params=[])
    {
        $options = [
            'url'            => '',// string 请求的url
            'method'         => 'get',// string 请求类型
            'postData'       => [],// array 数组类型
            'addHeaderArr'   => [],// array http头信息 ['Content-type:application/json',]
            'bodyType'       => 1,// 1 正常的form表单数据;2 json化的body数据
            'timeout'        => 5,// int 等待响应的超时时间
            'connectTimeout' => 1,// int 发起连接的超时时间
        ];
        $options = array_merge($options, $params);
        $curlOpt = [
            CURLOPT_URL            => $options['url'],
            CURLOPT_TIMEOUT        => $options['timeout'],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER         => 0,// 为0时，只获取请求body数据
            CURLOPT_USERAGENT      => "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)",
        ];
        $options['method'] = strtoupper($options['method']);
        if ($options['method'] == 'POST') {
            $curlOpt[CURLOPT_POST] = 1;
            $curlOpt[CURLOPT_FOLLOWLOCATION] = 1;
            switch ($options['bodyType']) {
                case 2:// json
                    $options['addHeaderArr'][] = "Content-type:application/json";
                    $curlOpt[CURLOPT_POSTFIELDS] = json_encode($options['postData'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    break;
                case 1:// 表单提交
                default:
                    $options['addHeaderArr'][] = "Content-Type: application/x-www-form-urlencoded";
                    $curlOpt[CURLOPT_POSTFIELDS] = http_build_query($options['postData']);
            }
        } else {
            $curlOpt[CURLOPT_POST] = 0;
        }
        if ($options['addHeaderArr']) {
            $curlOpt[CURLOPT_HTTPHEADER] = $options['addHeaderArr'];
        }
        try{
            $ch = curl_init();
            curl_setopt_array($ch, $curlOpt);
            $response = curl_exec($ch);
            $errno = curl_errno($ch);
            $errMsg = curl_error($ch);
            if ($errno)return ['status'=>$errno,'msg'=>$errMsg];
            curl_close($ch);
            return ['status'=>1,'data'=>$response,];
        } catch (\Exception $e) {
            return ['status'=>-99999,'msg'=>$e->getMessage()];
        }
    }
}
