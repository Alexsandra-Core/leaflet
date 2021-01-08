<!DOCTYPE html>
<html>
<head>
    <title>demo</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <link rel="stylesheet" href="assets/css/custom.css">

    <link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container-fluid">
    <div class="row mt-20">
        <div class="col-md-12 space">
            <button type="button" class="btn btn-secondary space" data-toggle="modal" data-target="#uploadModal">
                <i class="material-icons btn-icon">upload</i><span class="btn-h6"> Upload csv</span>
            </button>
            <button type="button" class="btn btn-secondary space" data-toggle="modal" data-target="#saveModal">
                <i class="material-icons btn-icon">save</i><span class="btn-h6"> Save markers</span>
            </button>
            <button type="button" class="btn btn-secondary space" data-toggle="modal" data-target="#titleModal">
                <i class="material-icons btn-icon">title</i><span class="btn-h6"> Add title</span>
            </button>
            <button type="button" class="btn btn-secondary space">
                <i class="material-icons btn-icon">gps_fixed</i><span class="btn-h6"> Get GPS track</span>
            </button>
        </div>
    </div>
    <div class="row mt-20">
        <div class="col-md-9">
            <div id="map"></div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="log"></table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="upload"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for title -->
<div class="modal" id="titleModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Input title for a new marker</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="save_marker_title">Confirm
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal for save csv file -->
<div class="modal" id="saveModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Save all markers in a new CSV file?</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title">Input file name:</label>
                            <input type="text" class="form-control" id="filename">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="save_markers">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
            </div>
        </div>

    </div>
</div>

<!-- Modal for upload -->
<div class="modal" id="uploadModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Please select CSV file</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="file" name="fileToUpload" id="fileToUpload">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="uploadFile">Confirm</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>
<script src="assets/js/custom.js"></script>

<script>
    /*************************** map declare and actions *********************/
    var tempTitle = '';
    $('#save_marker_title').click(function () {
        return tempTitle = $('#title').val();
    });
    var my_map = L.map('map').setView([60.78635459819804, 11.511869519863401], 13);
    L.tileLayer(
        'http://opencache.statkart.no/gatekeeper/gk/gk.open_gmaps?layers=topo4&zoom={z}&x={x}&y={y}',
        {maxZoom: 20,}
    ).addTo(my_map);
    var init_popup_title = 'This is a test position for L-marker.';
    make_new_marker([60.78635459819804, 11.511869519863401], tempTitle, init_popup_title);
    my_map.on('click', onMapClick);

    /***************************function after initial one.*********************/
    function onMapClick(e) {
        var popup_text = `You clicked the map at ${e.latlng.toString()}`;
        var title = tempTitle;
        make_new_marker(e.latlng, title, popup_text);
    }

    /********function for new marker. It can be called by new or new operator.*************/
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

    /**************************** Save *******************************/
    var down = <?php include "./load.php"; echo $final_tbl_data; ?>;
    var initialDown = [];
    down.slice(0).reverse().map(item => {
        var downRow = [];
        downRow.push(item.filename);
        initialDown.push(downRow);
    });
    var columns = [
        {
            title: 'Saved CSV files.',
            render: function (data, type, row, meta) {
                if (meta.row == 0) {
                    return type === 'display'
                        ? '<a href="download.php?path=' + data + '">' + data + '.csv' + ' <span class="badge badge-success">New</span>' + '</a>'
                        : data;
                }
                return type === 'display'
                    ? '<a href="download.php?path=' + data + '">' + data + '.csv' + '</a>'
                    : data;
            }
        }
    ];
    var downloadList = $('#log').DataTable({
        columns: columns,
        ordering: false,
        searching: false,
        pageLength: 5,
        bLengthChange: false,
        bInfo: false,
        data: initialDown
    });
    downloadList.clear();
    downloadList.rows.add(initialDown).draw();

    // Save markers' info to local storage.
    $('#save_markers').click(function () {
        var filename = $('#filename').val();
        if (filename != '') {
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
            $.ajax({
                type: 'POST',
                url: 'save.php',
                data: {
                    data: markers_info,
                    filename: filename
                },
                success: function (response) {
                    var re_tbl_data = JSON.parse(response);
                    var re_dataSet = [];
                    re_tbl_data.slice(0).reverse().map(item => {
                        var rowData = [];
                        rowData.push(item.filename);
                        re_dataSet.push(rowData);
                    });
                    downloadList.clear();
                    downloadList.rows.add(re_dataSet).draw();
                }
            });
        }
        else {
            alert('Please input file name.');
        }
    });


    /**************************** Upload *******************************/

    var uploaded = <?php include "./load.php"; echo $uploaded_list; ?>;
    var initialUpload = [];
    uploaded.slice(0).reverse().map(item => {
        var uploadRow = [];
        uploadRow.push(item.filename);
        initialUpload.push(uploadRow);
    });
    var u_columns = [
        {
            title: 'Uploaded CSV files.',
            render: function (data, type, row, meta) {
                if (meta.row == 0) {
                    return type === 'display'
                        ? data + ' <span class="badge badge-success">New</span>' + '<a class="fa fa-map-marker fa-load" id="' + data + '" style="font-size: 30px;"></a>'
                        : data;
                }
                return type === 'display'
                    ? data + '<a class="fa fa-map-marker fa-load" id="' + data + '" style="font-size: 30px;"></a>'
                    : data;
            }
        }
    ];
    var uploadList = $('#upload').DataTable({
        columns: u_columns,
        ordering: false,
        searching: false,
        pageLength: 5,
        bLengthChange: false,
        bInfo: false,
        data: initialUpload
    });
    uploadList.clear();
    uploadList.rows.add(initialUpload).draw();

    $('#uploadFile').on('click', function () {
        var file_data = $('#fileToUpload').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            url: 'upload.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (response) {
                var re_upload_list = JSON.parse(response);
                var re_initialUpload = [];
                re_upload_list.slice(0).reverse().map(item => {
                    var re_uploadRow = [];
                    re_uploadRow.push(item.filename);
                    re_initialUpload.push(re_uploadRow);
                });
                uploadList.clear();
                uploadList.rows.add(re_initialUpload).draw();
                history.go(0);
            }
        });
    });

    $('.fa-load').click(function (e) {
        console.log(e.currentTarget.id);
        $.ajax({
            type: 'POST',
            url: 'load.php',
            data: {
                show_name: e.currentTarget.id,
            },
            success: function (response) {
                console.log(JSON.parse(response))
                var show_data = JSON.parse(response);
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
    })

</script>
</html>
