<?php
/**
 * Created by .
 * Date 2019-10-16
 * Time 16:03
 * User chenlei
 */

require "vendor/autoload.php";

use api\DateApi as DateApi;

$date_str = '2019-08-01';

$date = new DateApi($date_str);

echo $date->isHoliday();
