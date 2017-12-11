<?php
require_once (__DIR__."/phpKb/Dom.php");
require_once (__DIR__."/phpKb/Kb.php");
require_once (__DIR__."/phpKb/Curl.php");

class kblibs_register_class_check {
	public static $smart = array();
	public static $class = array();
}
function _replace_dup_s($text) {
	return str_replace("\\","/",str_replace("//","/",$text));

}

spl_autoload_register(function ($entity) {
	$md5 = md5($entity);
    $module = explode('\\',$entity,2);
    if(isset(kblibs_register_class_check::$class[$md5])) {
    	return ;
    }
    if(count($module) > 1) {
        $file = _replace_dup_s(str_replace("\\","/", __DIR__ . '/' .$entity.'.php'));
        $file1 = defined("ABSPATH") ? _replace_dup_s(ABSPATH."/wp-content/plugins/".$entity.'.php') : "emptyFile";

        if(is_readable($file)) {
	        kblibs_register_class_check::$class[$md5] = $file;
            require_once ($file);
        }
        else if(defined("ABSPATH") && is_readable($file1)) {
	        kblibs_register_class_check::$class[$md5] = $file1;
        	require_once ($file1);
        }
        else if(defined("ABSPATH")) {
        	$path = ABSPATH."/wp-content/plugins/";
        	foreach(kblibs_register_class_check::$smart as $file) {
		        $filename = _replace_dup_s($path."/".$file."/" .$entity.'.php');
		        if(is_readable($filename)) {
			        kblibs_register_class_check::$class[$md5] = $filename;
			        require_once($filename);
			        return ;
		        }
	        }
	        $dir = opendir($path);
	        while($file = readdir($dir)) {
	        	if(is_dir($path."/".$file) && $file!="." && $file!="..") {
	        		$filename = _replace_dup_s($path."/".$file."/" .$entity.'.php');
	        		if(is_readable($filename)) {
				        kblibs_register_class_check::$smart[] = $file;
				        kblibs_register_class_check::$class[$md5] = $filename;
	        			require_once($filename);
	        			return ;
			        }
		        }
	        }
        }
    }
});



# require_once (__DIR__.'/../vendor/autoload.php');