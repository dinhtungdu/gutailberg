<?php
/**
 * custom option and settings
 */
function gutailberg_settings_init() {
	// Register a new setting for "gutailberg" page.
	register_setting(
		'gutailberg',
		'gutailberg_options',
		array(
			'sanitize_callback' => function( $value ) {
				if ( isset( $value['gutailberg_field_tailwind_config'] ) ) {
					$value['gutailberg_field_tailwind_config'] = sanitize_textarea_field( $value['gutailberg_field_tailwind_config'] );
				}
				if ( isset( $value['gutailberg_field_tailwind_output'] ) ) {
					$value['gutailberg_field_tailwind_output'] = sanitize_textarea_field( $value['gutailberg_field_tailwind_output'] );
				}
				if ( isset( $value['gutailberg_field_tailwind_editor'] ) ) {
					$value['gutailberg_field_tailwind_editor'] = rest_sanitize_boolean( $value['gutailberg_field_tailwind_editor'] ) ? 1 : 0;
				}
				return $value;
			}
		)
	);

	// Register a new section in the "gutailberg" page.
	add_settings_section(
		'gutailberg_section_default',
		__( 'The Matrix has you.', 'gutailberg' ), 'gutailberg_section_default_callback',
		'gutailberg'
	);

	// Register a new field in the "gutailberg_section_configs" section, inside the "gutailberg" page.
	add_settings_field(
		'gutailberg_field_tailwind_config',
		__( 'Tailwind Config', 'gutailberg' ),
		'gutailberg_field_tailwind_config_cb',
		'gutailberg',
		'gutailberg_section_default',
		array(
			'label_for'         => 'gutailberg_field_tailwind_config',
			'class'             => 'gutailberg_row',
		)
	);

	add_settings_field(
		'gutailberg_field_tailwind_output',
		__( 'Tailwind CSS Output', 'gutailberg' ),
		'gutailberg_field_tailwind_output_cb',
		'gutailberg',
		'gutailberg_section_default',
		array(
			'label_for'         => 'gutailberg_field_tailwind_output',
			'class'             => 'gutailberg_row',
		)
	);

	add_settings_field(
		'gutailberg_field_tailwind_editor',
		__( 'Load Tailwind in editors', 'gutailberg' ),
		'gutailberg_field_tailwind_editor_cb',
		'gutailberg',
		'gutailberg_section_default',
		array(
			'label_for'         => 'gutailberg_field_tailwind_editor',
			'class'             => 'gutailberg_row',
		)
	);
}

/**
 * Register our gutailberg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'gutailberg_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function gutailberg_section_default_callback( $args ) {
	?>
	<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'gutailberg' ); ?></p>
	<?php
}

/**
 * Pill field callback function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function gutailberg_field_tailwind_config_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'gutailberg_options', array() );
	?>
	<textarea
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="gutailberg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			cols="80"
			rows="13"
		/><?php echo esc_html(
			$options[ $args['label_for'] ] ?? '
tailwind.config = {
	corePlugins: {
		preflight: false
	}
}'
		); ?></textarea>
	<p class="description">
	</p>
	<?php
}

function gutailberg_field_tailwind_output_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'gutailberg_options', array() );
	?>
	<textarea
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="gutailberg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
			cols="80"
			rows="13"
		/><?php echo esc_html( $options[ $args['label_for'] ] ?? '' ); ?></textarea>
	<p class="description">
		<div id="fse-templates" class="hidden"></div>
		<a href="#" id="fetch-templates" role="button" class="button button-secondary">Fetch templates</a>
		<a href="#" id="generate-css" role="button" class="button button-secondary hidden">Generate CSS</a>
		<a href="#" id="clear-css" role="button" class="button button-secondary">Clear CSS</a>
	</p>
	<?php
}

function gutailberg_field_tailwind_editor_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
	$options = get_option( 'gutailberg_options', array() );
	?>
	<input
		type="checkbox"
		id="<?php echo esc_attr( $args['label_for'] ); ?>"
		name="gutailberg_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
		value="1"
		<?php checked( 1, $options[ $args['label_for'] ] ?? 0 ); ?>
	/>
	<p class="description">
		<?php echo esc_html__( 'Load Tailwind in the editor. Use block list to navigate to hidden blocks.'); ?>
	</p>
	<?php
}

/**
 * Add the top level menu page.
 */
function gutailberg_options_page() {
	add_submenu_page(
		'tools.php',
		'Gutailberg',
		'Gutailberg',
		'manage_options',
		'gutailberg',
		'gutailberg_options_page_html'
	);
}


/**
 * Register our gutailberg_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'gutailberg_options_page' );


/**
 * Top level menu callback function
 */
function gutailberg_options_page_html() {
	// check user capabilities
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// add error/update messages

	// check if the user have submitted the settings
	// WordPress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'gutailberg_messages', 'gutailberg_message', __( 'Settings Saved', 'gutailberg' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'gutailberg_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			// output security fields for the registered setting "gutailberg"
			settings_fields( 'gutailberg' );
			// output setting sections and their fields
			// (sections are registered for "gutailberg", each field is registered to a specific section)
			do_settings_sections( 'gutailberg' );
			// output save settings button
			submit_button( 'Save Settings' );
			?>
		</form>
	</div>
	<?php
}
