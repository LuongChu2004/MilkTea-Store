<?php
require_once('config.php');

// DEBUG mode
defined('DEBUG') || define('DEBUG', true);

// Kết nối database
function getDbConnection() {
    $conn = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);
    if ($conn->connect_error) {
        die("❌ Kết nối DB thất bại: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");
    return $conn;
}

// Ghi log SQL lỗi
function handleSqlError($con, $sql, $function_name) {
    $error_msg = "SQL Error in {$function_name}(): " . mysqli_error($con) . " | SQL: " . $sql;
    error_log($error_msg);
    if (DEBUG) {
        echo "<pre>Database Error: " . mysqli_error($con) . "<br>SQL: " . $sql . "</pre>";
    } else {
        echo "Database Error occurred. Please check the logs.";
    }
}

// Thực thi query và trả về số dòng ảnh hưởng
function executeWithAffectedRows($sql) {
    $con = getDbConnection();
    $result = mysqli_query($con, $sql);
    if (!$result) {
        handleSqlError($con, $sql, 'executeWithAffectedRows');
        mysqli_close($con);
        return false;
    }
    $affected_rows = mysqli_affected_rows($con);
    mysqli_close($con);
    return $affected_rows;
}

// Thực thi query INSERT/UPDATE/DELETE hoặc query đơn giản
function execute($sql, $params = []) {
    $con = getDbConnection();
    $result = false;

    if (!empty($params)) {
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            handleSqlError($con, $sql, 'execute - prepare');
            mysqli_close($con);
            return false;
        }
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        $result = mysqli_stmt_execute($stmt);
        if (!$result) handleSqlError($con, $sql, 'execute - execute');
        mysqli_stmt_close($stmt);
    } else {
        $result = mysqli_query($con, $sql);
        if (!$result) handleSqlError($con, $sql, 'execute');
    }

    mysqli_close($con);
    return $result;
}

// Thực thi query SELECT và trả về mảng kết quả
function executeResult($sql, $params = []) {
    $con = getDbConnection();
    $data = [];

    if (!empty($params)) {
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            handleSqlError($con, $sql, 'executeResult - prepare');
            mysqli_close($con);
            return $data;
        }
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        if (!mysqli_stmt_execute($stmt)) {
            handleSqlError($con, $sql, 'executeResult - execute');
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return $data;
        }

        $result = mysqli_stmt_get_result($stmt);
        if (!$result) {
            handleSqlError($con, $sql, 'executeResult - get_result');
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return $data;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        mysqli_free_result($result);
        mysqli_stmt_close($stmt);
    } else {
        $result = mysqli_query($con, $sql);
        if (!$result) {
            handleSqlError($con, $sql, 'executeResult');
            mysqli_close($con);
            return $data;
        }
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        mysqli_free_result($result);
    }

    mysqli_close($con);
    return $data;
}
function executeSingleResult($sql, $params = []) {
    $con = getDbConnection();
    mysqli_set_charset($con, "utf8");

    $row = null;

    if (!empty($params)) {
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            handleSqlError($con, $sql, 'executeSingleResult - prepare');
            mysqli_close($con);
            return null;
        }

        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        if (!mysqli_stmt_execute($stmt)) {
            handleSqlError($con, $sql, 'executeSingleResult - execute');
            mysqli_stmt_close($stmt);
            mysqli_close($con);
            return null;
        }

        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $row = mysqli_fetch_assoc($result) ?: null;
            mysqli_free_result($result);
        } else {
            handleSqlError($con, $sql, 'executeSingleResult - get_result');
        }

        mysqli_stmt_close($stmt);
    } else {
        $result = mysqli_query($con, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result) ?: null;
            mysqli_free_result($result);
        } else {
            handleSqlError($con, $sql, 'executeSingleResult');
        }
    }

    mysqli_close($con);
    return $row;
}

function executeInsert($sql, $params = []) {
    $con = getDbConnection();
    mysqli_set_charset($con, "utf8");

    $insertedId = null;

    if (!empty($params)) {
        $stmt = mysqli_prepare($con, $sql);
        if (!$stmt) {
            handleSqlError($con, $sql, 'executeInsert - prepare');
            mysqli_close($con);
            return null;
        }

        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);

        if (mysqli_stmt_execute($stmt)) {
            $insertedId = mysqli_insert_id($con);
        } else {
            handleSqlError($con, $sql, 'executeInsert - execute');
        }

        mysqli_stmt_close($stmt);
    } else {
        $result = mysqli_query($con, $sql);
        if ($result) {
            $insertedId = mysqli_insert_id($con);
        } else {
            handleSqlError($con, $sql, 'executeInsert');
        }
    }

    mysqli_close($con);
    return $insertedId;
}
?>