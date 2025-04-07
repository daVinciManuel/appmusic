<?php
function getInvoiceId() {
  $conn = connect();
    try {
        $sql = "SELECT InvoiceId FROM Invoice ORDER BY InvoiceId DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $order = $stmt->fetchColumn();
        return $order+1 ?? null;
    } catch(PDOException $e) {
        echo "Error extracting invoice data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
function getInvoiceLineId() {
  $conn = connect();
    try {
        $sql = "SELECT InvoiceLineId FROM InvoiceLine ORDER BY InvoiceLineId DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $id = $stmt->fetchColumn();
        return $id+1 ?? null;
    } catch(PDOException $e) {
        echo "Error extracting invoice data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
function getInvoices($userId) {
  $conn = connect();
    try {
        $sql = "SELECT InvoiceId, InvoiceDate, Total FROM Invoice WHERE CustomerId=:userId ORDER BY InvoiceDate Desc";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':userId', $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $rawResult = $stmt->fetchAll();
        return $rawResult ?? null;
    } catch(PDOException $e) {
        echo "Error extracting Invoice data: " . $e->getMessage();
        return null;
    }
  $conn = null;
}
