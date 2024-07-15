document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('anz-pins-map').setView([-25.2744, 133.7751], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

});
