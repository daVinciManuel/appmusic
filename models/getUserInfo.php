<?php
function getUserInfo($email) {
  $conn = connect();
    try {
        $sql = "SELECT CustomerId,FirstName,LastName,Email FROM Customer WHERE Email = :email";
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
