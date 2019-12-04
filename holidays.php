<?php
/**
 * Created by .
 * Date 2019-10-16
 * Time 16:03
 * User chenlei
 */

require "vendor/autoload.php";

use api\DateApi as DateApi;

$server = new Swoole\Http\Server('0.0.0.0', 8080);

$server->on('request', function($request, $response) {
    $date = isset($request->get['date']) ? $request->get['date'] : '';
    $date = new DateApi($date);
    $response->end($date->isHoliday());
});
$server->start();
