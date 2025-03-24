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
