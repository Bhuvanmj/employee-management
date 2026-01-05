<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $employee_id    = $_POST['employee_id'];
    $department_id  = $_POST['department_id'];
    $job_role_id    = $_POST['job_role_id'];
    $bank_detail_id = $_POST['bank_details_id'];
    $confirm_action = $_POST['confirm_action'];

    if ($confirm_action !== "delete") {
        echo "<h2>Action not confirmed. No records deleted.</h2>";
        exit;
    }

    try {
        $conn->beginTransaction();

        // 1️⃣ Remove employee from department
        $stmt = $conn->prepare("
            DELETE FROM employeedepartment
            WHERE employeeid = ? AND departmentid = ?
        ");
        $stmt->execute([$employee_id, $department_id]);

        // 2️⃣ Remove employee job role
        $stmt = $conn->prepare("
            DELETE FROM employeejobrole
            WHERE employeeid = ? AND jobroleid = ?
        ");
        $stmt->execute([$employee_id, $job_role_id]);

        // 3️⃣ Remove bank details
        $stmt = $conn->prepare("
            DELETE FROM bankdetails
            WHERE employeeid = ? AND bankdetailid = ?
        ");
        $stmt->execute([$employee_id, $bank_detail_id]);

        // 4️⃣ Remove salary (if exists)
        $stmt = $conn->prepare("
            DELETE FROM salary
            WHERE employeeid = ?
        ");
        $stmt->execute([$employee_id]);

        // 5️⃣ Finally remove employee
        $stmt = $conn->prepare("
            DELETE FROM employee
            WHERE employeeid = ?
        ");
        $stmt->execute([$employee_id]);

        $conn->commit();
        echo "<h2>Employee record and related entries deleted successfully.</h2>";

    } catch (Exception $e) {
        $conn->rollBack();
        echo "<h2>Error deleting records: " . $e->getMessage() . "</h2>";
    }
}
