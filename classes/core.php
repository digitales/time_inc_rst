<?php
/**
 * Core
 *
 * A set of core functions for the plugin
 *
 * @author Ross Tweedie
 * @package Timeinc_rst
 * @since 1.0
 * @version 1.0.0
 */

// For composer dependencies.
require_once TIRSTPATH.'/vendor/autoload.php';

// We need to make sure that we are using the Guzzle HTTP client (included with composer).
use GuzzleHttp\Client;


class TimeIncRstCore{

	/**
	 * Construct
	 */
	function __construct() {
		$this->init();
	}


	/**
	 * Initialize the plugin
	 */
	function init() {
		add_action( 'init', array( __CLASS__, 'add_widgets' ) );
	}


	/**
	 * Add the widget to WordPress
	 */
	static function add_widgets() {

		register_widget( 'Timeinc_RST_Widget' );

		do_action( 'widgets_init' );
	}


	/**
	 * Check if a given URL is valid
	 *
	 * @param string $url the URL to load.
	 *
	 * @return boolean
	 */
	static function is_valid_url( $url ) {

		$client = new Client();

		try {
			$client->head( $url );
			return true;
		} catch (GuzzleHttp\Exception\ClientException $e) {
			return false;
		}

	}


	/**
	 * Get a given WordPress JSON URL
	 *
	 * @param string $url the URL to load.
	 *
	 * @return array || false return the results or nothing.
	 */
	static function get_url( string $url ) {

		$is_valid_url = self::is_valid_url( $url );

		if ( ! $is_valid_url ) {
			return false;
		}

		$client = new Client();

		$request = $client->get( $url );
		$records = json_decode( $request->getBody(), true );

		if ( is_array( $records ) && 0 < count( $records ) ) {
			return $records;
		}

		return false;
	}

	/**
	 * Helper function to prepare the view data
	 *
	 * @param array $view_data The data for the view.
	 *
	 * @return array
	 */
	static function clean_view_data( $view_data ) {

		if ( is_array( $view_data ) ) {

			foreach (  $view_data as $key => $value ) {
				if ( false !== strpos( $key, '-' )  ) {

					$new_key = str_replace( '-', '_', $key );

					$view_data[ $new_key ] = $value;

					// Remove the old key associated with the data.
					unset( $view_data[ $key ] );
				}
			}
		}

		return $view_data;
	}

	/**
	 * Check if the view file can be found on the file system
	 *
	 * @param string  $view_file The name of the view file.
	 * @param null || string  $path The path of the view files.
	 * @param null || sgtring $extension the file extension to load.
	 *
	 * @return boolean
	 */
	static function is_valid_view_file( string $view_file, $path = 'views/twig', $extension = 'twig' ) {

		$filepath = sprintf( TIRSTPATH.'%s/%s.%s', $path, $view_file, $extension );

		if ( file_exists( $filepath ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Include a view template
	 *
	 * @param string $view the view to load.
	 * @param array  $data_for_view the data to pass to the view.
	 *
	 * @return void
	 */
	static function include_view( string $view, $data_for_view = null ) {

		$data_for_view = self::clean_view_data( $data_for_view );

		if ( $data_for_view ) {
			extract( $data_for_view ); // Using extract to make  it easier to reuse this function.
		}

		$is_valid_view_file = self::is_valid_view_file( $view, 'views', 'php' );

		if ( $is_valid_view_file ) {
			include( TIRSTPATH . 'views/'.$view.'.php' );
		} else {
			include( TIRSTPATH . 'views/index.php' );
		}
	}

	/**
	 * Include a twig view template
	 *
	 * @param string  $view the view file.
	 * @param array   $data_for_view the data to be passed to the view.
	 * @param boolean $render should we render the template.
	 *
	 * @return null || string
	 */
	static function include_twig_view( string $view, $data_for_view = null, bool $render = true ) {

		$data_for_view = self::clean_view_data( $data_for_view );

		// Load the Twig loader and library.
		$loader = new Twig_Loader_Filesystem( TIRSTPATH.'views/twig/' );

		$twig = new Twig_Environment( $loader );

		$is_valid_view_file = self::is_valid_view_file( $view );

		if ( ! $is_valid_view_file ) {
			return null;
		}

		$rendered_view = $twig->render( $view.'.twig', $data_for_view );

		if ( true == $render ) {

			echo $rendered_view;

		} else {

			return $rendered_view;

		}

	}
}
