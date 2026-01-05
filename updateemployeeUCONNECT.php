<?php
// Database connection

require_once "db.php";


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get form data
    $employee_id = $_POST["employee_id"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $bank_details_id = $_POST["bank_details_id"];
    $bank_name = $_POST["bank_name"];
    $account_number = $_POST["account_number"];
    $department_id = $_POST["department_id"];
    $department_name = $_POST["department_name"];
    $job_role_id = $_POST["job_role_id"];
    $job_role_name = $_POST["job_role_name"];

    // Update employee details
    $sql_update_employee = "UPDATE Employee 
                            SET FirstName = '$first_name', LastName = '$last_name', Gender = '$gender', 
                                Birthdate = '$dob', ContactInfo = '$phone', Address = '$address'
                            WHERE EmployeeID = '$employee_id'";

    if ($conn->query($sql_update_employee) === TRUE) {
        echo "Employee details updated successfully.<br>";
    } else {
        echo "Error updating employee details: " . $conn->error;
    }

    // Update bank details
    $sql_update_bank = "UPDATE BankDetails 
                        SET BankName = '$bank_name', AccountNumber = '$account_number'
                        WHERE BankDetailID = '$bank_details_id'";

    if ($conn->query($sql_update_bank) === TRUE) {
        echo "Bank details updated successfully.<br>";
    } else {
        echo "Error updating bank details: " . $conn->error;
    }

    // Update department details
    $sql_update_department = "UPDATE Department 
                              SET DepartmentName = '$department_name' 
                              WHERE DepartmentID = '$department_id'";

    if ($conn->query($sql_update_department) === TRUE) {
        echo "Department details updated successfully.<br>";
    } else {
        echo "Error updating department details: " . $conn->error;
    }

    // Update job role details
    $sql_update_job_role = "UPDATE JobRole 
                            SET JobRoleName = '$job_role_name' 
                            WHERE JobRoleID = '$job_role_id'";

    if ($conn->query($sql_update_job_role) === TRUE) {
        echo "Job role details updated successfully.<br>";
    } else {
        echo "Error updating job role details: " . $conn->error;
    }

    // Update employee's job role assignment
    $sql_update_employee_job_role = "UPDATE EmployeeJobRole 
                                     SET JobRoleID = '$job_role_id'
                                     WHERE EmployeeID = '$employee_id'";

    if ($conn->query($sql_update_employee_job_role) === TRUE) {
        echo "Employee job role assignment updated successfully.<br>";
    } else {
        echo "Error updating employee job role assignment: " . $conn->error;
    }
    
}

$conn->close();
?>
