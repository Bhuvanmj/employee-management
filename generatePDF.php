<?php
require 'dompdf-3.0.1/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

require_once "db.php";

// Fetch all employee data
$sql = "
    SELECT 
        e.employeeid,
        e.firstname,
        e.lastname,
        e.gender,
        e.birthdate,
        e.contactinfo,
        e.address,
        d.departmentname,
        j.jobrolename,
        s.basicsalary,
        s.allowances,
        b.bankname,
        b.accountnumber,
        l.leavetype,
        l.leavestartdate,
        l.leaveenddate,
        l.status
    FROM employee e
    LEFT JOIN employeedepartment ed ON e.employeeid = ed.employeeid
    LEFT JOIN department d ON ed.departmentid = d.departmentid
    LEFT JOIN employeejobrole ej ON e.employeeid = ej.employeeid
    LEFT JOIN jobrole j ON ej.jobroleid = j.jobroleid
    LEFT JOIN salary s ON e.employeeid = s.employeeid
    LEFT JOIN bankdetails b ON e.employeeid = b.employeeid
    LEFT JOIN leavemanagement l ON e.employeeid = l.employeeid
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build HTML
$html = '<h2>All Employee Details</h2>';
$html .= '<table border="1" width="100%" cellspacing="0" cellpadding="5">
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
    <th>Leave Start</th>
    <th>Leave End</th>
    <th>Status</th>
</tr>';

if (count($employees) > 0) {
    foreach ($employees as $emp) {
        $html .= "<tr>
            <td>{$emp['employeeid']}</td>
            <td>{$emp['firstname']}</td>
            <td>{$emp['lastname']}</td>
            <td>{$emp['gender']}</td>
            <td>{$emp['birthdate']}</td>
            <td>{$emp['contactinfo']}</td>
            <td>{$emp['address']}</td>
            <td>{$emp['departmentname']}</td>
            <td>{$emp['jobrolename']}</td>
            <td>{$emp['basicsalary']}</td>
            <td>{$emp['allowances']}</td>
            <td>{$emp['bankname']}</td>
            <td>{$emp['accountnumber']}</td>
            <td>{$emp['leavetype']}</td>
            <td>{$emp['leavestartdate']}</td>
            <td>{$emp['leaveenddate']}</td>
            <td>{$emp['status']}</td>
        </tr>";
    }
} else {
    $html .= '<tr><td colspan="17">No employees found</td></tr>';
}

$html .= '</table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("EmployeeDetails.pdf", ["Attachment" => 1]);
