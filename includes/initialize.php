<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null :
	define('SITE_ROOT', DS.'Applications'.DS.'MAMP'.DS.'htdocs'.DS.'betanced');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'database.php');
include_once(LIB_PATH.DS.'loginobject.php');
include_once(LIB_PATH.DS.'announce.php');
include_once(LIB_PATH.DS.'anobject.php');
include_once(LIB_PATH.DS.'memberobject.php');
include_once(LIB_PATH.DS.'meminfo.php');
include_once(LIB_PATH.DS.'ceuinfo.php');
include_once(LIB_PATH.DS.'ceuobject.php');
include_once(LIB_PATH.DS.'memberadmin.php');
include_once(LIB_PATH.DS.'boardadmin.php');
include_once(LIB_PATH.DS.'boardmember.php');
include_once(LIB_PATH.DS.'mapobjects.php');
include_once(LIB_PATH.DS.'mobject.php');
include_once(LIB_PATH.DS.'registryobject.php');
include_once(LIB_PATH.DS.'email_object.php');
include_once(LIB_PATH.DS.'indregistry.php');
include_once(LIB_PATH.DS.'tresult_object.php');
include_once(LIB_PATH.DS.'email_contact.php');
?>
