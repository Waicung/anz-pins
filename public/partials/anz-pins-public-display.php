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
$map_id = $atts['map'];
// get the pins for the map
$pins = Anz_Pins::get_pins_by_map($map_id);
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var map = L.map('anz-pins-map').setView([-28.65, 144.2], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


        var pins = <?php echo json_encode($pins); ?>;

        // Define a custom icon
        var customIcon = L.icon({
            // load logo of the site
            iconUrl: '<?php echo Anz_Pins::get_icon_url(); ?>',
            iconSize: [32, 32], // Size of the icon
        });

        pins.forEach(function(pin) {
            // Apply the custom icon to each marker
            L.marker([pin.latitude, pin.longitude], {
                icon: customIcon
            }).addTo(map);
        });

    });
</script>