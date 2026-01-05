<?php
// Database connection
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root"; // Your database username
$password = "navuhb"; // Your database password
$dbname = "dbmsproject"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

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
    

    // Insert employee data
    $sql_employee = "INSERT INTO Employee (EmployeeID, FirstName, LastName, Gender, Birthdate, ContactInfo, Address) 
                     VALUES ('$employee_id', '$first_name', '$last_name', '$gender', '$dob', '$phone', '$address')";

    if ($conn->query($sql_employee) === TRUE) {
        echo "Employee details added successfully.<br>";
    } else {
        echo "Error: " . $sql_employee . "<br>" . $conn->error;
    }

    // Insert bank details
    $sql_bank = "INSERT INTO BankDetails (BankDetailID, EmployeeID, BankName, AccountNumber) 
                 VALUES ('$bank_details_id', '$employee_id', '$bank_name', '$account_number')";

    if ($conn->query($sql_bank) === TRUE) {
        echo "Bank details added successfully.<br>";
    } else {
        echo "Error: " . $sql_bank . "<br>" . $conn->error;
    }

    // Insert department details
    $sql_department = "INSERT INTO Department (DepartmentID, DepartmentName) 
                       VALUES ('$department_id', '$department_name')";

    if ($conn->query($sql_department) === TRUE) {
        echo "Department details added successfully.<br>";
    } else {
        echo "Error: " . $sql_department . "<br>" . $conn->error;
    }

    // Assign employee to the department
    $sql_employee_department = "INSERT INTO EmployeeDepartment (EmployeeID, DepartmentID) 
                                 VALUES ('$employee_id', '$department_id')";

    if ($conn->query($sql_employee_department) === TRUE) {
        echo "Employee assigned to department successfully.<br>";
    } else {
        echo "Error: " . $sql_employee_department . "<br>" . $conn->error;
    }

    /*// Assign employee to the job role
    $sql_employee_jobrole = "INSERT INTO EmployeeJobRole (EmployeeID, JobRoleID) 
                             VALUES ('$employee_id', '$job_role_id')";

    if ($conn->query($sql_employee_jobrole) === TRUE) {
        echo "Employee assigned to job role successfully.<br>";
    } else {
        echo "Error: " . $sql_employee_jobrole . "<br>" . $conn->error;
    }
*/
     
}

$conn->close();
?>