<?php
define('PATH', __DIR__ .'/');
require_once "std/Modification.php";
require Modification::getFile($_GET['_path']);