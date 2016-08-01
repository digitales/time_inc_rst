



<p><label for="title-<?php echo $number; ?>"><?php _e( 'Title:', 'timeinc_rst' ); ?></label>
<input class="widefat" id="title-<?php echo $number; ?>" name="widget-timeincrst[<?php echo $number; ?>][title]" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

<p><label for="api-url-<?php echo $number; ?>"><?php _e( 'Enter the API URL to embed:', 'timeinc_rst' ); ?></label>
<input class="widefat" id="api-url-<?php echo $number; ?>" name="widget-timeincrst[<?php echo $number; ?>][url]" type="text" value="<?php echo esc_url( $url ); ?>" /></p>

<p><input id="cache-enable-<?php echo $number; ?>" name="widget-timeincrst[<?php echo $number; ?>][cache_enable]" type="checkbox" value="1" <?php checked( $cache_enable); ?> />
<label for="cache-enable-<?php echo $number; ?>"><?php _e( 'Enable the cache?', 'timeinc_rst' ); ?></label></p>

<p><label for="cache-time-<?php echo $number; ?>"><?php _e( 'The cache time (minutes):', 'timeinc_rst' ); ?></label>
<input class="widefat" id="cache-time-<?php echo $number; ?>" name="widget-timeincrst[<?php echo $number; ?>][cache_time]" type="text" value="<?php echo esc_attr( $cache_time ); ?>" /></p>
