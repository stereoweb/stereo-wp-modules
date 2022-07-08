<?php

// Installer script. 
$module = 'storelocator';
$mpath = __DIR__.'/'.$module;

// PHP Module (Rest, CPT, functions...)
$this->copy($mpath.'/includes/storelocator.php',$this->theme_path.'/includes/storelocator.php');
$this->copy($mpath.'/parts/storelocator.php',$this->theme_path.'/parts/storelocator.php');
// ACF Fields
$this->copy($mpath.'/acf-json/group_60facec4991cc.json',$this->theme_path.'/includes/acf-json/group_60facec4991cc.json');

$this->create_template('t-storelocator.php','Localisateur de magasins',$mpath.'/t-storelocator.php');

// Load the module!
$this->append($mpath.'/functions.php',$this->theme_path.'/functions.php');

// Install the npm modules
$this->npm_require("vue@2.6.14");
$this->npm_require("vue2-google-maps");

// Copy modular JS Module
$this->copy($mpath.'/src/js/modules/Storelocator.js',$this->src_path.'/js/modules/Storelocator.js');

// Link it to the JS
$this->append($mpath.'/src/js/modules.js',$this->theme_path.'/functions.php');

// Add Sass component
$this->copy($mpath.'/src/scss/components/_storelocator.scss',$this->src_path.'/scss/components/_storelocator.scss');

// Add it to the main file
$this->append($mpath.'/src/scss/style.scss',$this->src_path.'/scss/style.scss');


// Create the store locator page.
$pid = wp_create_post(['post_type'=>'page','post_status' => 'publish', 'post_title' => 'Localisateur de magasins', 'post_meta' => ['_wp_page_template' => 't-storelocator.php']]);

// Not required in this project. 
// $this->flush_rewrite_rules(); 