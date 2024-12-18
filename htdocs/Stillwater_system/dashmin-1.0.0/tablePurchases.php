<?php
    include ('../db_connection.php');

    session_start();

    // Check if the user_admin is logged in
    if (!isset($_SESSION['UserType'])) {
        header("Location: ../loginSystem/log_in.php");
        echo "<script>alert('You must log in first. Redirecting to login page...');</script>";
        exit;
    }

    function updateButton($Client_id) {
        echo '<button onclick="window.open(\'../Admin/update_client.php?Client_id=' . $Client_id . '\', \'_blank\', \'width=800,height=600\')" class="updateButton">
        Update
        </button>';
    }
    function deleteButton($Client_id) {
        echo '<button onclick="window.location.href=\'../Admin/delete_client.php?Client_id=' . $Client_id . '\'" class="deleteButton">
        Delete
        </button>';
    }

    function updateButton1($Item_number) {
        echo '<button onclick="window.open(\'../Admin/update_item.php?Item_number=' . $Item_number . '\', \'_blank\', \'width=800,height=600\')" class="updateButton">
        Update
        </button>';
    }

    function deleteButton1($Item_number) {
        echo '<button onclick="window.location.href=\'../Admin/delete_item.php?Item_number=' . $Item_number . '\'" class="deleteButton">
        Delete
        </button>';
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link rel="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="css/custom.css" rel="stylesheet">

    <!-- Custom CSS for DataTables -->
    <style>
        .dataTables_filter {
            text-align: right;
            margin-bottom: 10px;
        }
        .dataTables_filter input {
            width: 300px;
            margin-left: 0.5em;
            display: inline-block;
        }
        .dataTables_length {
            float: left;
            text-align: left;
            margin-right: 1em;
            margin-bottom: 10px;
        }
        .dataTables_length select {
            display: inline-block;
            width: auto;
            margin-left: 0.5em;
        }
        .dataTables_paginate {
        margin-top: 10px;
        display: inline-block;
        }
        .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            margin-left: 0.5em;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
        }
        .dataTables_paginate .paginate_button:hover {
            background-color: #e9ecef;
            border-color: #ddd;
        }
        .dataTables_paginate .paginate_button.current {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }
    </style>
</head>

<body>
<div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.html" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>AdminBoard</h3>
                </a>
                <div class="navbar-nav w-100">
                    <div class="nav-item border-start border-3 border-primary">
                        <div class="nav flex-column ms-4 mt-2">
                            <a href="tableClient.php" class="nav-link text-primary rounded mb-2 px-3 py-2">Clients Table</a>
                            <a href="tableItem.php" class="nav-link text-primary rounded mb-2 px-3 py-2">Items Table</a>
                            <a href="tablePurchases.php" class="nav-link text-primary bg-primary text-white rounded mb-2 px-3 py-2">Purchases Table</a>
                            <a href="tableSales.php" class="nav-link text-primary rounded mb-2 px-3 py-2">Sales Table</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <!-- Content Start -->
        <div class="content">
                        <!-- Navbar Start -->
                        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
            </nav>
            <!-- Navbar End -->

            <!-- Table Start -->
            <div class="container-fluid">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 fw-bold text-primary">Purchases Table</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive overflow-visible">
                            <div class="dataTables_length" id="dataTable_length">
                                <label style="margin-left: 10px;">Status 
                                    <select id="statusFilter" class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                        <option value="">All</option>
                                        <option value="Valid">Valid</option>
                                        <option value="Deleted">Deleted</option>
                                    </select>
                                </label>
                            </div>
                            <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead id="tableHeaderPurchases">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Client Name</th>
                                            <th>Purchase Cost</th>
                                            <th>Date Purchased</th>
                                            <th>Condition at Purchased</th>
                                        </tr>
                                    </thead>
                                    <tfoot id="tableFooterPurchases">
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Client Name</th>
                                            <th>Purchase Cost</th>
                                            <th>Date Purchased</th>
                                            <th>Condition at Purchased</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="tableBodyPurchases">
                                    <?php
                                        $query = "SELECT Purchases.*, Item.Item_name, Client.First_name, Client.Lastname FROM Purchases 
                                        INNER JOIN Item ON Purchases.Item_number = Item.Item_number
                                        INNER JOIN Client ON Purchases.Client_id = Client.Client_id";
                                
                                        $result = mysqli_query($conn, $query);
                                
                                        if (!$result) {
                                            die("Query failed: " . mysqli_error($conn));
                                        }
                                
                                        // Loop through each row in the result set and display it in the table
                                        setlocale(LC_MONETARY, 'c', 'en-PH');
                                        while($row = mysqli_fetch_array($result)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row['Item_name']; ?></td>
                                            <td><?php echo $row['Lastname'] . ', ' . $row['First_name']; ?></td>
                                            <td><?php echo number_format($row['Purchase_cost'] ?? 0); ?></td>
                                            <td><?php echo $row['Date_purchased']; ?></td>
                                            <td><?php echo $row['Condition_at_purchased']; ?></td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>

            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="lib/chart/chart.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/tempusdominus/js/moment.min.js"></script>
        <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
        <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

        <!-- DataTables Initialization -->
        <script>
    $(document).ready(function() {

        var purchasesTable = $('#dataTablePurchases').DataTable({
            "searching": true,
            "dom": '<"top"lf>rt<"bottom"ip><"clear">'
        });

        $('#statusFilter').on('change', function() {
            var selectedValue = $(this).val();
            var visibleTable = $('table:visible').DataTable();
            visibleTable.column(5).search(selectedValue).draw();
        });

    });
        </script>
    </div>
</body>

</html>
