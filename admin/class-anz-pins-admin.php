<?php

use Shuchkin\SimpleXLSX;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://willai.com.au
 * @since      1.0.0
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/admin
 * @author     Will <will@willai.com.au>
 */
class Anz_Pins_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('admin_init', array($this, 'settings_init'));

		add_action('wp_ajax_save_anz_pins_map', array($this, 'save_anz_pins_map'));
		add_action('wp_ajax_delete_anz_pins_map', array($this, 'delete_anz_pins_map'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Anz_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Anz_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/anz-pins-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Anz_Pins_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Anz_Pins_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/anz-pins-admin.js', array('jquery'), $this->version, false);


		wp_enqueue_media();
	}

	public function add_admin_menu()
	{
		// Add a new top-level menu (settings page)
		add_options_page(
			'ANZ Pins Settings', // Page title
			'ANZ Pins Settings', // Menu title
			'manage_options', // Capability required to see this menu
			'anz-pins-settings', // Menu slug
			array($this, 'display_settings_page') // Function to display the settings page
		);

		add_options_page(
			'ANZ Pins Map', // Page title
			'ANZ Pins Map', // Menu title
			'manage_options', // Capability required to see this menu
			'anz-pins-maps', // Menu slug
			array($this, 'display_maps_editor') // Function to display the settings page
		);
	}

	public function display_settings_page()
	{
		// Check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

?>
		<div class="wrap">
			<h1>ANZ Pins Map</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields('anz_pins_options_group');
				do_settings_sections('anz-pins-settings');
				submit_button();
				?>
			</form>
		</div>
	<?php
	}

	public function settings_init()
	{
		register_setting('anz_pins_options_group', 'anz_pins_souce_option');
		register_setting('anz_pins_options_group', 'anz_pins_google_key_option');
		register_setting('anz_pins_options_group', 'anz_pins_willai_token_option');

		add_settings_section(
			'base_map_settings_section',
			'Base Map',
			array($this, 'base_map_settings_section_callback'),
			'anz-pins-settings'
		);

		add_settings_field(
			'base_map_setting_field',
			'Source',
			array($this, 'source_setting_field_callback'),
			'anz-pins-settings',
			'base_map_settings_section'
		);

		/* add_settings_field(
			'google_key_setting_field',
			'Google Map API Key',
			array($this, 'google_key_setting_field_callback'),
			'anz-pins-settings',
			'base_map_settings_section'
		); */

		add_settings_section(
			'location_service_settings_section',
			'Location Service',
			array($this, 'location_service_settings_section_callback'),
			'anz-pins-settings'
		);

		add_settings_field(
			'location_token_setting_field',
			'WillAI Token',
			array($this, 'location_token_setting_field_callback'),
			'anz-pins-settings',
			'location_service_settings_section'
		);
	}


	public function base_map_settings_section_callback()
	{
		echo '<p>Source of the Base Map.</p>';
	}

	public function source_setting_field_callback()
	{
		$option = get_option('anz_pins_souce_option');

		$choices = array('openstreet' => ' OpenStreet Map'/* , 'google' => 'Google Map' */);
		foreach ($choices as $value => $label) {
			echo '<label><input type="radio" name="anz_pins_souce_option" value="' . esc_attr($value) . '" ' . checked($value, $option, false) . '> ' . esc_html($label) . '</label><br>';
		}
	}

	public function google_key_setting_field_callback()
	{
		$option = get_option('anz_pins_google_key_option');
		echo '<input type="text" name="anz_pins_google_key_option" value="' . esc_attr($option) . '">';
	}

	public function location_service_settings_section_callback()
	{
		echo '<p>Location Service Settings.</p>';
	}

	public function location_token_setting_field_callback()
	{
		$option = get_option('anz_pins_willai_token_option');
		echo '<input type="text" name="anz_pins_willai_token_option" value="' . esc_attr($option) . '">';
	}

	/* Map editor */
	public function display_maps_editor()
	{
		$items = get_option('anz_pins_maps', array());
	?>
		<div class="wrap">
			<h1>ANZ Pins Map</h1>
			<p>
				<button id="add-new-item" class="button" type="button">Add New Map</button>
			</p>

			<table class="wp-list-table widefat fixed striped centered responsive-table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Name</th>
						<th>Shortcode</th>
						<th>Postcodes</th>
						<th>Icon</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($items as $id => $item) : ?>
						<tr>
							<td><?php echo esc_html($id); ?></td>
							<td><?php echo esc_html($item['name']); ?></td>
							<td>
								<input type="text" readonly value="[anz_pins map='<?php echo esc_attr($id); ?>']" class="shortcode-field">
								<p>
									<button class="button copy-shortcode" data-shortcode="[anz_pins map='<?php echo esc_attr($id); ?>']">Copy</button>
								</p>

							</td>
							<td>
								<?php if (isset($item['country_postcodes'])) : ?>
									<?php
									$jsonData = json_encode($item['country_postcodes']);
									$escapedData = htmlspecialchars($jsonData, ENT_QUOTES, 'UTF-8');
									?>
									<span class="pin_counter"><?php echo count($item['country_postcodes']); ?></span>
									<input class="country_postcodes" type="hidden" value="<?php echo $escapedData; ?>" />
								<?php else : ?>
									<span class="pin_counter">0</span>
									<input class="country_postcodes" type="hidden" value="" />
								<?php endif; ?>
							</td>
							<td>
								<?php $customIcon = $item['pin_icon'] ?? Anz_Pins::get_default_icon_url(); ?>
								<img src="<?php echo esc_url($customIcon); ?>" style="width: 32px; height: 32px;">
							</td>
							<td>
								<button type="button" class="button edit-item" data-id="<?php echo esc_attr($id); ?>">Edit</button>
								<button type="button" class="button delete-item" data-id="<?php echo esc_attr($id); ?>">Delete</button>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php
		$this->add_edit_modal(); // Function to add the modal for editing items
	}

	public function add_edit_modal()
	{
	?>
		<div id="edit-modal" style="display:none;">
			<div class="modal-content">
				<form id="edit-item-form">
					<input type="hidden" name="action" value="save_anz_pins_map">
					<input type="hidden" name="item_id" id="item_id" value="">
					<p>
						<label for="item_name">Name</label>
						<input type="text" name="item_name" id="item_name" value="">
					</p>
					<p>
						<label for="item_file">Upload Excel File</label>
						<input type="file" name="item_file" id="item_file">
					</p>
					<!-- a new element for icon selector from media -->
					<p>
						<label for="pin_icon">Custom Icon</label>
						<input type="text" name="pin_icon" id="pin_icon" value="">
						<button type="button" id="select-pin-icon" class="button">Select Icon</button>
					</p>
					<!-- table view to display country_codes -->
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th>Country</th>
								<th>Postcode</th>
								<th>Latitude</th>
								<th>Longitude</th>
							</tr>
						</thead>
						<tbody id="country-postcodes">
						</tbody>
					</table>
					<p>
						<button type="submit" class="button button-primary">Save</button>
						<button type="button" id="close-modal" class="button">Cancel</button>
					</p>
				</form>
			</div>
			<div id="map-preview" style="display:none;">
				<div id="anz-pins-map" style="width: 100%; height: 600px;"></div>
			</div>
			<style>
				#edit-modal {
					position: fixed;
					top: 50%;
					left: 50%;
					transform: translate(-50%, -50%);
					background: #fff;
					padding: 20px;
					box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
					z-index: 1000;
				}

				.modal-content {
					display: flex;
					flex-direction: column;
				}

				.shortcode-field {
					margin-right: 5px;
				}

				/* Make the table itself scrollable horizontally */
				@media screen and (max-width: 768px) {
					table.responsive-table {
						width: 100%;
						overflow-x: auto;
						display: block;
					}

					table.responsive-table thead {
						display: none;
					}

					table.responsive-table,
					table.responsive-table tbody,
					table.responsive-table th,
					table.responsive-table td,
					table.responsive-table tr {
						display: block;
					}

					table.responsive-table tr {
						margin-bottom: 10px;
					}

					table.responsive-table td {
						text-align: center;
						position: relative;

						white-space: nowrap;
					}

					table.responsive-table td:before {
						/* Now using attr() to dynamically insert the content based on the data-label attribute */
						content: attr(data-label);
						position: absolute;
						left: 0;
						width: 50%;
						padding-right: 10px;
						white-space: nowrap;
						text-align: left;
						font-weight: bold;
					}
				}
			</style>
			<script>
				jQuery(document).ready(function($) {
					/* icon selector */
					$('#select-pin-icon').click(function(e) {
						e.preventDefault();

						var image_frame;
						if (image_frame) {
							image_frame.open();
						}
						// Define image_frame as wp.media object
						image_frame = wp.media({
							title: 'Select Media',
							multiple: false,
							library: {
								type: 'image',
							}
						});

						image_frame.on('close', function() {
							// On close, get selections and save to the hidden input
							// plus other AJAX stuff to be done
							var selection = image_frame.state().get('selection');
							var gallery_ids = new Array();
							var my_index = 0;
							selection.each(function(attachment) {
								gallery_ids[my_index] = attachment['id'];
								my_index++;
							});
							var ids = gallery_ids.join(",");
							if (ids.length > 0) {
								var attachment = selection.first().toJSON();
								$('#pin_icon').val(attachment.url); // Set the value of the input to the image URL
							}
						});

						image_frame.on('open', function() {
							// On open, get the id from the hidden input
							// and select the appropiate images in the media manager
							var selection = image_frame.state().get('selection');
							var ids = $('#pin_icon').val().split(',');
							ids.forEach(function(id) {
								var attachment = wp.media.attachment(id);
								attachment.fetch();
								selection.add(attachment ? [attachment] : []);
							});

						});

						image_frame.open();
					});
					/* end icon selector */

					// Show the modal for adding a new item
					$('#add-new-item').on('click', function() {
						$('#item_id').val('');
						$('#item_name').val('');
						$('#edit-modal').show();
					});

					// Show the modal for editing an existing item
					$('.edit-item').on('click', function() {
						var itemId = $(this).data('id');
						var itemName = $(this).closest('tr').find('td:nth-child(2)').text();
						var icon = $(this).closest('tr').find('td:nth-child(5) img').attr('src');

						$('#item_id').val(itemId);
						$('#item_name').val(itemName);
						$('#pin_icon').val(icon);

						// populate the country_postcodes
						if ($(this).closest('tr').find('input.country_postcodes').val() !== '') {
							var country_postcodes = JSON.parse($(this).closest('tr').find('input.country_postcodes').val());
							var country_postcodes_html = '';
							if (country_postcodes.length > 0) {
								country_postcodes.slice(0, 10).forEach(function(country_postcode) {
									country_postcodes_html += '<tr><td>' + country_postcode.countrycode + '</td><td>' + country_postcode.postcode + '</td><td>' + country_postcode.latitude + '</td><td>' + country_postcode.longitude + '</td></tr>';
								});
							}
							// populate the country_postcodes
							$('#country-postcodes').html(country_postcodes_html);
						} else {
							$('#country-postcodes').html('');
						}
						$('#edit-modal').show();
					});

					// Handle the form submission
					$('#edit-item-form').on('submit', function(e) {
						e.preventDefault();

						var formData = new FormData(this);

						// AJAX request to save the item
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: formData,
							processData: false,
							contentType: false,
							success: function(response) {
								location.reload(); // Reload the page to show the updated list
							}
						});
					});

					// Handle the delete button click
					$('.delete-item').on('click', function() {
						if (!confirm('Are you sure you want to delete this item?')) {
							return;
						}

						var itemId = $(this).data('id');

						// AJAX request to delete the item
						$.post(ajaxurl, {
							action: 'delete_anz_pins_map',
							item_id: itemId,
						}, function(response) {
							location.reload(); // Reload the page to show the updated list
						});
					});

					// Handle the copy button click
					$('.copy-shortcode').on('click', function() {
						var shortcode = $(this).data('shortcode');
						var $temp = $('<input>');
						$('body').append($temp);
						$temp.val(shortcode).select();
						document.execCommand('copy');
						$temp.remove();
						alert('Shortcode copied to clipboard: ' + shortcode);
					});

					// Close the modal
					$('#close-modal').on('click', function() {
						$('#edit-modal').hide();
					});
				});
			</script>
	<?php
	}

	public function save_anz_pins_map()
	{
		// Check if the user has the required capability
		if (!current_user_can('manage_options')) {
			wp_die('Unauthorized user');
		}

		// Get the posted data
		$item_id = sanitize_text_field($_POST['item_id']);
		$item_name = sanitize_text_field($_POST['item_name']);
		if (empty($_POST['pin_icon'])) {
			$custom_icon = Anz_Pins::get_default_icon_url();
		} else {
			$custom_icon = sanitize_url($_POST['pin_icon']);
		}


		// Get the existing items
		$items = get_option('anz_pins_maps', array());
		$item_file = $_FILES['item_file'];

		if (empty($item_id)) {
			// Add a new item
			$item_id = uniqid();
		}

		// Update the item
		$items[$item_id]['name'] = $item_name;
		$items[$item_id]['pin_icon'] = $custom_icon;

		// Handle the file upload and parse the Excel file
		if ($item_file && !empty($item_file['tmp_name'])) {

			if ($xlsx = SimpleXLSX::parse($item_file['tmp_name'])) {
				$country_postcodes = array();
				$isFirstLine = true;
				foreach ($xlsx->rows() as $row) {
					if ($isFirstLine && $row[0] === 'country') {
						$isFirstLine = false;
						continue;
					}
					$country = sanitize_text_field($row[0]);
					$postcode = sanitize_text_field($row[1]);
					$latitude = sanitize_text_field($row[2]);
					$longitude = sanitize_text_field($row[3]);
					$country_postcodes[] = array('countrycode' => $country, 'postcode' => $postcode, 'latitude' => $latitude, 'longitude' => $longitude);
				}
				// reformat to request coordinate
				$requesting_postcodes = array_map(function ($country_postcode) {
					return array('countrycode' => $country_postcode['countrycode'], 'postcode' => $country_postcode['postcode']);
				}, $country_postcodes);
				// contruct a post request with country_postcodes as the body
				$with_coordinates = $this->request_coordinate($requesting_postcodes);
				$items[$item_id]['country_postcodes'] = $with_coordinates;
			} else {
				wp_die(SimpleXLSX::parseError());
			}
		}

		// handle icon update

		// Save the items
		update_option('anz_pins_maps', $items);

		wp_die();
	}

	public function delete_anz_pins_map()
	{
		// Check if the user has the required capability
		if (!current_user_can('manage_options')) {
			wp_die('Unauthorized user');
		}

		// Get the posted data
		$item_id = sanitize_text_field($_POST['item_id']);

		// Get the existing items
		$items = get_option('anz_pins_maps', array());

		// Remove the item
		if (isset($items[$item_id])) {
			unset($items[$item_id]);
		}

		// Save the updated items
		update_option('anz_pins_maps', $items);

		wp_die(); // This is required to terminate immediately and return a proper response
	}

	public function request_coordinate($cpostcodes)
	{
		// get existing coordinate from options
		$existing_coordinates = get_option('anz_pins_coordinates', array());
		// get the postcodes that are not in the existing coordinates
		$notlocated_postcodes = array_filter($cpostcodes, function ($cpostcode) use ($existing_coordinates) {
			return !array_key_exists($cpostcode['countrycode'] . $cpostcode['postcode'], $existing_coordinates);
		});

		if (count($notlocated_postcodes) > 0) {


			// Prepare the data
			$token = get_option('anz_pins_willai_token_option');

			// Set up the arguments
			$args = array(
				'body'        => json_encode(array_values($notlocated_postcodes)),
				'timeout'     => '45',
				'redirection' => '5',
				'httpversion' => '2.0',
				'blocking'    => true,
				'headers'     => array(
					'Authorization' => "Bearer $token",
					'Content-Type'  => 'application/json',
				),
				'cookies'     => array(),
			);

			// Make the POST request
			$response = wp_remote_post('https://vercelapi.willai.com.au/getgeos', $args);

			// Handle the response
			if (is_wp_error($response)) {
				$error_message = $response->get_error_message();
				echo "Something went wrong: $error_message";
			} else {
				$response = wp_remote_retrieve_body($response);
			}

			// combine the existing coordinates with the new coordinates
			$coordinates = array_merge($existing_coordinates, $this->coordinate_formater(json_decode($response, true)));
			// save the new coordinates
			update_option('anz_pins_coordinates', $coordinates);
			// get coordinates for postcodes
			$with_coordinates = array_map(function ($cpostcode) use ($coordinates) {
				$coordinate = $coordinates[$cpostcode['countrycode'] . $cpostcode['postcode']];
				return array('countrycode' => $cpostcode['countrycode'], 'postcode' => $cpostcode['postcode'], 'latitude' => $coordinate['latitude'], 'longitude' => $coordinate['longitude']);
			}, $cpostcodes);
		} else {
			$with_coordinates = array_map(function ($cpostcode) use ($existing_coordinates) {
				$coordinate = $existing_coordinates[$cpostcode['countrycode'] . $cpostcode['postcode']];
				return array('countrycode' => $cpostcode['countrycode'], 'postcode' => $cpostcode['postcode'], 'latitude' => $coordinate['latitude'], 'longitude' => $coordinate['longitude']);
			}, $cpostcodes);
		}
		return $with_coordinates;
	}

	public function coordinate_formater($coordinates)
	{
		$formated_coordinates = array();
		foreach ($coordinates as $coordinate) {
			$formated_coordinates[$coordinate['countrycode'] . $coordinate['postcode']] = array('latitude' => $coordinate['latitude'], 'longitude' => $coordinate['longitude']);
		}
		return $formated_coordinates;
	}
}
