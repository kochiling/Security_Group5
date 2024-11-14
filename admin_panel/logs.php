<?php include('partials/_header.php') ?>


<?php include('partials/_sidebar.php') ?>
<input type="hidden" value="4" id="checkFileName">

<!-- Table of Logs -->
<div class="content">
    <!-- Navbar -->
    <?php include("partials/_navbar.php"); ?>
    <!-- End of Navbar -->

    <main>
        <div class="header">
            <div class="left">
                <h1>Logs</h1>
                <ul class="breadcrumb">

                    <div class="logs" style="background-color: white; padding: 20px; border-radius: 10px;">
                        <div class="header">
                            <i class='bx bx-history'></i>
                            <h3>Logs</h3>
                            <i class='bx bx-filter'></i>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>Time</th>
                                    <th>Context</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody">
                            </tbody>
                        </table>
                    </div>
                    <style>
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        table,
                        th,
                        td {
                            border: 1px solid #ddd;
                        }

                        th,
                        td {
                            padding: 12px;
                            text-align: left;
                        }

                        th {
                            background-color: #f2f2f2;
                        }

                        tr:nth-child(even) {
                            background-color: #f9f9f9;
                        }

                        tr:hover {
                            background-color: #f1f1f1;
                        }

                        /* Add scrollbar for more than 10 items */
                        tbody {
                            display: block;
                            max-height: 500px;
                            overflow-y: auto;
                        }

                        thead,
                        tbody tr {
                            display: table;
                            width: 100%;
                            table-layout: fixed;
                        }
                    </style>

                    <script src="../assets/js/dashboard.js"></script>
                    <?php include('partials/_footer.php'); ?>