<?php
function getAllTracks(){
  $conn = connect();
    $stmt = $conn->prepare("SELECT TrackId,Name,UnitPrice FROM Track");
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $rawResult = $stmt->fetchAll();

  return $rawResult;
    $conn = null;
}
function getTrackInfo($id) {
  $conn = connect();
    try {
        $sql = "SELECT Name,UnitPrice,TrackId FROM Track WHERE TrackId = :trackId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':trackId', $id);
        $stmt->execute();
        $info = $stmt->fetch(PDO::FETCH_ASSOC);
    // si el email existe devuelve el nombre completo
    // si el email no existe devuelve NULL;
        return $info ?? null;
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
function getTrackPrice($id) {
  $conn = connect();
    try {
        $sql = "SELECT UnitPrice FROM Track WHERE TrackId = :trackId";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':trackId', $id);
        $stmt->execute();
        $price = $stmt->fetchColumn();
    // si el email existe devuelve el nombre completo
    // si el email no existe devuelve NULL;
        return $price ?? null;
    } catch(PDOException $e) {
        echo "Error extracting user data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
