<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VentureTwist - Transport Management</title>
   <link rel="stylesheet" href="transport.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
   
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "wanderful";

  
    $conn = new mysqli($servername, $username, $password, $dbname);

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submissions
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["action"])) {
            $action = $_POST["action"];
            $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;
            
            if ($action == "add" || $action == "edit") {
                // Add or edit transport
                $type = $_POST["type"];
                $name = $_POST["name"];
                $departure_location = $_POST["departure_location"];
                $arrival_location = $_POST["arrival_location"];
                $departure_time = $_POST["departure_time"];
                $arrival_time = $_POST["arrival_time"];
                $price = floatval($_POST["price"]);
                $capacity = intval($_POST["capacity"]);
                $status = $_POST["status"];
                $description = $_POST["description"];

                if ($action == "add") {
                    $stmt = $conn->prepare("INSERT INTO transports (type, name, departure_location, arrival_location, departure_time, arrival_time, price, capacity, status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssdiss", $type, $name, $departure_location, $arrival_location, $departure_time, $arrival_time, $price, $capacity, $status, $description);
                } else {
                    $stmt = $conn->prepare("UPDATE transports SET type=?, name=?, departure_location=?, arrival_location=?, departure_time=?, arrival_time=?, price=?, capacity=?, status=?, description=? WHERE id=?");
                    $stmt->bind_param("ssssssdissi", $type, $name, $departure_location, $arrival_location, $departure_time, $arrival_time, $price, $capacity, $status, $description, $id);
                }

                if ($stmt->execute()) {
                    $message = "Transport " . ($action == "add" ? "added" : "updated") . " successfully!";
                    echo "<div class='notification show success' id='notification'><i class='fas fa-check-circle'></i> $message</div>";
                } else {
                    $message = "Error: " . $conn->error;
                    echo "<div class='notification show error' id='notification'><i class='fas fa-exclamation-circle'></i> $message</div>";
                }
                $stmt->close();
            } elseif ($action == "delete" && $id > 0) {
                // Delete transport
                $stmt = $conn->prepare("DELETE FROM transports WHERE id=?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $message = "Transport deleted successfully!";
                    echo "<div class='notification show success' id='notification'><i class='fas fa-check-circle'></i> $message</div>";
                } else {
                    $message = "Error: " . $conn->error;
                    echo "<div class='notification show error' id='notification'><i class='fas fa-exclamation-circle'></i> $message</div>";
                }
                $stmt->close();
            }
        }
    }

    // Fetch transports from database
    $transports = [];
    $sql = "SELECT * FROM transports";
    $result = $conn->query($sql);

   if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $transports[] = $row;
    }
} else {
    
    echo "<div class='notification show error'>Query failed: " . $conn->error . "</div>";
}

    ?>

    <header>
        <div class="navbar">
            <div class="logo">
                <i class="fas fa-plane"></i>
                <span>VentureTwist</span>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="Destinations.html"><i class="fas fa-map-marked-alt"></i> Destinations</a></li>
                    <li><a href="#" class="active"><i class="fas fa-bus"></i> Transport</a></li>
                    <li><a href="#"><i class="fas fa-concierge-bell"></i> Packages</a></li>
                    <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-bus-alt"></i> Transport Management</h1>
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fas fa-plus"></i> Add Transport
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Transport Options</h2>
                <div class="filter-container">
                    <div class="filter-item">
                        <label for="transport-type">Transport Type</label>
                        <select id="transport-type" class="form-control select-control" onchange="filterTransports()">
                            <option value="all">All Types</option>
                            <option value="flight">Flight</option>
                            <option value="bus">Bus</option>
                            <option value="train">Train</option>
                            <option value="car">Car</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="status-filter">Status</label>
                        <select id="status-filter" class="form-control select-control" onchange="filterTransports()">
                            <option value="all">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="transport-table">
                        <thead>
                            <tr>
                                <th>Transport ID</th>
                                <th>Type</th>
                                <th>Route</th>
                                <th>Departure</th>
                                <th>Arrival</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transport-list">
                            <?php if (count($transports) > 0): ?>
                                <?php foreach ($transports as $transport): ?>
                                    <tr id="transport-<?php echo $transport['id']; ?>">
                                        <td><?php echo $transport['id']; ?></td>
                                        <td>
                                            <div class="transport-icon <?php echo $transport['type']; ?>">
                                                <i class="<?php 
                                                    echo $transport['type'] == 'flight' ? 'fas fa-plane' : 
                                                    ($transport['type'] == 'bus' ? 'fas fa-bus' : 
                                                    ($transport['type'] == 'train' ? 'fas fa-train' : 'fas fa-car')); 
                                                ?>"></i>
                                            </div>
                                            <?php echo ucfirst($transport['type']); ?>
                                        </td>
                                        <td><?php echo $transport['name']; ?></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($transport['departure_time'])); ?></td>
                                        <td><?php echo date('M j, Y g:i A', strtotime($transport['arrival_time'])); ?></td>
                                        <td>$<?php echo number_format($transport['price'], 2); ?></td>
                                        <td>
                                            <span class="status">
                                                <span class="status-indicator <?php echo $transport['status'] == 'active' ? 'status-active' : 'status-inactive'; ?>"></span>
                                                <?php echo ucfirst($transport['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="action-btn view" onclick="viewTransport(<?php echo $transport['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn edit" onclick="editTransport(<?php echo $transport['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="action-btn delete" onclick="confirmDelete(<?php echo $transport['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-bus-alt-slash"></i>
                                            <p>No transport options found</p>
                                            <button class="btn btn-primary" onclick="openAddModal()">
                                                <i class="fas fa-plus"></i> Add New Transport
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="transport-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-title">Add New Transport</h2>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            <form id="transport-form" method="POST" action="">
                <input type="hidden" name="action" id="form-action" value="add">
                <input type="hidden" name="id" id="transport-id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="transport-type-input">Transport Type *</label>
                        <select name="type" id="transport-type-input" class="form-control select-control" required>
                            <option value="">Select Type</option>
                            <option value="flight">Flight</option>
                            <option value="bus">Bus</option>
                            <option value="train">Train</option>
                            <option value="car">Car</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transport-name">Transport Name *</label>
                        <input type="text" name="name" id="transport-name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="departure-location">Departure Location *</label>
                        <input type="text" name="departure_location" id="departure-location" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="arrival-location">Arrival Location *</label>
                        <input type="text" name="arrival_location" id="arrival-location" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="departure-time">Departure Time *</label>
                        <input type="datetime-local" name="departure_time" id="departure-time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="arrival-time">Arrival Time *</label>
                        <input type="datetime-local" name="arrival_time" id="arrival-time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($) *</label>
                        <input type="number" name="price" id="price" class="form-control" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity *</label>
                        <input type="number" name="capacity" id="capacity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status *</label>
                        <select name="status" id="status" class="form-control select-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div id="view-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="view-modal-title">Transport Details</h2>
                <button class="close" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="transport-details" id="transport-details">
                    <!-- Details will be inserted here by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirm Deletion</h2>
                <button class="close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <form id="delete-form" method="POST" action="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="delete-id">
                <div class="modal-body">
                    <p>Are you sure you want to delete this transport option? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" onclick="closeDeleteModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>

   <script>
   

    // Modal functions
    function openAddModal() {
        document.getElementById('modal-title').textContent = 'Add New Transport';
        document.getElementById('form-action').value = 'add';
        document.getElementById('transport-id').value = '';
        document.getElementById('transport-form').reset();
        document.getElementById('transport-modal').style.display = 'flex';
    }

    function editTransport(id) {
        // Find the transport in our array - simulated here with PHP data
        fetch(`get_transport.php?id=${id}`)
            .then(response => response.json())
            .then(transport => {
                if (transport) {
                    document.getElementById('modal-title').textContent = 'Edit Transport';
                    document.getElementById('form-action').value = 'edit';
                    document.getElementById('transport-id').value = transport.id;
                    document.getElementById('transport-type-input').value = transport.type;
                    document.getElementById('transport-name').value = transport.name;
                    document.getElementById('departure-location').value = transport.departure_location;
                    document.getElementById('arrival-location').value = transport.arrival_location;
                    
                    // Format datetime for the input fields
                    const departureTime = new Date(transport.departure_time);
                    const arrivalTime = new Date(transport.arrival_time);
                    
                    document.getElementById('departure-time').value = 
                        departureTime.toISOString().slice(0, 16);
                    document.getElementById('arrival-time').value = 
                        arrivalTime.toISOString().slice(0, 16);
                    
                    document.getElementById('price').value = transport.price;
                    document.getElementById('capacity').value = transport.capacity;
                    document.getElementById('status').value = transport.status;
                    document.getElementById('description').value = transport.description;
                    
                    document.getElementById('transport-modal').style.display = 'flex';
                }
            })
            .catch(error => console.error('Error fetching transport:', error));
    }

    function closeModal() {
        document.getElementById('transport-modal').style.display = 'none';
    }

    function viewTransport(id) {
        fetch(`get_transport.php?id=${id}`)
            .then(response => response.json())
            .then(transport => {
                if (transport) {
                    document.getElementById('view-modal-title').textContent = transport.name + ' Details';

                    const detailsContainer = document.getElementById('transport-details');
                    detailsContainer.innerHTML = `
                        <div class="detail-row">
                            <div class="detail-label">Type:</div>
                            <div class="detail-value">
                                <div class="transport-icon ${transport.type}">
                                    <i class="${transport.type == 'flight' ? 'fas fa-plane' : 
                                               transport.type == 'bus' ? 'fas fa-bus' : 
                                               transport.type == 'train' ? 'fas fa-train' : 'fas fa-car'}"></i>
                                </div>
                                ${transport.type.charAt(0).toUpperCase() + transport.type.slice(1)}
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error fetching transport:', error));
    }

    // Delete confirmation modal
function confirmDelete(id) {
    document.getElementById('delete-id').value = id;
    document.getElementById('delete-modal').style.display = 'flex';
}

// Actual delete function
function deleteTransport() {
    const id = document.getElementById('delete-id').value;
    
    fetch('delete_transport.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: id })
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Remove the deleted row from the table
            const row = document.getElementById(`transport-${id}`);
            if (row) row.remove();
            
            // Show success message
            showNotification(data.message, 'success');
            
            // Show empty state if no more transports
            if (document.querySelectorAll('#transport-list tr').length === 0) {
                document.getElementById('empty-state').style.display = 'block';
            }
        } else {
            throw new Error(data.error || 'Unknown error occurred');
        }
    })
    .catch(error => {
        showNotification(error.message, 'error');
        console.error('Delete error:', error);
    })
    .finally(() => {
        closeDeleteModal();
    });
}

// Close delete modal
function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

// Initialize delete form submission
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('delete-form').addEventListener('submit', function(e) {
        e.preventDefault();
        deleteTransport();
    });
});

</script>
