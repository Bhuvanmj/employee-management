<?php
require 'dompdf-3.0.1/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Database connection
$host     = getenv("DB_HOST");
$username = getenv("DB_USER");
$password = getenv("DB_PASS");
$database = getenv("DB_NAME");


$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all employee data along with department, bank details, and more
$sql = "
    SELECT 
        E.EmployeeID, E.FirstName, E.LastName, E.Gender, E.Birthdate, E.ContactInfo, E.Address,
        D.DepartmentName, 
        J.JobRoleName, 
        S.BasicSalary, S.Allowances, 
        B.BankName, B.AccountNumber,
        L.LeaveType, L.LeaveStartDate, L.LeaveEndDate, L.Status
    FROM 
        Employee E
    LEFT JOIN 
        EmployeeDepartment ED ON E.EmployeeID = ED.EmployeeID
    LEFT JOIN 
        Department D ON ED.DepartmentID = D.DepartmentID
    LEFT JOIN 
        EmployeeJobRole EJ ON E.EmployeeID = EJ.EmployeeID
    LEFT JOIN 
        JobRole J ON EJ.JobRoleID = J.JobRoleID
    LEFT JOIN 
        Salary S ON E.EmployeeID = S.EmployeeID
    LEFT JOIN 
        BankDetails B ON E.EmployeeID = B.EmployeeID
    LEFT JOIN 
        LeaveManagement L ON E.EmployeeID = L.EmployeeID
";

$result = $conn->query($sql);

$html = '<h2>All Employee Details:</h2>';
$html .= '<table border="1">
            <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Birthdate</th>
                <th>Contact Info</th>
                <th>Address</th>
                <th>Department</th>
                <th>Job Role</th>
                <th>Basic Salary</th>
                <th>Allowances</th>
                <th>Bank Name</th>
                <th>Account Number</th>
                <th>Leave Type</th>
                <th>Leave Start Date</th>
                <th>Leave End Date</th>
                <th>Leave Status</th>
            </tr>';

if ($result->num_rows > 0) {
    while ($employee = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . $employee['EmployeeID'] . '</td>
                    <td>' . $employee['FirstName'] . '</td>
                    <td>' . $employee['LastName'] . '</td>
                    <td>' . $employee['Gender'] . '</td>
                    <td>' . $employee['Birthdate'] . '</td>
                    <td>' . $employee['ContactInfo'] . '</td>
                    <td>' . $employee['Address'] . '</td>
                    <td>' . $employee['DepartmentName'] . '</td>
                    <td>' . $employee['JobRoleName'] . '</td>
                    <td>' . $employee['BasicSalary'] . '</td>
                    <td>' . $employee['Allowances'] . '</td>
                    <td>' . $employee['BankName'] . '</td>
                    <td>' . $employee['AccountNumber'] . '</td>
                    <td>' . $employee['LeaveType'] . '</td>
                    <td>' . $employee['LeaveStartDate'] . '</td>
                    <td>' . $employee['LeaveEndDate'] . '</td>
                    <td>' . $employee['Status'] . '</td>
                  </tr>';
    }
} else {
    $html .= '<tr><td colspan="17">No employees found.</td></tr>';
}

$html .= '</table>';

// Create an instance of Dompdf
$dompdf = new Dompdf();

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the PDF
$dompdf->render();

// Output the generated PDF to browser
$dompdf->stream('EmployeeDetails.pdf', array("Attachment" => 1));

$conn->close();
?>
