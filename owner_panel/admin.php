<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/boxicons/2.1.4/css/boxicons.min.css">
    <style>
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            margin: 0 auto;
            max-width: 800px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        .students-table table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .students-table th, .students-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .block-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .block-btn:hover {
            background-color: #c82333;
        }

        .no-data-box {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #666;
        }
    </style>
</head>
<body>

<div class="content">
    <main>
        <div class="header">
            <h1>Admin Management</h1>
        </div>
        <div class="bottom-data">
            <div class="orders">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item me-1" role="presentation">
                        <button class="nav-link active" id="addAdminTab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Add Admin</button>
                    </li>
                    <li class="nav-item me-1" role="presentation">
                        <button class="nav-link" id="showAdminTab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false" onclick="showAdmins()">Show Admins</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Add Admin Form -->
                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                        <div class="container mt-4">
                            <h3>Add a New Admin</h3>
                            <form id="addAdminForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-2"><i class='bx bxs-user-plus'></i> Add Admin</button>
                            </form>
                        </div>
                    </div>

                    <!-- Show Admins List (With Block Option) -->
                    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <div class="container mt-4">
                            <h3><i class='bx bx-list-ul'></i> Admin List</h3>
                            <hr>
                            <div class="students-table">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Admin ID</th>
                                            <th>Email</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="admin-table-body">
                                        <!-- Content populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="dataNotAvailable">
                                <div class="no-data-box">
                                    <i class='bx bx-data'></i>
                                    <p>No Data</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/admin.js"></script>
</body>
</html>
