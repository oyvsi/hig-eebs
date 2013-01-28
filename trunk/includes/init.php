<?php
 include __SITE_PATH . '/application/' . 'conf/database.php';
 include __SITE_PATH . '/application/' . 'libs/baseModel.php';
 include __SITE_PATH . '/application/' . 'libs/view.php';
 include __SITE_PATH . '/application/' . 'libs/baseController.php';
 include __SITE_PATH . '/application/bootstrap.php';

 /*** auto load model classes ***/
    function __autoload($class_name) {
    $filename = strtolower($class_name) . '.class.php';
    $file = __SITE_PATH . '/model/' . $filename;

    if (file_exists($file) == false)
    {
        return false;
    }
  include ($file);
}

?>
