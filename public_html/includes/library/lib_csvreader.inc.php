<?php

    /**
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/10/23
     * Time: 9:07
     */
    class csvreader {
        private $csv_file;
        private $spl_object = null; //SplFileObject
        private $error;

        public function __construct($csv_file = '') {
            if($csv_file && file_exists($csv_file)) {
                $this->csv_file = $csv_file;
            }
        }

        /**
         * Setting csvFile path
         * @param $csv_file Csv file path
         * @return bool
         */
        public function set_csv_file($csv_file) {
            if(!$csv_file || !file_exists($csv_file)) {
                $this->error = 'File invalid';
                return false;
            }
            $this->csv_file = $csv_file;
            $this->spl_object = null;
        }

        public function get_csv_file() {
            return $this->csv_file;
        }

        private function _file_valid($file = '') {
            $file = $file ? $file : $this->csv_file;
            if(!$file || !file_exists($file)) {
                return false;
            }
            if(!is_readable($file)) {
                return false;
            }
            return true;
        }

        /**
         * @return bool
         */
        private function _open_file() {
            if(!$this->_file_valid()) {
                $this->error = 'File invalid';
                return false;
            }
            if($this->spl_object == null) {
                $this->spl_object = new SplFileObject($this->csv_file, 'rb');
            }
            return true;
        }

        /**
         * 获取csv的数据
         * @param int $length 需要获取多少条
         * @param int $start 开始条数
         * @return array|bool
         */
        public function get_data($length = 0, $start = 0)
        {
            set_time_limit(0);//设置脚本执行时间
            if (!$this->_open_file()) {
                return false;
            }
            $length = $length ? $length : $this->get_lines();
            $start = $start - 1;// 这个必须有，这是php的一个bug，否则第一个数据会跳行
            $start = ($start < 0) ? 0 : $start;
            $data = array();
            $this->spl_object->seek($start);
            while ($length-- && !$this->spl_object->eof()) {
                $data[] = $this->spl_object->fgetcsv();// fgetcsv： Gets line from file and parse as CSV fields
            }
            if (!empty($data) && is_array($data)) {
                if (count($data) > 2) {
                    if (count($data[count($data) - 1]) < count($data[count($data) - 2])) {
                        unset($data[count($data) - 1]);
                    }
                }
            }
            return $data;
        }

        /**
         * 获取csv文件总行数
         * @return int
         */
        public function get_lines() {
            if(!$this->_open_file()) {
                return false;
            }
            $length = filesize($this->csv_file);//文件大小的字节数
            $this->spl_object->seek($length);// 定位到文件末尾
            return $this->spl_object->key();// Get line number
        }

        public function get_error() {
            return $this->error;
        }
    }