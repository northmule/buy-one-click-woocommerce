<?php

namespace Coderun\BuyOneClick;

if (!defined('ABSPATH')) {
    exit;
}

class LoadFile {

    protected static $_instance = null;
    protected $folder = array();

    /**
     * Информация о файле
     */
    protected $files = array();

    /**
     * Singletone
     * @return LoadFile
     */
    public static function getInstance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Загрузка файла
     * Вернут массив message,url,error
     * @return string
     */
    public function load() {

        $result = array();

        try {

            if ($this->is_multi_form()) {
                $this->files = $this->compose_files_structure(true);
            } else {
                $this->files = $this->compose_files_structure(false);
            }


            $this->check_restriction();
        } catch (\Exception $ex) {
            $result[] = array(
                'message' => $ex->getMessage(),
                'url' => '',
                'error' => true
            );

            return $result;
        }

        $path = $this->get_load_folder_path()['path'] . '/';

        foreach ($this->files as $num_file => $file) {
            $img = $file['name'];
            $tmp = $file['tmp_name'];
            $new_name = strtolower($this->get_new_name($img));
            $path = $path . strtolower($new_name);
            if (move_uploaded_file($tmp, $path)) {
                $result[$num_file] = array(
                    'message' => __('File downloaded', 'coderun-oneclickwoo'),
                    'url' => $this->folder['url'] . '/' . $new_name,
                   // 'path' => $path,
                    'error' => false
                );
            }
        }

        return $result;
    }

    /**
     * Пересоборка файла/файлов в одну структуру
     * @param type $multi
     * @return type
     */
    protected function compose_files_structure($multi = false) {

        $result = array();

        $file_list = $_FILES['files'];

        if ($multi) {
            foreach ($file_list['name'] as $key_file => $value) {
                $result[$key_file]['name'] = $value;
                $result[$key_file]['type'] = $file_list['type'][$key_file];
                $result[$key_file]['tmp_name'] = $file_list['tmp_name'][$key_file];
                $result[$key_file]['error'] = $file_list['error'][$key_file];
                $result[$key_file]['size'] = $file_list['size'][$key_file];
            }
        } else {
            $key_file = 0;
            $result[$key_file]['name'] = $file_list['name'];
            $result[$key_file]['type'] = $file_list['type'];
            $result[$key_file]['tmp_name'] = $file_list['tmp_name'];
            $result[$key_file]['error'] = $file_list['error'];
            $result[$key_file]['size'] = $file_list['size'];
        }

        return $result;
    }

    /**
     * Вернёт true -если используется multinput
     * @return type
     */
    protected function is_multi_form() {
        return (isset($_FILES['files']['name'][0]) ? true : false);
    }

    /**
     * Проверка файлов на ограничение
     * @throws Exception
     */
    protected function check_restriction() {

        if (empty($this->files) || empty($this->files[0]['name'])) {
            throw new \Exception(__('No file to download', 'coderun-oneclickwoo'), 200);
        }

        foreach ($this->files as $file) {

            if (empty($file['tmp_name']) || empty($file['name'])) {
                throw new \Exception(__('No file to download', 'coderun-oneclickwoo'), 200);
            }

            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $this->get_valid_extension())) {
                throw new \Exception(__('Invalid file extension', 'coderun-oneclickwoo'), 200);
            }

            if ($file['size'] > $this->get_valid_size()) {
                throw new \Exception(__('Invalid file size', 'coderun-oneclickwoo'), 200);
            }

            if (!in_array($file['type'], $this->get_valid_mime_types())) {
                throw new \Exception(__('Invalid file type', 'coderun-oneclickwoo'), 200);
            }
        }
    }

    protected function get_load_folder_path() {
        $folder = $this->folder;
        $result = array('path' => $folder['path'], 'url' => $folder['url']);
        return apply_filters('coderun_oneclickwoo_file_load_folder_path', $result);
    }

    protected function get_new_name($name) {
        $salt = apply_filters('coderun_oneclickwoo_file_salt_name', ('buy_file_' . rand(100, 10000)));

        return $salt . '_' . $name;
    }

    protected function get_valid_extension() {
        $result = array('jpeg', 'jpg', 'png', 'gif', 'bmp', 'pdf', 'doc', 'ppt');

        return apply_filters('coderun_oneclickwoo_file_valid_extension', $result);
    }

    protected function get_valid_mime_types() {
        $result = array(
            'image/gif',
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/tiff',
            'image/vnd.wap.wbmp',
            'image/webp',
            'ppt',
            'text/csv',
            'text/plain',
            'text/xml',
            'application/vnd.oasis.opendocument.text',
            'application/vnd.oasis.opendocument.spreadsheet',
            'application/vnd.oasis.opendocument.presentation',
            'application/vnd.oasis.opendocument.graphics',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/x-rar-compressed',
            'application/x-tar',
            'application/pdf',
            'application/xml-dtd',
            'application/zip',
            'application/gzip',
            'application/xml',
            'application/msword',
        );

        return apply_filters('coderun_oneclickwoo_file_valid_mime_types', $result);
    }

    protected function get_valid_size() {
        $result = 10485760; //10Mb

        return apply_filters('coderun_oneclickwoo_file_valid_size', $result);
    }

    protected function __construct() {
        $this->folder = wp_upload_dir();
    }

    public function __clone() {
        throw new \Exception('Forbiden instance __clone');
    }

    public function __wakeup() {
        throw new \Exception('Forbiden instance __wakeup');
    }

}
