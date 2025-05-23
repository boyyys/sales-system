<?php include 'config/db.php'; ?>
<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Penjualan -->
    <div class="bg-blue-100 p-6 rounded-lg">
        <h3 class="text-xl font-bold mb-2">Total Penjualan</h3>
        <p class="text-3xl">
            $<?= number_format($pdo->query("SELECT SUM(total_amount) FROM sales")->fetchColumn(), 2) ?>
        </p>
    </div>

    <!-- Total Profit -->
    <div class="bg-green-100 p-6 rounded-lg">
        <h3 class="text-xl font-bold mb-2">Total Profit</h3>
        <p class="text-3xl">
            $<?= number_format($pdo->query("SELECT SUM(profit) FROM sales")->fetchColumn(), 2) ?>
        </p>
    </div>

    <!-- Produk Terlaris -->
    <div class="bg-yellow-100 p-6 rounded-lg">
        <h3 class="text-xl font-bold mb-2">Produk Terlaris</h3>
        <p class="text-3xl">
            <?php
            $bestSeller = $pdo->query(
                "SELECT p.name, SUM(si.quantity) as total 
        FROM sale_items si 
        JOIN products p ON si.product_id = p.id 
        GROUP BY p.id 
        ORDER BY total DESC 
        LIMIT 1"
            )->fetch();
            echo htmlspecialchars($bestSeller['name']) ?? '-';
            ?>
        </p>
    </div>
</div>

<!-- Grafik Profit -->
<canvas id="profitChart" class="bg-white p-6 rounded-lg shadow"></canvas>

<script>
    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($salesData, 'date')) ?>,
            datasets: [{
                label: 'Profit Harian',
                data: <?= json_encode(array_column($salesData, 'profit')) ?>,
                borderColor: '#10B981',
                tension: 0.1
            }]
        }
    });
</script>