<?php

$grouped = [];

foreach ($invoices as $row) {

    $invoiceId = $row['invoice_id'];
    if (!isset($grouped[$invoiceId])) {
        $grouped[$invoiceId] = [
            'invoice_date' => $row['invoice_date'],
            'customer_name' => $row['customer_name'],
            'customer_address' => $row['customer_address'],
            'items' => []
        ];
    }

    $grouped[$invoiceId]['items'][] = [
        'product_name' => $row['product_name'],
        'category_name' => $row['category_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}

foreach ($grouped as $invoiceId => $invoice) {
    echo json_encode($invoice, JSON_PRETTY_PRINT);

    echo "<h3>Invoice #{$invoiceId}</h3>";
    echo "<p>Date: {$invoice['invoice_date']}<br>";
    echo "Customer: {$invoice['customer_name']}<br>";
    echo "Address: {$invoice['customer_address']}</p>";

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<thead><tr><th>Product</th><th>Category</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead><tbody>";
    $grandTotal = 0;
    foreach ($invoice['items'] as $item) {
        $total = $item['quantity'] * $item['price'];
        $grandTotal += $total;
        echo "<tr>
                <td>{$item['product_name']}</td>
                  <td>{$item['category_name']}</td>
                <td>{$item['quantity']}</td>
                <td>{$item['price']}</td>
                <td>{$total}</td>
              </tr>";
    }
    echo "<tr><td colspan='3'><strong>Grand Total</strong></td><td><strong>{$grandTotal}</strong></td></tr>";
    echo "</tbody></table><br><hr><br>";
}
