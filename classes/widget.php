<?php
/**
 * Name: Time Inc RST Widget
 *
 * A plugin to fulfill the coding test for TIme Inc - create a widget to load results from a JSON API and display via a widget
 *
 * @author:          Ross Tweedie https://github.com/digitales
 * @version:         1.0.0
 * @since:			1.0.0
 * @package         Timeinc_rst
 */
class Timeinc_RST_Widget extends WP_Widget {

	/**
	The transient name of the widget.
	 *
	@var $transient_name
	*/
	var $transient_name = 'timincrst_widget';

	/**
	 * The main construct
	 */
	function __construct() {
		$widget_ops = array(
			'description' => __( 'Display data from arbitary JSON API endpoint.', 'timeinc_rst' ),
		);
		$control_ops = array( 'width' => 400, 'height' => 200 );
		parent::__construct( 'timeincrst', __( 'Time Inc RST', 'timeinc_rst' ), $widget_ops, $control_ops );

	}

	/**
	 * Outputs the contents lodaded for the isntance of the widget
	 *
	 * @param array $args The general arguments of the widget.
	 * @param array $instance The arguments for this instance of the widget.
	 */
	public function widget( $args, $instance ) {

		$transient_name = $this->transient_name.$this->number;

		if ( get_transient( $transient_name ) && 1 === (int) $instance['cache_enable'] ) {
			$rendered = get_transient( $transient_name );
			echo  $rendered;

			echo '<!-- Cached version of content -->';

			return true;
		}

		if ( isset( $instance['error'] ) && $instance['error'] ) {
			return;
		}

		$url = ( ! empty( $instance['url'] ) ) ? $instance['url'] : '' ;
		while ( stristr( $url, 'http' ) !== $url ) {
			$url = substr( $url, 1 );
		}

		// If the URL is empty then we have nothing to load so return nothing.
		if ( empty( $url ) ) {
			return;
		}

		// Now process the variables to pass to the view.
		$title = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$records = TimeIncRstCore::get_url( $url );

		$instance['before_widget'] = $args['before_widget'];
		$instance['before_title'] = $args['before_title'];
		$instance['after_title'] = $args['after_title'];
		$instance['after_widget'] = $args['after_widget'];

		$instance['title'] = $title;
		$instance['records'] = $records;

		$rendered = TimeIncRstCore::include_twig_view( 'posts_list.html', $instance, false );

		if (  1 === (int) $instance['cache_enable'] ) {
			set_transient( $transient_name, $rendered,  $instance['cache_time'] );
		}

		echo  $rendered;
	}


	/**
	 * Update and save the widget settings
	 *
	 * @param array $new_instance The new widget data instance.
	 * @param array $old_instance The old widget instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$is_updated 		= ( isset( $new_instance['url'] ) && ( ! isset( $old_instance['url'] ) || ( $new_instance['url'] !== $old_instance['url'] ) ) ) ? 1 : 0 ;

		$url 						= esc_url_raw( $new_instance['url'] );
		$title         			= isset( $new_instance['title'] ) ? trim( strip_tags( $new_instance['title'] ) ) : '' ;

		$cache_time		= isset( $new_instance['cache_time'] ) ? (int) $new_instance['cache_time'] : 0 ;
		$cache_enable	= isset( $new_instance['cache_enable'] ) ? (int) $new_instance['cache_enable'] : 0 ;

		// Delete the transient when the widget is updated.
		if ( $is_updated ) {
			delete_transient( $this->transient_name. $this->number );
		}

		return compact( 'title', 'url', 'cache_time', 'cache_enable', 'error' );
	}


	/**
	 * Render the widget form
	 *
	 * @param array $instance the widget instance.
	 */
	public function form( $instance ) {

		// Set up the default params.
		if ( empty( $instance ) ) {
			$instance = array( 'title' => '', 'url' => '', 'cache_time' => 5, 'error' => false, 'cache_enable' => 1 );
		}

		// Pass the widget number.
		$instance['number'] = esc_attr( $this->number );

		// Now include the the form view.
		TimeIncRstCore::include_view( 'widget-form', $instance );
	}
}
