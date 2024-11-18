document.getElementById('addAdminForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('../assets/addAdmin.php', { 
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data === 'success') {
            alert('Admin added successfully!');
            document.getElementById('addAdminForm').reset();
            showAdmins(); // Automatically show the admin list after adding a new admin
        }
    })
    .catch(error => console.error('Error:', error));
});

function showAdmins() {
    // Fetch admin data from the server
    fetch('../assets/fetchAdmins.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById("admin-table-body");

        // Check if data is empty or indicates "No_Record"
        if (!data || data === "No_Record") {
            tableBody.innerHTML = "<tr><td colspan='4'>No admins found</td></tr>";
            document.getElementById("dataNotAvailable").style.display = 'block';
        } else {
            document.getElementById("dataNotAvailable").style.display = 'none';

            // Populate table with admin data
            tableBody.innerHTML = data.map((admin, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${admin.id}</td>
                    <td>${admin.email}</td>
                    <td>
                        ${admin.role === 'admin' ? 
                            `<button class="block-btn" onclick="blockAdmin('${admin.id}')">Block</button>` : 
                            `<span style="color: red; font-weight: bold;">BLOCKED</span>`}
                    </td>
                </tr>
            `).join('');
        }
    })
    .catch(error => console.error('Error:', error));
}

function blockAdmin(adminId) {
    console.log("Blocking admin with ID:", adminId); // Add this for debugging
    if (confirm('Are you sure you want to block this admin?')) {
        fetch('../assets/blockAdmins.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'adminId=' + encodeURIComponent(adminId),
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'success') {
                alert('Admin blocked successfully!');
                showAdmins(); // Refresh the admin list after blocking an admin
            } else {
                alert('Error blocking admin: ' + data);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

