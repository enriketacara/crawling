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
<link rel = "stylesheet" href = "https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>

        .old-price {
            color: #ff0000; 
        }

        .new-price {
            color: #00ff00; 
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .small-swal {
            width:15%; 
        }

        
    </style>
</head>
<body>
    
   <div class="container mt-5 ">    
    <h2 class="text-center">PUBLISHED  ITEMS
        <i data-toggle="tooltip" title="Delete checked items button:Deletes selected products in the checkboxes located in the first column of the table.The status of this product in the Available Items interface changes, and the Publish button appears again to republish the product.
Update button:Updates the available size for that product.
Ordered button:In the Final buying price, final selling price, and ordered price columns, we place the necessary information, and when we click on the ordered button, this product is automatically added to the Ordered Items interface."
           class="fa fa-info-circle"></i>
    </h2>
    <div class="text-center">
 
 <div class="row mt-1">
         <div class="col-md-6 mb-1">
             <button class="btn btn-success btn-block" onclick="redirectToAvailable()">Check Available Items </button>
         </div>
         <div class="col-md-6 mb-1">
             <button class="btn btn-primary btn-block" onclick="redirectToOrdered()">Check Ordered Items</button>
         </div>
         <div class="col-md-6 mb-1">
             <button id="deleteCheckedItems" class="btn btn-danger btn-block">Delete checked Items</button>
         </div>
         <!-- <div class="col-md-6 mb-1">
             <button id="updateCheckedItem" class="btn btn-warning btn-block">-------</button>
         </div> -->
      </div>
     </div>
        
    </div>
</div>
  
        <table id="dataTable" class="table table-striped" style = "width:100%">
            <thead class="thead-light">
            <tr> 
                    <th>Check</th>
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
                    <th>Orderd size </th>
                    <th>Final Buying Price</th>
                    <th>Final Selling Price</th>
                    <th>Image Path</th>
                    <th>Table Name</th>
                    <th>Update</th>
                    <th>Ordered</th>
                    <!-- <th>Delete</th> -->
                </tr>
                <tr>
                    <th>Check</th>
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
                    <th>Orderd size </th>
                     <th>Final Buying Price</th>
                    <th>Final Selling Price</th>
                    <th>Image Path</th>
                    <th>Table Name</th>
                    <th>Update</th>
                    <th>Ordered</th>
                    <!-- <th>Delete</th> -->
                </tr>
            </thead>
            <tbody>
           
            </tbody>
        </table>
   

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src = "https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src = "https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Include Alertify JS from CDN -->
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
function redirectToOrdered() {
    window.location.href = 'ordered.php';
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
    var dataTable = $('#dataTable').DataTable({
        "processing": true,
        "responsive": true,
        ajax: 'get_published.php', 
        columns: [
            { data: null,
                render: function(data, type, row) {
                    return '<input type="checkbox" class="itemCheckbox" data-id="' + row.id + '" title="Check the product"><span class="text-white font-weight-bold"/>';
                
                }
            },
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
            { data: 'unique_id' },
            { data: 'type' },
            { data: 'gender' },
            { data: 'size' },
            { data: 'old_price', className: 'old-price'},
            { data: 'discount'},
            { data: 'new_price', className: 'new-price' },
            { data: 'ordered_size', className: 'editable', render: function (data, type, row) {
                return '<input type="text" class="form-control form-control-sm" value="' + data + '" data-id="' + row.id + '" name="ordered_size">';
            }},
            { data: 'final_buying_price', className: 'editable', render: function (data, type, row) {
                return '<input type="text" class="form-control form-control-sm" value="' + data + '" data-id="' + row.id + '" name="final_buying_price">';
            }},
            { data: 'final_selling_price', className: 'editable', render: function (data, type, row) {
                return '<input type="text" class="form-control form-control-sm" value="' + data + '" data-id="' + row.id + '" name="final_selling_price">';
            }},
            { data: 'image_path', visible: false}, 
            { data: 'table_name', visible: false}, 
            { data: null,
                className: 'action-column',
                render: function(data, type, row) {
                    return '<button class="btn btn-primary btn-sm btn-update" title="Get real time product data from website" data-id="' + row.id + '">Update</button>';
            } 
            }, 
            {
                data: null,
                className: 'action-column',
                render: function(data, type, row) {
                    return '<button class="btn btn-success btn-sm btn-insert" title="The product is being ordered" data-id="' + row.id + '">Ordered</button>';
                }
            }
            // , 
            // {
            //     data: null,
            //     className: 'action-column',
            //     render: function(data, type, row) {
            //         return '<button class="btn btn-danger btn-sm btn-delete" title="The product is being ordered" data-id="' + row.id + '">Delete</button>';
            //     }
            // }
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

    var checkedRows = []; // Array to store checked rows
// Add event listener for checkboxes
$('#dataTable tbody').on('change', '.itemCheckbox', function() {
    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    if ($(this).is(':checked')) {
        checkedRows.push(rowData); // Add checked row to array
    } else {
        var index = checkedRows.findIndex(function(item) {
            return item.id === rowData.id;
        });
        if (index !== -1) {
            checkedRows.splice(index, 1); // Remove unchecked row from array
        }
    }
});

// Add event listener for "Update" button
$('#deleteCheckedItems').on('click', function() {
    showConfirmation(function() {
    if (checkedRows.length > 0) {
        checkedRows.forEach(function(rowData) {
            deletePublishedItem(rowData);
        });
        checkedRows = []; // Clear the array after updating
    } else {
        alert('Please select at least one item to update.');
    }
  });
});

    // $('#dataTable tbody').on('change', '.itemCheckbox', function() {
    //     var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    //     if ($(this).is(':checked')) {
    //         updateItem(rowData);
    //     }
    // });

    // Handle button click event
    $('#dataTable tbody').on('click', '.btn-insert', function() {
    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    // Call a function to insert rowData into another table using Ajax
    insertDataIntoAnotherTable(rowData);
    updateDatabase(rowData);
     });

   $('#dataTable tbody').on('click', '.btn-update', function() {
    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    updateItem(rowData);
    });

   $('#dataTable tbody').on('click', '.btn-delete', function() {
    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
    deletePublishedItem(rowData);
   });

    // Function to insert data into another table using Ajax
    function insertDataIntoAnotherTable(rowData) {
    
    // Get the values from the input fields
    var finalBuyingPrice = $('[name="final_buying_price"][data-id="' + rowData.id + '"]').val();
    var finalSellingPrice = $('[name="final_selling_price"][data-id="' + rowData.id + '"]').val();
    var ordered_size = $('[name="ordered_size"][data-id="' + rowData.id + '"]').val();

    // Include the input values in the rowData object
    var updatedRowData = {
        id: rowData.id,
        image: rowData.image_path,
        site_name: rowData.site_name,
        product_url: rowData.product_url,
        brand: rowData.brand,
        unique_id: rowData.unique_id,
        type: rowData.type,
        gender: rowData.gender,
        size: rowData.size,
        old_price: rowData.old_price,
        discount: rowData.discount,
        new_price: rowData.new_price,
        final_buying_price: finalBuyingPrice,
        final_selling_price: finalSellingPrice,
        ordered_size: ordered_size
    };

    // Create the postData object
    var postData = {
        rowData: JSON.stringify(updatedRowData),
    };

    // Perform an Ajax request to insert data into another table
    $.ajax({
        url: 'order_data.php', // Replace with your server-side script
        type: 'POST',
        data: postData,
        success: function (response) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.success('Product successfully inserted into Ordered Products.');
        },
        error: function (error) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.error('Error inserting product into ordered products. Please try again.');
        },
    });
 
}
// Function to update the database with the changed values
function updateDatabase(rowData) {
    showConfirmation(function() {
    // Get the values from the input fields
    var finalBuyingPrice = $('[name="final_buying_price"][data-id="' + rowData.id + '"]').val();
    var finalSellingPrice = $('[name="final_selling_price"][data-id="' + rowData.id + '"]').val();
    var ordered_size = $('[name="ordered_size"][data-id="' + rowData.id + '"]').val();
    $.ajax({
        url: 'update_data.php', // Replace with your server-side script
        type: 'POST',
        data: {
            id: rowData.id,
            final_buying_price: finalBuyingPrice,
            final_selling_price: finalSellingPrice,
            ordered_size: ordered_size,
        },
        success: function (response) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.success('Product successfully updated');
        },
        error: function (error) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.error('Error updating product. Please try again.');
        },
    });
});

}
// Function to insert data into another table using Ajax
function updateItem(rowData) {
    // showConfirmation(function() {
    // Get the values from the input fields
    var finalBuyingPrice = $('[name="final_buying_price"][data-id="' + rowData.id + '"]').val();
    var finalSellingPrice = $('[name="final_selling_price"][data-id="' + rowData.id + '"]').val();
    var ordered_size = $('[name="ordered_size"][data-id="' + rowData.id + '"]').val();
 $.ajax({
        url: 'update_item.php', // Replace with your server-side script
        type: 'POST',
        data: {
         id: rowData.id,
        image: rowData.image_path,
        site_name: rowData.site_name,
        product_url: rowData.product_url,
        brand: rowData.brand,
        unique_id: rowData.unique_id,
        type: rowData.type,
        gender: rowData.gender,
        size: rowData.size,
        old_price: rowData.old_price,
        discount: rowData.discount,
        new_price: rowData.new_price,
        final_buying_price: finalBuyingPrice,
        final_selling_price: finalSellingPrice,
        ordered_size: ordered_size,
        table_name: rowData.table_name,
        },
        success: function (response) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.success('Item successfully updated');
            setTimeout(function() {
            location.reload();
            }, 1000);
        },
        error: function (error) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.error('Error updating item. Please try again.');
            setTimeout(function() {
            location.reload();
           }, 1000);
        },
    });
// });

}
// Function to insert data into another table using Ajax
function deletePublishedItem(rowData) {
    $.ajax({
        url: 'delete_item.php', // Replace with your server-side script
        type: 'POST',
        data: {
            id: rowData.id,
            table_name: rowData.table_name,
            unique_id: rowData.unique_id,
        },
        success: function (response) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.success('Item successfully deleted');
            setTimeout(function() {
            location.reload();
           }, 2000);
        },
        error: function (error) {
            alertify.set('notifier', 'position', 'top-left');
            alertify.error('Error deleting the item. Please try again.');
            setTimeout(function() {
            location.reload();
           }, 2000);
        },
    });


}

});

    </script>
</body>
</html>
