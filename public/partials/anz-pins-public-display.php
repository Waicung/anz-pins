<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://willai.com.au
 * @since      1.0.0
 *
 * @package    Anz_Pins
 * @subpackage Anz_Pins/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="anz-pins-container" style="width:100%;">
    <div id="anz-pins-map" style="width: 100%; height: 600px;"></div>
</div>

<?php
// populate a list of coordinate from various australia districts
$au_pins = array(
    array('lat' => -33.8688, 'lng' => 151.2093),
    array('lat' => -37.8136, 'lng' => 144.9631),
    array('lat' => -31.9505, 'lng' => 115.8605),
    array('lat' => -34.9285, 'lng' => 138.6007),
    array('lat' => -27.4698, 'lng' => 153.0251),
    array('lat' => -35.2809, 'lng' => 149.1300),
    array('lat' => -12.4634, 'lng' => 130.8456),
    array('lat' => -19.2576, 'lng' => 146.8179),
    array('lat' => -34.9285, 'lng' => 138.6007),
    array('lat' => -37.8136, 'lng' => 144.9631),
    array('lat' => -33.8688, 'lng' => 151.2093),
    array('lat' => -31.9505, 'lng' => 115.8605),
    array('lat' => -27.4698, 'lng' => 153.0251),
    array('lat' => -35.2809, 'lng' => 149.1300),
    array('lat' => -12.4634, 'lng' => 130.8456),
    array('lat' => -19.2576, 'lng' => 146.8179),
    array('lat' => -34.9285, 'lng' => 138.6007),
    array('lat' => -37.8136, 'lng' => 144.9631),
    array('lat' => -33.8688, 'lng' => 151.2093),
    array('lat' => -31.9505, 'lng' => 115.8605),
    array('lat' => -27.4698, 'lng' => 153.0251),
    array('lat' => -35.2809, 'lng' => 149.1300)
);

// populate a list of coordinate from various new zealand districts
$nz_pins = array(
    array('lat' => -36.8485, 'lng' => 174.7633),
    array('lat' => -41.2865, 'lng' => 174.7762),
    array('lat' => -43.5321, 'lng' => 172.6362),
    array('lat' => -45.8742, 'lng' => 170.5036),
    array('lat' => -36.8485, 'lng' => 174.7633),
    array('lat' => -41.2865, 'lng' => 174.7762),
    array('lat' => -43.5321, 'lng' => 172.6362),
    array('lat' => -45.8742, 'lng' => 170.5036),
    array('lat' => -36.8485, 'lng' => 174.7633),
    array('lat' => -41.2865, 'lng' => 174.7762),
    array('lat' => -43.5321, 'lng' => 172.6362),
    array('lat' => -45.8742, 'lng' => 170.5036),
    array('lat' => -36.8485, 'lng' => 174.7633),
    array('lat' => -41.2865, 'lng' => 174.7762),
    array('lat' => -43.5321, 'lng' => 172.6362),
    array('lat' => -45.8742, 'lng' => 170.5036),
    array('lat' => -36.8485, 'lng' => 174.7633),
    array('lat' => -41.2865, 'lng' => 174.7762),
    array('lat' => -43.5321, 'lng' => 172.6362),
    array('lat' => -45.8742, 'lng' => 170.5036)
);


?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('anz-pins-map').setView([-28.65, 144.2], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var auPins = <?php echo json_encode($au_pins); ?>;
        var nzPins = <?php echo json_encode($nz_pins); ?>;

        // Define a custom icon
        var customIcon = L.icon({
            // load logo of the site
            iconUrl: '<?php echo Anz_Pins::get_icon_url(); ?>',
            iconSize: [32, 32], // Size of the icon
        });

        auPins.forEach(function(pin) {
            // Apply the custom icon to each marker
            L.marker([pin.lat, pin.lng], {
                icon: customIcon
            }).addTo(map);
        });

        nzPins.forEach(function(pin) {
            // Apply the custom icon to each marker
            L.marker([pin.lat, pin.lng], {
                icon: customIcon
            }).addTo(map);
        });
    });
</script>