<?php
/*
 * Title:沉沦云快捷开发框架
 * Project:Upload功能类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

use Systems\Dir;

class Upload
{
	private $name;
	private $path;
	private $max_size;
	private static $error;
	private $mime;
	private $filename;
	public static function init($name)
	{
		return new self($name);
	}
	private function __construct($name)
	{
		$this->name = $name;
		$this->max_size = 1000000;
		$this->mime = array('image/jpeg', 'image/png', 'image/gif');
		return $this;
	}
	/**
	 * Title:保存文件
	 * @return mixed 成功返回文件信息，失败返回false
	 */
	private function save()
	{
		if (!isset($_FILES[$this->name])) {
			return false;
		}
		if (!is_array($_FILES[$this->name]['name'])) {
			return $this->savefile($_FILES[$this->name], $this->filename);
		} else {
			return $this->savefiles($_FILES[$this->name], $this->filename);
		}
	}
	/**
	 * Title:上传单个文件
	 * @param $files string 文件
	 * @return mixed 成功返回文件信息，失败返回false
	 */
	private function savefile($file, $file_name)
	{
		if ($file['error'] == 0) {
			//上传类型判断
			if (!in_array(str_replace('.', '', strrchr($file['name'], '.')), $this->mime) && !in_array('*', $this->mime)) {
				self::$error = -1;
				return false;
			}
			//文件大小判断
			if ($file['size'] > $this->max_size) {
				self::$error = -2;
				return false;
			}
			//目录判断 不存在则创建
			if (!is_dir($this->path)) {
				Dir::createFile($this->path);
			}
			if (is_callable($file_name)) {
				$filename = $file_name($file) . strrchr($file['name'], '.');
			} else {
				$filename = $file_name . strrchr($file['name'], '.');
			}
			//开始移动
			if (move_uploaded_file($file['tmp_name'], $this->path . $filename)) {
				return array('file' => $this->path . $filename,'name'=>$filename, 'size' => $file['size'], 'type' => $file['type']);
			} else {
				self::$error = -3;
				return false;
			}
		} else {
			self::$error = $file['error'];
			return false;
		}
	}
	/**
	 * Title:上传多个个文件
	 * @param $files string 文件
	 * @return mixed 成功返回文件信息，失败返回false
	 */
	private function savefiles($files)
	{
		foreach ($files['name'] as $key => $value) {
			$file['name'] = $files['name'][$key];
			$file['type'] = $files['type'][$key];
			$file['tmp_name'] = $files['tmp_name'][$key];
			$file['error'] = $files['error'][$key];
			$file['size'] = $files['size'][$key];
			$filename[] = $this->savefile($file, $this->filename);
		}
		return $filename;
	}
	/**
	 * Title:保存文件的类型
	 * @param $path string 保存文件的类型
	 */
	public function type($type)
	{
		if (is_array($type)) {
			$this->mime = $type;
		} else {
			$this->mime = explode(',',$type);
		}
		return $this;
	}
	/**
	 * Title:保存文件的大小限制
	 * @param $path string 保存文件的大小
	 */
	public function size($max_size = 1000000)
	{
		$this->max_size = $max_size;
		return $this;
	}
	/**
	 * Title:保存文件的名称
	 * @param $path string 保存文件的名称
	 */
	public function name($filename)
	{
		$this->filename = $filename;
		return $this;
	}
	/**
	 * Title:保存路径
	 * @param $path string 保存文件的路径
	 * @return mixed 成功返回文件信息，失败返回false
	 */
	public function path($path)
	{
		$this->path = $path;
		return $this->save();
	}
	/**
	 * 获取错误信息,根据错误号获取相应的错误提示
	 * @access public
	 * @return string 返回错误信息
	 */
	public static function error()
	{
		switch (self::$error) {
			case -1:
				return '请检查你的文件类型，目前支持的类型有' . implode(',', $this->mime);
				break;
			case -2:
				return '文件超出系统规定的大小，最大不能超过' . $this->max_size;
				break;
			case -3:
				return '文件移动失败';
				break;
			case 1:
				return '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值,其大小为' . ini_get('upload_max_filesize');
				break;
			case 2:
				return '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值,其大小为' . @$_POST['MAX_FILE_SIZE'];
				break;
			case 3:
				return '文件只有部分被上传';
				break;
			case 4:
				return '没有文件被上传';
				break;
			case 5:
				return '非法上传';
				break;
			case 6:
				return '找不到临时文件夹';
				break;
			case 7:
				return '文件写入临时文件夹失败';
				break;
			default:
				return '未知错误';
				break;
		}
	}
}
