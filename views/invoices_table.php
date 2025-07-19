<?php if (!empty($invoices)): ?>

    <?php echo json_encode($invoices, JSON_PRETTY_PRINT); ?>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($invoices as $invoice): ?>
                <tr>
                    <td><?= htmlspecialchars($invoice['invoice_id']) ?></td>
                    <td><?= htmlspecialchars($invoice['invoice_date']) ?></td>
                    <td><?= htmlspecialchars($invoice['customer_name']) ?></td>
                    <td><?= htmlspecialchars($invoice['customer_address']) ?></td>
                    <td><?= htmlspecialchars($invoice['product_name']) ?></td>
                    <td><?= htmlspecialchars($invoice['quantity']) ?></td>
                    <td><?= htmlspecialchars($invoice['price']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No invoices found.</p>
<?php endif; ?>