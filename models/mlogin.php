<?php
function getUserPass($email) {
  $conn = connect();
    try {
        $sql = "SELECT LastName FROM Customer WHERE Email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        return $result;
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
// esta funcion la uso para ver que un email existe
function getUserFullname($email) {
  $conn = connect();
    try {
        $sql = "SELECT FirstName,LastName FROM Customer WHERE Email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
    // si el email existe devuelve el nombre completo
    // si el email no existe devuelve NULL;
        $fullname = $info ? $info['FirstName'] . ' ' . $info['LastName'] : null;
        return $fullname;
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
