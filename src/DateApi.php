<?php
/**
 * Created by .
 * Date 2019-10-16
 * Time 16:03
 * User chenlei
 */

namespace api;

class DateApi {
    public $holiday_data;
    public $year;
    public $date_str;
    public $date;

    public function __construct($date = '') {
        $this->date = empty($date) ? date('Y-m-d') : $date;
        $this->dealDate();
        $this->getSpecialData();
    }

    public function dealDate() {
        $date_arr = explode('-', $this->date);
        $this->year = $date_arr[0];
        $this->date_str = $date_arr[1] . $date_arr[2];
    }

    public function getSpecialData() {
        $path = __DIR__ . '/../data/' . $this->year . '_data.json';
        if (!file_exists($path)) {
            echo $this->year . ' data is not exist!';
            exit();
        }
        $data = file_get_contents($path);
        $json = json_decode($data, true);
        $this->holiday_data = $json;
    }

    public function isHoliday(): int {
        echo 'current deal date is ' . $this->date_str . PHP_EOL;
        $date = $this->getDate();
        echo 'date:' . $date . PHP_EOL;
        if (isset($this->holiday_data[$date])) {
            echo 'loaded holidays data' . PHP_EOL;
            return intval($this->holiday_data);
        }
        return $this->normalDay($this->getWeekDay());
    }

    public function getDate(): string {
        echo 'date1' . $this->date . PHP_EOL;
        return empty($this->date_str) ? date('md') : date('md', strtotime($this->date));
    }

    public function normalDay(string $weekday): int {
        if (in_array($weekday, ['0', '6'])) {
            return 1;
        }
        return 0;
    }

    public function getWeekDay(): string {
        echo $this->date . PHP_EOL;
        return empty($this->date_str) ? date('w') : date('w', strtotime($this->date));
    }
}

