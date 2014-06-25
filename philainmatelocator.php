<?php
/* Plugin Name: Phila Inmate Locator Widget
Plugin URI: localhost/wordpress
Description: Helps find inmaates
Version: 1.0
Author: Andrew Kennel
Author URI: localhost/wordpress
*/
add_shortcode('PhilaInmateLocator', 'philainmatelocator_handler');

function philainmatelocator_handler(){
$message = <<<EOM



EOM;

return $message;
}

function philapaywidget($args, $instance) { // widget sidebar output
  extract($args, EXTR_SKIP);
  echo $before_widget; // pre-widget code from theme
  echo philainmatelocator_handler();
  echo $after_widget; // post-widget code from theme
}
?>