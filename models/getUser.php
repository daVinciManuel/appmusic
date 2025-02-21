<?php
function getUserInfo($email) {
  $conn = connect();
    try {
        $sql = "SELECT * FROM customers WHERE Email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Error en la obtencion de los datos del usuario: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
