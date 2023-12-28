<?php
/**
 * Fawaterak Settings.
 */
$wp_travel_engine_settings = get_option( 'wp_travel_engine_settings' );
$fawaterak_gateway_mode = esc_attr( $wp_travel_engine_settings['fawaterak_gateway_mode'] );
?>
<div class="wpte-field wpte-text wpte-floated">
	<label for="wp_travel_engine_settings[fawaterak_gateway]" class="wpte-field-label"><?php esc_html_e( 'API Key', 'wp-travel-engine' ); ?></label>
	<input type="text" id="wp_travel_engine_settings[fawaterak_gateway]" name="wp_travel_engine_settings[fawaterak_gateway]" value="<?php echo isset( $wp_travel_engine_settings['fawaterak_gateway'] ) ? esc_attr( $wp_travel_engine_settings['fawaterak_gateway'] ) : ''; ?>">
	<span class="wpte-tooltip"><?php esc_html_e( 'Enter a valid API Key.', 'wp-travel-engine' ); ?></span>
</div>

<div class="wpte-field wpte-text wpte-floated">
	<label for="wp_travel_engine_settings[fawaterak_gateway_mode]" class="wpte-field-label"><?php esc_html_e( 'Gateway Mode', 'wp-travel-engine' ); ?></label>
    <select name="wp_travel_engine_settings[fawaterak_gateway_mode]" id="wp_travel_engine_settings[fawaterak_gateway_mode]">
        <option value="test" <?php if ($fawaterak_gateway_mode === "test") { echo "selected"; } ?> >Test</option>
        <option value="live" <?php if ($fawaterak_gateway_mode === "live") { echo "selected"; } ?> >Live</option>
    </select>
    <span class="wpte-tooltip"><?php esc_html_e( 'Select Gateway Mode.', 'wp-travel-engine' ); ?></span>
</div>
