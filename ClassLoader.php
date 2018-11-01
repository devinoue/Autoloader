<?php

/**
 * 自作オートローダ
 * require_once地獄に囚われないために、
 * 指定されたクラス名から、$DefaultPathで指定されたディレクトリの
 * サブディレクトリまでいって再帰的に検索して探してrequire_onceする。
 * 使い方は使用するrequire_once 'ClassLoader.php';
 */
class ClassLoader {
	private static $DefaultPath = "./src";
	private static $ClassName;


	/**
	 * クラス検索
	 * self::$DefaultPathで指定されたディレクトリ以下を検索する
	 * @param string $FilePath	最初だけ呼び出されたClass名が入り、以降はディレクトリ名が入る
	 * @return boolean 成功ならTrue
	 */
	public static function SearchClassFile($FilePath)
	{
        if (!is_dir($FilePath)) {
			self::$ClassName = $FilePath;
            $FilePath = self::$DefaultPath;
        }
		
		if ($handle = opendir("$FilePath")) {
			while (false !== ($Item = readdir($handle))) {
				if ($Item != "." && $Item != "..") {
					if (is_dir("$FilePath/$Item")) {
						self::SearchClassFile("$FilePath/$Item");
					} else {
						if (self::$ClassName == basename($Item, ".php" )) {
							$FileName = "{$FilePath}/{$Item}";

							if (is_file($FileName)) {
								require_once $FileName;
				
								return true;
							}
						}
					}
				}
			}
			closedir($handle);
		}
	}


}

spl_autoload_register(array('ClassLoader', 'SearchClassFile'));