<?php
/**
 * Created by .
 * Date 2019-10-16
 * Time 16:03
 * User chenlei
 */

require __DIR__ . "/../vendor/autoload.php";

use api\DateApi as DateApi;

//新建一个 http server
$server = new Swoole\Http\Server('0.0.0.0', 8080);

//缓存查询的节假日数据
$cache = [];

$server->on('request', function ($request, $response) {
    $date = $request->get['date'] ?? '';
    if (empty($date)) {
        $response->end(json_encode([
            'status' => -1,
            'error'  => 'date cannot be empty'
        ]));
        return;
    }
    $date = new DateApi($date);
    $response->end(json_encode($date->isHoliday()));
});
$server->start();
