<?php

// Installer script. 
$module = 'storelocator';
$mpath = __DIR__.'/'.$module;

$this->copy($mpath.'/includes/storelocator.php',$this->theme_path.'/includes/storelocator.php');
$this->append($mpath.'/functions.php',$this->theme_path.'/functions.php');

$this->flush_rewrite_rules();