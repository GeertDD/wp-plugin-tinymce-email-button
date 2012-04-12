<?php
/*
Plugin Name: TinyMCE Email Button
Plugin URI: https://github.com/GeertDD/tinymce-email-button
Description: Adds a button for creating email links to the TinyMCE editor
Version: 1.0
Author: Geert De Deckere
Author URI: http://www.geertdedeckere.be/
*/

new TinyMCE_Email_Button;

class TinyMCE_Email_Button {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		add_action('init', array($this, 'init'));
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init()
	{
		// This plugin only applies to Wysiwyg areas in the admin
		if ( ! is_admin() || get_user_option('rich_editing') !== 'true')
			return;

		// Setup internationalisation first
		$this->i18n();

		// Note: the TinyMCE Advanced plugin also uses these hooks and uses priority 999.
		// We need a higher priority in order for the button to be added succesfully.
		// See: http://wordpress.org/extend/plugins/tinymce-advanced/
		add_filter('mce_buttons', array($this, 'mce_buttons'), 1000);
		add_filter('mce_external_plugins', array($this, 'mce_external_plugins'), 1000);
	}

	/**
	 * Internationalization setup
	 *
	 * @return void
	 */
	public function i18n()
	{
		// Load language files
		// See: http://www.geertdedeckere.be/article/loading-wordpress-language-files-the-right-way
		$domain = 'tinymce-email-button';
		$locale = apply_filters('plugin_locale', get_locale(), $domain);
		load_textdomain($domain, WP_LANG_DIR.'/tinymce-email-button/'.$domain.'-'.$locale.'.mo');
		load_plugin_textdomain($domain, FALSE, dirname(plugin_basename(__FILE__)).'/languages/');

		// Localization for the tinymce-email-button.js file
		wp_localize_script('editor', 'tinymce_email_button', array(
			'url'    => plugin_dir_url(__FILE__),
			'title'  => __('Insert e-mail link', 'tinymce-email-button'),
			'prompt' => __('Enter the email address to link to:', 'tinymce-email-button'),
		));
	}

	/**
	 * Add a mailto button to the first row of the TinyMCE editor
	 *
	 * @param array $buttons ordered button list
	 * @return array $buttons
	 */
	public function mce_buttons($buttons)
	{
		// Look for the regular link button
		if (FALSE === ($key = array_search('link', $buttons)))
		{
			// Append the button to the end of the row if no link button was found
			$buttons[] = 'email';
		}
		else
		{
			// Insert the email button before the link button
			$before = array_slice($buttons, 0, $key);
			$after = array_slice($buttons, $key);
			$buttons = array_merge($before, array('email'), $after);
		}

		return $buttons;
	}

	/**
	 * Register the JavaScript file for the mailto button
	 *
	 * @param array $plugins TinyMCE plugin files
	 * @return array $plugins
	 */
	public function mce_external_plugins($plugins)
	{
		$plugins['email'] = plugin_dir_url(__FILE__).'javascript/tinymce-email-button.js';
		return $plugins;
	}

}