<?php
function getUserInfo($email) {
  $conn = connect();
    try {
        $sql = "SELECT ".DATABASE_TABLE_USER_QUERY_COLUMNS." FROM ".DATABASE_TABLE_USER." WHERE Email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
