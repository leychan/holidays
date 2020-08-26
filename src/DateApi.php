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
    public $time_stamp;
    public $date;

    const ERROR_CODE = 0;
    const SUCCESS_CODE = 1;

    const SPECIAL_HOLIDAY = 2;
    const NORMAL_HOLIDAY = 1;
    const NOT_HOLIDAY = 0;

    public function __construct($date) {
        $this->date = $date;
    }

    /**
     * @desc 检查和格式化时间
     * @user lei
     * @throws \Exception
     */
    public function dealDate() {
        $this->time_stamp = strtotime($this->date);
        if (!$this->time_stamp) {
            throw new \Exception('date format error');
        }
        $this->year = date('Y', $this->time_stamp);
        $this->date_str = date('md', $this->time_stamp);
    }

    /**
     * @desc 根据年份日期获取节假日数据, 获取以后存放在缓存数组中
     * @user lei
     * @return array
     * @throws \Exception
     */
    public function getSpecialData() :array {
        global $cache;
        $path = __DIR__ . '/../data/' . $this->year . '_data.json';
        if (empty($cache[$this->year])) {
            if (!file_exists($path)) {
                throw new \Exception('no holiday data file found', self::ERROR_CODE);
            }
            $data = file_get_contents($path);
            $json = json_decode($data, true);
            $cache[$this->year] = $json;
            return $json;
        }
        return $cache[$this->year];
    }

    /**
     * @desc 判断日期是否是假日/周末, 假日返回2, 周末返回1, 工作日返回0
     * @user lei
     * @return array
     */
    public function isHoliday() :array {
        try {
            $this->dealDate();
            $this->holiday_data = $this->getSpecialData();
        } catch (\Exception $e) {
            return $this->asJson(self::NOT_HOLIDAY, $e->getCode(), $e->getMessage());
        }
        if (isset($this->holiday_data[$this->date_str])) {
            return $this->asJson($this->holiday_data[$this->date_str]);
        }
        $holiday = $this->normalDay($this->getWeekDay());
        return $this->asJson($holiday);
    }

    /**
     * 判断是否是工作日, 如果周末则返回1, 否则返回0
     *
     * @param string $weekday
     * @return int
     */
    public function normalDay(string $weekday): int {
        if (in_array($weekday, ['0', '6'])) {
            return self::NORMAL_HOLIDAY;
        }
        return self::NOT_HOLIDAY;
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

    public function asJson($is_holiday, $status = 1, $msg = '') {
        return [
            'holiday' => $is_holiday,
            'status'  => $status,
            'msg'     => $msg
        ];
    }
}

