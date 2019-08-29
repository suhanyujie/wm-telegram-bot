#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: Samuel
 * Date: 2019/6/1
 * Time: 12:19
 */

ini_set('display_errors', 'on');

use Novel\NovelSpider\Services\DataCacheService;
use Workerman\Worker;
if(strpos(strtolower(PHP_OS), 'win') === 0)
{
    exit("start.php not support windows, please use start_for_win.bat\n");
}
// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
// 标记是全局启动
define('GLOBAL_START', 1);
// 解析配置文件
//定义全局常量
define('ROOT', realpath(__DIR__.'/../../'));
//解析配置文件
$envConfig = parse_ini_file(ROOT . "/.env", true);
DataCacheService::set('envConfigArr', $envConfig);
$dbConfig = $envConfig['start_list_db'] ?? [];



require_once __DIR__ . '/vendor/autoload.php';
// 加载所有Applications/*/start.php，以便启动所有服务
foreach(glob(__DIR__.'/app/*/start*.php') as $start_file)
{
    require_once $start_file;
}
// 运行所有服务
Worker::runAll();
