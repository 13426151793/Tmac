<?php
namespace frontend\components;


use yii;

/**
 *
 * @author Tmac
 *        
 */
define("LOG",ROOT.'/frontend/runtime/logs/');
class Log
{
    // TODO - Insert your code here

    // 日志级别 从上到下，由低到高
    const EMERG   = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT    = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN    = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE  = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO     = 'INFO';  // 信息: 程序输出信息
    const DEBUG   = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效
    const LOG_RECORD_LEVEL       =   array('EMERG','ALERT','CRIT','ERR','WARN','NOTIC','INFO','DEBUG','SQL');  // 允许记录的日志级别
    const LOG_FILE_SIZE  = '2097152';	// 日志文件大小限制
    
    // 日志记录方式
    const SYSTEM = 0;
    const MAIL      = 1;
    const TCP       = 2;
    const FILE       = 3;
    
    // 日志信息
    static $log =   array();
    
    // 日期格式
    static $format =  '[ c ]';
    
    static $config = [
        'time_format' => 'c',
        'file_size'   => 2097152,
        'path'        => LOG,
    ];
    /**
     */
    function __construct()
    {
        
        // TODO - Insert your code here
    }

    /**
     +----------------------------------------------------------
     * 记录日志 并且会过滤未经设置的级别
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param boolean $record  是否强制记录
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function record($message,$level=self::ERR,$record=false) {
        if($record || in_array($level,self::LOG_RECORD_LEVEL)) {
            $now = date(self::$format);
            self::$log[] =   "{$now} {$level}: {$message}\r\n";
        }
    }
    
    /**
     +----------------------------------------------------------
     * 日志保存
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function save($type=self::FILE,$destination='',$extra='')
    {
        if(empty($destination))
            $destination = self::$config['path'].date('y_m_d').".log";
        if(self::FILE == $type) { // 文件方式记录日志信息
            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if(is_file($destination) && floor(self::LOG_FILE_SIZE) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log(implode("",self::$log), $type,$destination ,$extra);
        // 保存后清空日志缓存
        self::$log = array();
        //clearstatcache();
    }
    
    /**
     +----------------------------------------------------------
     * 日志直接写入
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param integer $type 日志记录方式
     * @param string $destination  写入目标
     * @param string $extra 额外参数
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function write($message,$level=self::ERR,$type=self::FILE,$destination='',$extra='')
    {
        $now = date(self::$format);
        if(empty($destination))
            $destination = self::$config['path'].date('y_m_d').".log";
        if(self::FILE == $type) { // 文件方式记录日志
            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if(is_file($destination) && floor(self::LOG_FILE_SIZE) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log("{$now} {$level}: {$message}\r\n", $type,$destination,$extra );
        //clearstatcache();
    }
    
    /**
     * 日志写入接口
     * @access public
     * @param string $message 日志信息
     * @param string $component 业务组件名
     * @param array $extra 额外参数
     * @return void
     */
    static function savelog($message,$component='kytz',$extra=[]) {

        $log['message'] = $message;
        $log['component'] = $component;
        $log['module'] = __CLASS__;
        $log['method'] = __FUNCTION__;
        $log['type'] = !isset($extra['type'])?:'interface';
        $log['state'] = !isset($extra['state'])?:'success';
        $log['level'] = !isset($extra['level'])?:self::INFO;
        $log['ipaddr'] = $_SERVER['REMOTE_ADDR'];
        $log['time'] = time();
        yii::$app->redis->lpush('loglist', 'sss');
        $len = yii::$app->redis->len('loglist');
        for ($i = 0; $i < $len; $i++) {
            $datas = yii::$app->redis->pop('loglist');
            $component_dir = $datas['component']?$datas['component'].'/':'';
    
            $destination = self::$config['path'] . $component_dir . date('Y/m/d') . '/' . date('H') . '.log';
    
            $path = dirname($destination);
            !is_dir($path) && mkdir($path, 0755, true);
    
            //检测日志文件大小，超过配置大小则备份日志文件重新生成
            if (is_file($destination) && floor(self::$config['file_size']) <= filesize($destination)) {
                rename($destination, dirname($destination) . '/' . time() . '-' . basename($destination));
            }
    
            error_log(json_encode($datas)."\r\n", 3, $destination);
        }
    
    }

    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>