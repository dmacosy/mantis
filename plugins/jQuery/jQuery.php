<?php
require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class jQueryPlugin extends MantisPlugin{
    function register () {
        $this->name = 'jQuery';
        $this->description = 'Add library jQuery';
        $this->version = '1.7.2';
        $this->requires = array(    
            'MantisCore' => '1.2.8'
        );
        $this->author = 'Kelson Kien';
        $this->contact = 'kelson.kien@eurocopter.com';
    }

    function hooks( ) {
        $hooks = array(
            'EVENT_LAYOUT_RESOURCES' => 'include_ressource',
        );

        return $hooks;
    }

    function include_ressource( ) {
        return '<script type="text/javascript" src="'.plugin_file('jQuery.js').'"></script>';
    }	

}
?>
