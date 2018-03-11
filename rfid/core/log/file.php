<?php
namespace core\log;

class file{
	public function write($message,$filename)
	{
		//以追加方式写入 而不是每一次将文件戳为零、、
		file_put_contents($filename, $message,FILE_APPEND);
	}
}


?>