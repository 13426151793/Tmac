<?php
namespace frontend\components;


use yii;


/**
 * @desc 工具类
 * @author Tmac
 *
 */
define("LOG",ROOT.'/frontend/runtime/logs/');
class Uitl
{
    // TODO - Insert your code here

    /**
     */
    function __construct()
    {

        // TODO - Insert your code here
    }

    /**
     * 请求错误返回格式化json数据
     */
    function jsonError($code,$msg='',$flag = true)
    {
        $arr = array('result'=>"fail", 'code'=>$code,'msg'=>$msg);
        if($flag){
        	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        	exit();
        } else{
        	return json_encode($arr, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 请求正确返回格式化json数据
     */
    function jsonSucc($result_array=array(),$flag = true){

        $arr = array("result"=>"succ","code"=>'0000');
        if(!empty($result_array))
        {
            $arr += $result_array;
        }
        if($flag){
        	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
        	exit();
        } else{
        	return json_encode($arr, JSON_UNESCAPED_UNICODE);
        }

    }

    /**
     * 模拟Post
     * @param string $url 请求URL
     * @param  $post_data 请求参数
     * @param int   $timeout 超时时间 （毫秒）
     */
    function curlPost($url,  $post_data, $post_head = 0,  $timeout = 2000)
    {
        if(!empty($post_data) && is_array($post_data))
        {
            $post_data = http_build_query($post_data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1) ;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $post_head) ;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0");
        //为了支持cookie
        curl_setopt($ch, CURLOPT_COOKIEJAR , 'cookie.txt') ;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);    //注意，毫秒超时一定要设置这个
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);  //超时毫秒，cURL 7.16.2中被加入。从PHP 5.2.3起可使用
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);
        $result = curl_exec($ch);
        try {
            if($result === false)
            {
                throw new \Exception(curl_error($ch));
            }
        } catch (\Exception $e){
            throw new \Exception($e->getMessage());;
        }
        curl_close($ch);
        return $result;
    }

    /**
     * @desc 模拟Curl get\Post请求
     * @param unknown $url
     * @param unknown $data
     * @param string $type
     * @param number $port
     * @param number $timeout
     * @return mixed
     */
    function request($url, $data = array(), $type = 'get', $port = 0, $timeout = 30) {
        $ch = curl_init();
        if ($type == 'get' && $data) {
            $url = $url . '?' . http_build_query($data);
        }
        //echo $url."<hr>";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        if (!empty($port)) {
            curl_setopt($ch, CURLOPT_PORT, $port);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($type == 'post') {

            curl_setopt($ch, CURLOPT_POST, 1);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($ch);
       try {
            if($result === false)
            {
                throw new \Exception(curl_error($ch));
            }
        } catch (\Exception $e){
            throw new \Exception($e->getMessage());;
        }
        curl_close($ch);

        return $result;
    }
    /**
     * @desc 自定义日志
     * @example Yii::$app->util->log("接口返回参数:".($result)); 传入的数值$result必须为数组
     * 或者  Yii::$app->util->log("接口返回参数:".$_SERVER['HTTP_USER_AGENT']."\t function".__FILE__);用.号拼接，输出字符串的日志
     * @param array or string
     *
     */
    public function log($data,$category = 'system'){
        $date =date('Y-m-d');
        if (is_array($data)){
            error_log(date('m月d日 H:i:s')."\t".Service::getIP()."\t".json_encode($data, JSON_UNESCAPED_UNICODE)."\r\n", 3,LOG."/{$category}_{$date}_.log");
        }else {
            error_log(date('m月d日 H:i:s')."\t".Service::getIP()."\t".$data."\r\n", 3,LOG."/{$category}_{$date}_.log");
        }
    }

    /**
     * @DESC HTTP header Curl post/get  request
     */
    public function CurlHeader($_post_url,$data){
        $post = http_build_query($data);
        $curl = curl_init ( $_post_url );
        curl_setopt ( $curl, CURLOPT_HEADER, 0 );
        $header = array ();
        $header [] = 'Host:kyfront';
        $header [] = 'Connection: keep-alive';
        $header [] = 'User-Agent: Mozilla/4.0';
        $header [] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $header [] = 'Accept-Language: zh-CN,zh;q=0.8';
        $header [] = 'Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3';
        $header [] = 'Cache-Control:max-age=0';
        $header [] = 'Cookie:t_skey=p5gdu1nrke856futitemkld661; t__CkCkey_=29f7d98';
        $header [] = 'Content-Type:application/x-www-form-urlencoded';
        curl_setopt ( $curl, CURLOPT_HTTPHEADER, $header );
        curl_setopt ( $curl, CURLOPT_POST, 1);
        curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data );
        $result = curl_exec ( $curl );
        try {
            if($result === false)
            {
                throw new \Exception(curl_error($curl));
            }
        } catch (\Exception $e){
            throw new \Exception($e->getMessage());;
        }
        curl_close ( $curl );
        return $result;
    }

    /**
     */
    function __destruct()
    {

        // TODO - Insert your code here
    }


}

?>