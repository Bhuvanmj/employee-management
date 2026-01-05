<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name      = $_POST["first_name"];
    $last_name       = $_POST["last_name"];
    $gender          = $_POST["gender"];
    $dob             = $_POST["dob"];
    $phone           = $_POST["phone"];
    $address         = $_POST["address"];
    $bank_name       = $_POST["bank_name"];
    $account_number  = $_POST["account_number"];
    $department_name = $_POST["department_name"];
    $job_role_id     = $_POST["job_role_id"];

    try {
        // start transaction
        $conn->beginTransaction();

        // 1. insert employee
        $stmt = $conn->prepare("
            INSERT INTO employee 
            (firstname, lastname, gender, birthdate, contactinfo, address)
            VALUES (?, ?, ?, ?, ?, ?)
            RETURNING employeeid
        ");
        $stmt->execute([
            $first_name,
            $last_name,
            $gender,
            $dob,
            $phone,
            $address
        ]);

        $employee_id = $stmt->fetchColumn();

        // 2. insert bank details
        $stmt = $conn->prepare("
            INSERT INTO bankdetails 
            (employeeid, bankname, accountnumber)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            $employee_id,
            $bank_name,
            $account_number
        ]);

        // 3. insert department if not exists
        $stmt = $conn->prepare("
            INSERT INTO department (departmentname)
            VALUES (?)
            ON CONFLICT (departmentname) DO NOTHING
        ");
        $stmt->execute([$department_name]);

        // 4. get department id
        $stmt = $conn->prepare("
            SELECT departmentid FROM department WHERE departmentname = ?
        ");
        $stmt->execute([$department_name]);
        $department_id = $stmt->fetchColumn();

        // 5. map employee to department
        $stmt = $conn->prepare("
            INSERT INTO employeedepartment (employeeid, departmentid)
            VALUES (?, ?)
        ");
        $stmt->execute([$employee_id, $department_id]);

        // 6. map job role (optional)
        if (!empty($job_role_id)) {
            $stmt = $conn->prepare("
                INSERT INTO employeejobrole (employeeid, jobroleid)
                VALUES (?, ?)
            ");
            $stmt->execute([$employee_id, $job_role_id]);
        }

        $conn->commit();
        echo "Employee added successfully.";

    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
