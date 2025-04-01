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
