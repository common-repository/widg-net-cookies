<?php
/*
Plugin Name: WIDG.net - Уведомление о cookies
Plugin URI: https://widg.net/ru/widgets/1020/
Description: Плагин выводит уведомление с предложением предоставить согласие на обработку файлов cookie
Version: 1.0
Author: "WIDG.net"
Author URI: https://widg.net
*/

require('admin_panel.php');

class WidgNetClass1020
{
	var $admin;
	var $options;
	var $options_default = array(
		'script' => '',
		'position' => 'wp_footer',
		'mode' => 'all',
	);

	function __construct()
	{
		add_action('init', array($this, 'initial'));
	}

	public static function basename()
	{
		return plugin_basename(__FILE__);
	}

	public function initial()
	{
		$this->options = array_merge(
			$this->options_default,
			(array) get_option('widgnet_1020', array())
		);
		if (defined('ABSPATH') && is_admin()) $this->admin = new WidgNet1020();
		add_action('wp_head', array($this, 'add_in_head'), 5);
	}

	// Подготавливаем код для вывода в панели администратора
	function add_in_head()
	{
		if (!empty($this->options['script'])){
			$str = explode('"', $this->options['script']);
			echo '<script src="'.esc_attr( $str[1] ).'" defer></script>'; 
		}
	}
}

$widgnet_1020 = new WidgNetClass1020();
