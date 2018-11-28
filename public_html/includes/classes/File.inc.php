<?php

    /**
     * 一个文件类，不过还没考虑好路径的处理，暂时不能用
     * Created by PhpStorm.
     * User: zn
     * Date: 2018/11/17
     * Time: 22:22
     */
    class File
    {
        private $_charset = 'UTF-8';
        private $_sender = array();//
        private $_recipients = array();//收件人
        private $_subject = '';//主题
        private $_multiparts = array();//附件内容
        private $filePath = "";
        public function __construct($file=null) {
            $this->filePath = $file;
            return $this;
        }

        /**
         * 返回子文件
         * @return array()
         */
        public function listFiles() {

        }

        public function getName(){

        }
        public function getAbsoluteFilePath() {

        }
        public function getPath() {

        }
        public function isDirectory() {

        }
        public function isFile() {

        }
        public function getParentFile() {

        }
        public function getParent() {

        }

        public function mkdirs() {

        }

    }