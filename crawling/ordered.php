<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Protech</title>
    <!-- Include Bootstrap CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.min.css" />
<link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
<style>
        .old-price {
            color: #ff0000; 
        }
        .new-price {
            color: #00ff00; 
        }
        .small-swal {
            width:15%; 
        }
    </style>
</head>
<body>
    <div class="">
         
   <div class="container mt-5 ">    
    <h2 class="text-center">ORDERED  ITEMS</h2>
    <div class="text-center">
 
 <div class="row mt-1">
         <div class="col-md-6 mb-1">
             <button class="btn btn-success btn-block" onclick="redirectToAvailable()">Check Available Items </button>
         </div>
         <div class="col-md-6 mb-1">
             <button class="btn btn-primary btn-block" onclick="redirectToPublished()">Check Published Items</button>
         </div>
      </div>
     </div>
        
    </div>
</div>

        <table id="dataTable" class="table table-striped" style = "width:100%">
            <thead class="thead-light">
            <tr> 
                    <th>ID</th>
                    <th>Image</th>
                    <th>Site Name</th>
                    <th>Product URL</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th>Gender</th>
                    <th>Size</th>
                    <th>Old Price</th>
                    <th>Discount</th>
                    <th>New Price</th>
                    <th>Final Buying Price</th>
                    <th>Final Selling Price</th>
              
                </tr>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Site Name</th>
                    <th>Product URL</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th>Gender</th>
                    <th>Size</th>
                    <th>Old Price</th>
                    <th>Discount</th>
                    <th>New Price</th>
                    <th>Final Buying Price</th>
                    <th>Final Selling Price</th>
                   
                </tr>
            </thead>
            <tbody>
           
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src = "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
function redirectToPublished() {
    window.location.href = 'published.php';
}
function redirectToAvailable() {
    window.location.href = 'Crawling.php';
}
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
$(document).ready(function() {
    $('#dataTable').DataTable({
        ajax: 'get_ordered.php', 
        columns: [
            { data: 'id' },
            { 
                data: 'image',
                render: function(data, type, row) {
                    var encodedImageSrc = data.replace(/%/g, '%25');
                     return '<img src="' + encodedImageSrc + '" alt="Image" width="100" height="100">';
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
            { data: 'type' },
            { data: 'gender' },
            { data: 'ordered_size' },
            { data: 'old_price', className: 'old-price'},
            { data: 'discount'},
            { data: 'new_price', className: 'new-price' },
            { data: 'final_buying_price', className: 'old-price'},
            { data: 'final_selling_price' , className: 'new-price'}
          
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

});
 
    </script>
</body>
</html>
