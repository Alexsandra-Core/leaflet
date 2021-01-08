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
</head>
<body>
    <div class="container">
        <div class="row mt-20">
            <div class="col-md-12">
                <div id="map"></div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <button type="button" class="btn" data-toggle="modal" data-target="#uploadModal" style="float: right">
                    <i class="material-icons option-icon">upload</i>
                </button>
                <button type="button" class="btn" data-toggle="modal" data-target="#saveModal" style="float: right">
                    <i class="material-icons option-icon">save</i>
                </button>
                <button type="button" class="btn" data-toggle="modal" data-target="#titleModal" style="float: right">
                    <i class="material-icons option-icon">title</i>
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered" id="log"></table>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="save_marker_title">Confirm</button>
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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Do you want to save all markers in a new CSV file?</h5>
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
    var tbl_data = <?php include "./load.php"; echo $final_tbl_data; ?>;
    var initial_dataSet = [];
    tbl_data.slice(0).reverse().map(tempRow => {
        var rowDataSet = [];
        rowDataSet.push(tempRow.timestamp);
        initial_dataSet.push(rowDataSet);
    });
    var columns = [
        {
            title: 'Saved CSV files.',
            render: function (data, type, row, meta) {
                if (meta.row == 0){
                    return type === 'display'
                        ? '<a href="download.php?path='+data+'">'+data+'.csv'+' <span class="badge badge-success">New</span>'+'</a>'
                        : data;
                }
                return type === 'display'
                    ? '<a href="download.php?path='+data+'">'+data+'.csv'+'</a>'
                    : data;
            }
        }
    ];
    $('#log').DataTable({
        columns: columns,
        ordering: false,
        searching: false,
        pageLength: 5,
        bLengthChange: false,
        bInfo: false,
        data: initial_dataSet
    });
</script>
</html>
