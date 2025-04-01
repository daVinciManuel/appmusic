<?php
function getUserData($CustomerId) {
  $conn = connect();
    try {
        $sql = "SELECT BillingAddress,BillingCity,BillingState,BillingCountry,BillingPostalCode FROM Customer WHERE CustomerId = :CustomerId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':CustomerId', $CustomerId);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
    // si el email existe devuelve el nombre completo
    // si el email no existe devuelve NULL;
        return $info;
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
