// declare title for each marker.
var tempTitle = '';
$('#save_marker_title').click(function () {
    return tempTitle = $('#title').val();
});

// initialize the map
var my_map = L.map('map').setView([60.78635459819804, 11.511869519863401], 13);

L.tileLayer(
    'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=topo4&zoom={z}&x={x}&y={y}',
    {maxZoom: 20,}
    ).addTo(my_map);

var init_popup_title = 'This is a test position for L-marker.';

make_new_marker([60.78635459819804, 11.511869519863401], tempTitle, init_popup_title);

// work on map
my_map.on('click', onMapClick);

// function after initial one.
function onMapClick(e) {
    var popup_text = `You clicked the map at ${e.latlng.toString()}`;
    var title = tempTitle;
    make_new_marker(e.latlng, title, popup_text);
}


// function for new marker. It can be called by new or new operator.
function make_new_marker(pos, title, popup) {
    L.marker(pos, {draggable: true, title: title})
        .addTo(my_map)
        .bindPopup(popup)
        .openPopup()
        .on('dragend', function () {
            let coord = String(this.getLatLng()).split(',');
            let lat = coord[0].split('(');
            let lng = coord[1].split(')');
            this.bindPopup(`Moved to: (${lat[1]},${lng[0]})`);
            this.openPopup()
        })
        .on('click', function () {
            my_map.removeLayer(this)
        });
}

// function to get timestamp as a string
function getDate() {
    var current = new Date();
    var dateName = '';

    const year = current.getFullYear().toString();
    const month = current.getMonth().toString();
    const date = current.getDate().toString();
    const hour = current.getHours().toString();
    const min = current.getMinutes().toString();
    const sec = current.getSeconds().toString();

    dateName = year + month + date + hour + min + sec;
    console.log(typeof dateName, dateName);
    return dateName;
}

// Save markers' info to local storage.
$('#save_markers').click(function () {
    var markers_info = [];
    my_map.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
            if (my_map.getBounds().contains(layer.getLatLng())) {
                console.log(layer.getLatLng().lat, layer.getLatLng().lng, layer.options.title);
                markers_info.push({
                    lat: layer.getLatLng().lat,
                    lng: layer.getLatLng().lng,
                    title: layer.options.title
                });
            }
        }
    });

    // 'markers_info' can be used for R/W on a file in server-side.
    console.log(markers_info);
    $.ajax({
        type: 'POST',
        url: '../../save.php',
        data: {
            data: markers_info,
            timestamp: getDate()
        },
        success: function (response) {
            alert(response);
            history.go(0);
        }
    });

});


$('#uploadFile').on('click', function() {
    var file_data = $('#fileToUpload').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    $.ajax({
        url: '../../upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(response){
            var show_data = JSON.parse(response);
            console.log(show_data, typeof show_data);
            my_map.eachLayer(layer => {
                console.log(layer);
                my_map.removeLayer(layer);
            });
            L.tileLayer(
                'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=topo4&zoom={z}&x={x}&y={y}',
                {maxZoom: 20,}
            ).addTo(my_map);

            show_data.map(item => {
                console.log([item[0], item[1]]);
                make_new_marker(
                    [parseFloat(item[0]), parseFloat(item[1])],
                    item[2],
                    `You are the map at LatLng(${item[0]}, $${item[1]})`
                );
            })
        }
    });
});
