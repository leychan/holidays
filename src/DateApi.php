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
        echo 'real date ' . $date, PHP_EOL;
        $this->date = empty($date) ? date('Y-m-d') : $date;
        $this->dealDate();
        $this->holiday_data = $this->getSpecialData();
    }

    public function dealDate() {
        $date_arr = explode('-', $this->date);
        $this->year = $date_arr[0];
        $this->date_str = $date_arr[1] . $date_arr[2];
    }

    /**
     * 根据年份日期获取节假日数据, 获取以后存放在缓存数组中
     */
    public function getSpecialData() :array {
        global $cache;
        if (empty($cache[$this->year])) {
            $path = __DIR__ . '/../data/' . $this->year . '_data.json';
            if (!file_exists($path)) {
                return [];
            }
            $data = file_get_contents($path);
            $json = json_decode($data, true);
            $cache[$this->year] = $json;
            return $json;
        }
        return $cache[$this->year];
    }

    /**
     * 判断日期是否是假日/周末, 假日返回2, 周末返回1, 工作日返回0
     *
     * @return int
     */
    public function isHoliday() :array {
        if (empty($this->holiday_data)) {
            return [
                'status' => -1,
                'error'  => '没有查询的日期数据'
            ];
        }
        echo 'current deal date is ' . $this->date_str . PHP_EOL;
        $date = $this->getDate();
        echo 'date:' . $date . PHP_EOL;
        if (isset($this->holiday_data[$date])) {
            return [
                'status'  => 0,
                'error'   => '',
                'holiday' => $this->holiday_data[$date]
            ];
        }
        $holiday = $this->normalDay($this->getWeekDay());
        return [
            'status'  => 0,
            'error'   => '',
            'holiday' => $holiday
        ];
    }

    /**
     * 获取月份和日期, 0901
     *
     * @return string
     */
    public function getDate(): string {
        return empty($this->date_str) ? date('md') : date('md', strtotime($this->date));
    }

    /**
     * 判断是否是工作日, 如果周末则返回1, 否则返回0
     *
     * @param string $weekday
     * @return int
     */
    public function normalDay(string $weekday): int {
        if (in_array($weekday, ['0', '6'])) {
            return 1;
        }
        return 0;
    }

    /**
     * 获取日期是星期几
     *
     * @return string
     */
    public function getWeekDay(): string {
        echo 'this date '. $this->date . PHP_EOL;
        return empty($this->date_str) ? date('w') : date('w', strtotime($this->date));
    }
}

