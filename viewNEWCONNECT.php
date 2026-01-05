<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php";

try {
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

    $stmt = $conn->query($sql);
    $employees = $stmt->fetchAll();

    if (count($employees) > 0) {
        echo "<h2>All Employee Details</h2>";
        echo "<table border='1'>
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
                </tr>";

        foreach ($employees as $emp) {
            echo "<tr>
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

        echo "</table>";
    } else {
        echo "<p>No employees found.</p>";
    }

} catch (PDOException $e) {
    echo "<h3>Error fetching employees</h3>";
    echo $e->getMessage();
}
