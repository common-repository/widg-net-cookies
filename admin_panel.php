<?php

class WidgNet1020
{
	function __construct()
	{
		// Добавляем страницу настроек в панель администратора
		add_action('admin_menu', array(&$this, 'admin_menu'));

		// Добавляем в описание плагина ссылку на настройку и формируем поля ввода кода.
		add_filter('plugin_row_meta', 'WidgNet1020::plugin_row_meta', 10, 2);
		add_action('admin_init', array(&$this, 'plugin_settings'));
	}

	function admin_menu()
	{
		// Добавляем в меню "Настройки" страницу настроек плагина
		add_options_page(
			'Настройка скрипта "WIDG.net - Уведомление о cookies"',
			'WIDG.net - Уведомление о cookies',
			'manage_options',
			'widgnet_1020_setting.php',
			array(&$this, 'options_page_output')
		);
	}

	// Добавление ссылок к описанию плагина
	public static function plugin_row_meta($meta, $file)
	{
		if ($file == WidgNetClass1020::basename()) {
			// Ссылка на страницу справки
			$meta[] = '<a href="options-general.php?page=widgnet_1020_setting.php">Настройки</a>';
		}
		return $meta;
	}

	/**
	 * Создаем страницу настроек плагина
	 */

	function options_page_output()
	{
?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>

			<form action="options.php" method="POST">
				<?php
				settings_fields('widgnet_1020_group');     // скрытые защитные поля
				do_settings_sections('widgnet_1020_page'); // секции с настройками (опциями).
				submit_button();
				?>
			</form>
		</div>
<?php
	}

	/**
	 * Регистрируем настройки.
	 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
	 */
	function plugin_settings()
	{

		// параметры: $option_group, $option_name, $sanitize_callback
		register_setting('widgnet_1020_group', 'widgnet_1020', array(&$this, 'sanitize_callback'));

		// параметры: $id, $title, $callback, $page
		add_settings_section('widgnet_1020', '', array(&$this, 'display_setting_info'), 'widgnet_1020_page');

		$field_params = array(
			'type'      => 'textarea',
			'id'        => 'script',
			'label_for' => 'script'
		);
		add_settings_field('script', 'Уникальный код виджета:', array(&$this, 'display_settings'), 'widgnet_1020_page', 'widgnet_1020', $field_params);
	}

	// Поясняющее сообщение для секции тестирования и отладки
	function display_setting_info()
	{
		echo '<p>Для работы плагина вам необходимо получить на <a href="https://widg.net"  target="_blank">сайте "WIDG.net"</a> уникальный код виджета, и вставить его ниже.</p>';
	}

	/*
	 * Функция отображения полей ввода
	 * Здесь задаётся HTML и PHP, выводящий поля
	 */
	function display_settings($args)
	{
		extract($args);

		$option_name = 'widgnet_1020';

		$o = get_option($option_name);

		switch ($type) {
			case 'text':
				echo "<input class='regular-text' type='text' id='" . esc_attr( $id ) . "' name='" . esc_attr( $option_name ) . "[" . esc_attr( $id ) . "]' value='" . esc_attr(stripslashes($o[$id])) . "' />";
				echo (isset($args['desc'])) ? '<br /><span class="description">' . esc_attr( $args['desc'] ) . '</span>' : "";
				break;
			case 'textarea':
				echo "<textarea class='code large-text' cols='30' rows='10' type='text' id='" . esc_attr( $id ) . "' name='" . esc_attr( $option_name ) . "[" . esc_attr( $id ) . "]'>" . esc_attr(stripslashes($o[$id])) . "</textarea>";
				echo (isset($args['desc'])) ? '<br /><span class="description">' . esc_attr( $args['desc'] ) . '</span>' : "";
				break;
		}
	}

	## Очистка данных
	function sanitize_callback($options)
	{
		// очищаем
		foreach ($options as $name => &$val) {
			if ($name == 'input')
				$val = strip_tags($val);

			if ($name == 'checkbox')
				$val = intval($val);
		}

		//die(print_r( $options ));

		return $options;
	}
}
?>