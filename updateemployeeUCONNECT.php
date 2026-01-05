<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $employee_id     = $_POST["employee_id"];
    $first_name      = $_POST["first_name"];
    $last_name       = $_POST["last_name"];
    $gender          = $_POST["gender"];
    $dob             = $_POST["dob"];
    $phone           = $_POST["phone"];
    $address         = $_POST["address"];
    $bank_details_id = $_POST["bank_details_id"];
    $bank_name       = $_POST["bank_name"];
    $account_number  = $_POST["account_number"];
    $department_id   = $_POST["department_id"];
    $department_name = $_POST["department_name"];
    $job_role_id     = $_POST["job_role_id"];
    $job_role_name   = $_POST["job_role_name"];

    try {
        /* Update Employee */
        $stmt = $conn->prepare("
            UPDATE employee
            SET firstname = ?, lastname = ?, gender = ?, birthdate = ?, contactinfo = ?, address = ?
            WHERE employeeid = ?
        ");
        $stmt->execute([
            $first_name, $last_name, $gender, $dob, $phone, $address, $employee_id
        ]);

        /* Update Bank Details */
        $stmt = $conn->prepare("
            UPDATE bankdetails
            SET bankname = ?, accountnumber = ?
            WHERE bankdetailid = ?
        ");
        $stmt->execute([$bank_name, $account_number, $bank_details_id]);

        /* Update Department */
        $stmt = $conn->prepare("
            UPDATE department
            SET departmentname = ?
            WHERE departmentid = ?
        ");
        $stmt->execute([$department_name, $department_id]);

        /* Update Job Role */
        $stmt = $conn->prepare("
            UPDATE jobrole
            SET jobrolename = ?
            WHERE jobroleid = ?
        ");
        $stmt->execute([$job_role_name, $job_role_id]);

        /* Update Employee ↔ JobRole mapping */
        $stmt = $conn->prepare("
            UPDATE employeejobrole
            SET jobroleid = ?
            WHERE employeeid = ?
        ");
        $stmt->execute([$job_role_id, $employee_id]);

        echo "<h2>Employee details updated successfully</h2>";

    } catch (PDOException $e) {
        echo "<h2>Error updating employee</h2>";
        echo $e->getMessage();
    }
}
