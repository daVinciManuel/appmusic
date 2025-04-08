<?php
function insertInvoice($InvoiceId, $CustomerId, $InvoiceDate, $BillingAddress, $BillingCity, $BillingState, $BillingCountry, $BillingPostalCode, $Total)
{
    $insertDone = false;
    $conn = connect();
    try {
        $conn->beginTransaction();
        $query = "INSERT INTO Invoice(InvoiceId, CustomerId, InvoiceDate, BillingAddress, BillingCity, BillingState, BillingCountry, BillingPostalCode, Total)
                            VALUES (:InvoiceId, :CustomerId, :InvoiceDate, :BillingAddress, :BillingCity, :BillingState, :BillingCountry, :BillingPostalCode, :Total)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':InvoiceId', $InvoiceId);
        $stmt->bindParam(':CustomerId', $CustomerId);
        $stmt->bindParam(':InvoiceDate', $InvoiceDate);
        $stmt->bindParam(':BillingAddress', $BillingAddress);
        $stmt->bindParam(':BillingCity', $BillingCity);
        $stmt->bindParam(':BillingState', $BillingState);
        $stmt->bindParam(':BillingCountry', $BillingCountry);
        $stmt->bindParam(':BillingPostalCode', $BillingPostalCode);
        $stmt->bindParam(':Total', $Total);
        $stmt->execute();
        $conn->commit();
    $insertDone = true;
    } catch (PDOException $e) {
        $conn->rollback();
        die($e->getMessage());
    }
    $conn = null;
    return $insertDone;
}
 
 



function insertInvoiceLine($InvoiceLineId, $InvoiceId, $TrackId, $UnitPrice,$Quantity)
{
    $insertDone = false;
    $conn = connect();
    try {
    // $Quantity = 1;
        $conn->beginTransaction();
        $query = "INSERT INTO InvoiceLine(InvoiceLineId, InvoiceId, TrackId, UnitPrice, Quantity)
                            VALUES (:InvoiceLineId, :InvoiceId, :TrackId, :UnitPrice, :Quantity)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':InvoiceLineId', $InvoiceLineId);
        $stmt->bindParam(':InvoiceId', $InvoiceId);
        $stmt->bindParam(':TrackId', $TrackId);
        $stmt->bindParam(':UnitPrice', $UnitPrice);
        $stmt->bindParam(':Quantity', $Quantity);
        $stmt->execute();
        $conn->commit();
    $insertDone = true;
    } catch (PDOException $e) {
        $conn->rollback();
        die($e->getMessage());
    }
    $conn = null;
    return $insertDone;

}
