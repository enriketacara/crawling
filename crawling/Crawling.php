<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protech</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .old-price {
            background-color: #ff0000; 
        }

        .new-price {
            background-color: #00ff00; 
        }
        .small-swal {
            width:15%; 
        }
        /*.chosen-container-multi {*/
        /*    width: 100% !important; !* Specify the desired width here *!*/
        /*}*/

        #selected_published .chosen-container-multi {
            width: 100% !important; /* Specify the desired width here */
        }

</style>
</head>
<body>

        <div class="container mt-5 ">    
    <h2 class="text-center">AVAILABLE  ITEMS
     <i data-toggle="tooltip" title="Get Content button retrieves content information of products from the selected websites in the below dropdown
     and save it in the respective tables and images in the respective directories. Before this step is taken, initially, the content of these websites
      must be cleared by clicking on the Delete Content button.
     Check Availability and Sizes of selected Items below-opens a small window where you can select id of Published Items and check the availability and sizes. The Publish button, which is for each product, allows the product to be published and displayed on the Published Items interface."
        
        class="fa fa-info-circle"></i>
    </h2>

    <div class="form-group">
        <label for="fileDropdown">Select Website:</label>
        <select class="form-control" id="selected_files" name="selected_files[]" multiple class='chosen-select'>
            <option value="off_market">Off Market</option>
            <option value="calzatureginevra">Calzature Ginevra</option>
            <option value="lerobshop">Lerob Shop</option>
        </select>
    </div>
    <div class="text-center">
 
    <div class="row mt-1">
            <div class="col-md-6 mb-1">
                <button class="btn btn-success btn-block" onclick="getContent()">Get Content of selected files</button>
            </div>
            <div class="col-md-6 mb-1">
                <button class="btn btn-danger btn-block" onclick="deleteContent()">Delete Content of selected files</button>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-md-6 mb-1">
                <button onclick="redirectToPublished()" class="btn btn-primary btn-block">Check Published Items</button>
            </div>
            <div class="col-md-6 mb-1">
                <button onclick="redirectToOrdered()" class="btn btn-warning btn-block">Check Ordered Items</button>
            </div>
        </div>
    </div>
    <div>
                <!-- <button onclick="updateStatus()" class="btn btn-info btn-block">Update Status of selected Website</button> -->
                <p>
  <button  class="btn btn-block btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
     Check Availability and Sizes of selected Items below
      <i class="fa fa-angle-down"></i>
  </button>
</p>
<div >
  <div class="collapse collapse-vertical" id="collapseWidthExample">
    <div class="card card-body">

        <div class="row mt-1">
            <div class="col-md-6 mb-1">
                <div class="form-group" id="selected_published">
                    <label for="fileDropdown">Select Id:</label><br>
                    <select class="form-control" id="selected_published_id" name="selected_published_id[]" multiple class='chosen-select'>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-1">
                <div class="form-group">
                    <label for="selected_published_id_tx"> Selected ID</label>
                    <textarea class="form-control" id="selected_published_id_tx" rows="1"></textarea>
                </div>
            </div>
        </div>
        <button  class="btn btn-info btn-block" type="button" id="updateAvailabilityAndSize">
            Update availability and Size
        </button>
    </div>
  </div>
 </div>
</div>
     
    <div class="mt-3" id="result"></div>
    </div>
    </div>
   <br></br>
        <table id="dataTable" class="table table-striped" style = "width:100%">
            <thead class="thead-light">
            <tr> 
                    <th>ID</th>
                    <th>Image</th>
                    <th>Site Name</th>
                    <th>Product URL</th>
                    <th>Brand</th>
                    <th>Unique Id</th>
                    <th>Description</th>
                    <th>Gender</th>
                    <th>Size</th>
                    <th>Old Price</th>
                    <th>Discount</th>
                    <th>New Price</th>
                    <th>Image Path</th>
                    <th>Table Name</th>
                    <th>Publish</th>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Site Name</th>
                    <th>Product URL</th>
                    <th>Brand</th>
                    <th>Unique Id</th>
                    <th>Description</th>
                    <th>Gender</th>
                    <th>Size</th>
                    <th>Old Price</th>
                    <th>Discount</th>
                    <th>New Price</th>
                    <th>Image Path</th>
                    <th>Table Name</th>
                    <th>Publish</th>
                </tr>
            </thead>
            <tbody>
           
            </tbody>
        </table>
    </div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Include DataTables JS -->
<script src = "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src = "https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
  })
function redirectToPublished() {
  window.open('published.php', '_blank');
}
function redirectToOrdered() {
  window.open('ordered.php', '_blank');
}
$('#selected_files').chosen();
$('#selected_published_id').chosen();

    $('#selected_published_id').change(function(){
        var selectedOptions = $('#selected_published_id').val();
        $('#selected_published_id_tx').val(selectedOptions.join(','));
    });
    $(document).ready(function(){
        $('#updateAvailabilityAndSize').click(function() {
            var ids = $('#selected_published_id_tx').val().split(','); // Split textarea value into an array of IDs

            // AJAX request for each ID
            ids.forEach(function(id) {
                $.ajax({
                    url: 'fetch_id_data.php', // Replace 'fetch_details.php' with the actual path to your PHP script
                    method: 'GET',
                    dataType: 'json',
                    data: { id: id.trim() }, // Send the ID to the server
                    success: function(response) {
                        if (response.data) {
                            // Update size column
                            updateSize(response.data.unique_id, response.data.table_name,response.data.product_url);
                        } else {
                            alertify.set('notifier', 'position', 'top-left');
                            alertify.error('Error fetching details for ID: ' + id);
                        }
                    },
                    error: function(xhr, status, error) {
                        alertify.error('Error fetching details for ID: ' + id + ' - ' + error);
                    }
                });
            });
        });

        // Function to update size column
        function updateSize(uniqueId, tableName, productUrl) {
            // AJAX request to update size column
            $.ajax({
                url: 'update_size.php', // Replace 'update_size.php' with the actual path to your PHP script
                method: 'POST',
                data: { unique_id: uniqueId, table_name: tableName ,product_url: productUrl },
                success: function(response) {
                    alertify.set('notifier', 'position', 'top-left');
                    alertify.success('Size and availability got updated for unique ID: ' + uniqueId);
                },
                error: function(xhr, status, error) {
                    alertify.error('Error updating size column for unique ID: ' + uniqueId + ' - ' + error);
                }
            });
        }
    });

    $(document).ready(function(){
        // Fetch data from the server
        $.ajax({
            url: 'get_published_items_id.php', // Replace 'your_php_script.php' with the actual path to your PHP script
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.data) {
                    // Populate dropdown options
                    var dropdown = $('#selected_published_id');
                    $.each(response.data, function(index, item) {
                        dropdown.append($('<option>', {
                            value: item.id,
                            text: item.id
                        }));
                    });
                    // Initialize Chosen plugin after options are added
                    dropdown.trigger('chosen:updated');
                } else {
                    alertify.error('Error fetching data: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alertify.error('Error fetching data: ' + error);
            }
        });
    });
function showConfirmation(callback) {
    Swal.fire({
        // title: 'Confirmation',
        text: 'Are you sure you want to proceed?',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        icon: 'question',
        customClass: {
            popup: 'small-swal' // Define a custom class for the Swal popup
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // If user clicks "Yes", execute the callback function
            callback();
        }
    });
}

function getContent() {
    showConfirmation(function() {
        var selectedFiles = $('#selected_files').val();
        if (selectedFiles) {
            $.ajax({
                type: 'POST',
                url: 'get_contents.php',
                data: { files: selectedFiles },
                success: function(response) {
                    // $('#result').append('<p>' + response + '</p>');
                    alertify.set('notifier', 'position', 'top-left');
                    // alertify.success('Product successfully got published.');
                    var responseData = JSON.parse(response); // Parse the JSON response
                    alertify.set('notifier', 'position', 'top-left');
                    $.each(responseData, function(index, item) {
                        if (item.error) {
                            alertify.error(item.error);
                        } else {
                            alertify.notify(item.success, 'success', 5);
                        }
                    });
                },
                error: function(error) {
                    alertify.error('Error executing files: ' + error);

                }
            });
        }
     });
}

    function deleteContent() {
        showConfirmation(function() {
        var selectedFiles = $('#selected_files').val();
        if (selectedFiles) {
            $.ajax({
                type: 'POST',
                url: 'delete_contents.php',
                data: { files: selectedFiles },
                success: function(response) {
                    $('#result').append('<p>' + response + '</p>');
                   
                },
                error: function(error) {
                    console.error('Error deleting content: ' + error);
                }
            });
        }
    });
}
    function updateStatus() {
    showConfirmation(function() {
        var selectedFiles = $('#selected_files').val();
        if (selectedFiles) {
            $.ajax({
                type: 'POST',
                url: 'update_status_form_gui.php',
                data: { files: selectedFiles },
                dataType: 'json',
                success: function(response) {
                    alertify.set('notifier', 'position', 'top-left');
                    $.each(response, function(index, item) {
                        if (item.error) {
                            alertify.error(item.error);
                        } else {
                            alertify.notify(item.message, 'success', 5);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    alertify.error('Error executing files: ' + error);
                }
            });
        }
    });
}



$(document).ready(function() {
    $('#dataTable').DataTable({
        "processing": true,
        "responsive": true,
        "serverSide": false,
        "paging": true,
        "pageLength": 10, 
        ajax: 'get_data.php', 

        columns: [
            { data: 'id' },
            { 
                data: 'image',
                render: function(data, type, row) {
                    var encodedImageSrc = data.replace(/%/g, '%25');
                     return '<img src="' + encodedImageSrc + '" alt="Image" width="100" height="100">';
                    //  return '<img src="' + data + '" alt="Image" width="100" height="100">';
                }
            },
            { data: 'site_name' },
            { 
                data: 'product_url',
                render: function(data, type, row) {
                    return '<a href="' + data + '" target="_blank" class="btn btn-warning" title="Check the product details by pressing the button"><span class="text-white font-weight-bold">Check Product</span></a>';
                }
            },
            { data: 'brand' },
            { data: 'unique_id' },
            { data: 'type' },
            { data: 'gender' },
            { data: 'size' },
            { data: 'old_price', className: 'old-price'},
            { data: 'discount'},
            { data: 'new_price', className: 'new-price' },
            { data: 'image_path', visible: false },
            { data: 'table_name', visible: false },
            {
                data: 'status',
                className: 'action-column',
                render: function(data, type, row) {
                if (data === 'unpublished') {
                return '<button class="btn btn-primary btn-sm btn-insert" data-id="' + row.id + '">Publish</button>';
                   } else {
                return 'Published'; // Or any other content/message for the published status
            }
        }
            }
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search...">')
                    .appendTo($(column.header()).empty())
                    .on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
            });
        },
        ordering: true,
        createdRow: function(row, data, dataIndex) {
            $(row).find('.old-price').css('background-color', '#ffd6cc'); // Red color
            $(row).find('.new-price').css('background-color', '#ccffcc'); // Green color
        }
    });

  // Handle button click event
$('#dataTable tbody').on('click', '.btn-insert', function() {
    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    // Call a function to insert rowData into another table using Ajax
    insertDataIntoAnotherTable(rowData);
     // Replace the button with the text "Published"
     $(this).replaceWith('<span class="published-text">Published</span>');
});



  // Function to insert data into another table using Ajax
function insertDataIntoAnotherTable(rowData) {
    console.log('rowData:', rowData);

    // Perform an Ajax request to insert data into another table
    $.ajax({
        url: 'publish_data.php', // Replace with your server-side script
        type: 'POST',
        data: { rowData: JSON.stringify(rowData) },
        success: function(response) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.success('Product successfully got published.');
        },
        error: function(error) {
            alertify.error('Error publishing the product ');
        }
    });
}

});
       
    </script>
</body>
</html>
