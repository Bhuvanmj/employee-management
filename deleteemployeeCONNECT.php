<?php
// Database connection
require_once "db.php";


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $employee_id = $_POST['employee_id'];
    $department_id = $_POST['department_id'];
    $job_role_id = $_POST['job_role_id'];
    $bank_details_id = $_POST['bank_details_id'];
    $confirm_action = $_POST['confirm_action'];

    // Validate confirm action
    if ($confirm_action === "delete") {
        // Disable foreign key checks temporarily
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");

        try {
            // Delete from EmployeeDepartment table
            $sql1 = "DELETE FROM EmployeeDepartment WHERE EmployeeID = ? AND DepartmentID = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("ii", $employee_id, $department_id);
            $stmt1->execute();

            // Delete from EmployeeJobRole table
            $sql2 = "DELETE FROM EmployeeJobRole WHERE EmployeeID = ? AND JobRoleID = ?";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ii", $employee_id, $job_role_id);
            $stmt2->execute();

            // Delete from BankDetails table
            $sql3 = "DELETE FROM BankDetails WHERE EmployeeID = ? AND BankDetailID = ?";
            $stmt3 = $conn->prepare($sql3);
            $stmt3->bind_param("ii", $employee_id, $bank_details_id);
            $stmt3->execute();

            // Delete from Employee table
            $sql4 = "DELETE FROM Employee WHERE EmployeeID = ?";
            $stmt4 = $conn->prepare($sql4);
            $stmt4->bind_param("i", $employee_id);
            $stmt4->execute();

            echo "<h2>Employee record and related entries deleted successfully.</h2>";
        } catch (Exception $e) {
            echo "<h2>Error deleting records: " . $e->getMessage() . "</h2>";
        }

        // Enable foreign key checks back
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");

        // Close statements
        $stmt1->close();
        $stmt2->close();
        $stmt3->close();
        $stmt4->close();
    } else {
        echo "<h2>Action not confirmed. No records deleted.</h2>";
    }
}

// Close database connection
$conn->close();
?>
