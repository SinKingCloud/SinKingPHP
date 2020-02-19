<?php
/*
 * Title:沉沦云快捷开发框架
 * Project:Zip操作类
 * Author:流逝中沉沦
 * QQ：1178710004
*/

namespace Systems;

use ZipArchive;
use DirectoryIterator;
use Systems\File;

class Zip
{
    protected $zip;
    protected $root;
    protected $ignored_names;
    public function __construct()
    {
        $this->zip = new ZipArchive;
    }
    /**
     * 解压zip文件到指定文件夹
     *
     * @access public
     * @param string $zipfile 压缩文件路径
     * @param string $path  压缩包解压到的目标路径
     * @return boolean 解压成功返回 true 否则返回 false
     */
    public function unzip($zipfile, $path)
    {
        if ($this->zip->open($zipfile) === true) {
            $file_tmp = @fopen($zipfile, "rb");
            $bin = fread($file_tmp, 15); //只读15字节 各个不同文件类型，头信息不一样。
            fclose($file_tmp);
            /* 只针对zip的压缩包进行处理 */
            if (true === $this->getTypeList($bin)) {
                $result = $this->zip->extractTo($path);
                $this->zip->close();
                return $result;
            } else {
                return false;
            }
        }
        return false;
    }
    /**
     * 创建压缩文件
     *
     * @access public
     * @param string $zipfile 将要生成的压缩文件路径
     * @param string $folder 将要被压缩的文件夹路径
     * @param boolean $all 是否从当前文件夹开始压缩
     * @param array $ignored 要忽略的文件列表
     * @return boolean 压缩包生成成功返回true 否则返回 false
     */
    public function zip($zipfile, $folder,$all = true, $ignored = null)
    {
        try {
            $this->ignored_names = is_array($ignored) ? $ignored : $ignored ? array($ignored) : array();
            if ($this->zip->open($zipfile, ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            $folder = substr($folder, -1) == '/' ? substr($folder, 0, strlen($folder) - 1) : $folder;
            if (strstr($folder, '/')) {
                $this->root = substr($folder, 0, strrpos($folder, '/') + 1);
                $folder = substr($folder, strrpos($folder, '/') + 1);
            }
            $this->createZip($folder,null,$all);
            $res =  $this->zip->close();
            if (!$res) {
                unset($this->zip);
                unlink($zipfile);
            }
            return $res;
        } catch (\Throwable $th) {
            unset($this->zip);
            unlink($zipfile);
            return false;
        }
    }
    /**
     * 递归添加文件到压缩包
     *
     * @access private
     * @param string $folder 添加到压缩包的文件夹路径
     * @param string $parent 添加到压缩包的文件夹上级路径
     * @return void
     */
    private function createZip($folder, $parent = null,$all = true)
    {
        $full_path = $this->root . $parent . $folder;
        $zip_path = $parent . $folder;
        if (!$all) {
            $zip_path2 = str_replace(explode("/",$zip_path)[0],"",$zip_path);
        }else {
            $zip_path2 = $zip_path;
        }
        $this->zip->addEmptyDir($zip_path2);
        $dir = new DirectoryIterator($full_path);
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                $filename = $file->getFilename();
                if (!in_array($filename, $this->ignored_names)) {
                    if ($file->isDir()) {
                        $this->createZip($filename, $zip_path . '/',$all);
                    } else {
                        $this->zip->addFile($full_path . '/' . $filename, $zip_path2 . '/' . $filename);
                    }
                }
            }
        }
    }
    /**
     * 读取压缩包文件与目录列表
     *
     * @access public
     * @param string $zipfile 压缩包文件
     * @return array 文件与目录列表
     */
    public function fileList($zipfile)
    {
        $file_dir_list = array();
        $file_list = array();
        if ($this->zip->open($zipfile) == true) {
            for ($i = 0; $i < $this->zip->numFiles; $i++) {
                $numfiles = $this->zip->getNameIndex($i);
                if (preg_match('/\/$/i', $numfiles)) {
                    $file_dir_list[] = $numfiles;
                } else {
                    $file_list[] = $numfiles;
                }
            }
        }
        return array('files' => $file_list, 'dirs' => $file_dir_list);
    }
    /**
     * 得到文件头与文件类型映射表
     *
     * @author wengxianhu
     * @date 2013-08-10
     * @param $bin string 文件的二进制前一段字符
     * @return boolean
     */
    private function getTypeList($bin)
    {
        $array = array(
            array("504B0304", "zip")
        );
        foreach ($array as $v) {
            $blen = strlen(pack("H*", $v[0])); //得到文件头标记字节数
            $tbin = substr($bin, 0, intval($blen)); ///需要比较文件头长度
            $a = unpack("H*", $tbin);
            $a = array_shift($a);
            if (strtolower($v[0]) == strtolower($a)) {
                return true;
            }
        }
        return false;
    }
}
