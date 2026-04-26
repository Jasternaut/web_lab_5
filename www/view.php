<?php
// view.php
require_once 'OrderRepository.php';
$repo = new OrderRepository();

// обработка фильтра (если в URL есть ?filter=large)
$showLarge = isset($_GET['filter']) && $_GET['filter'] === 'large';
$orders = $showLarge ? $repo->getLargeOrders(3) : $repo->getAllSorted();

$totalCount = $repo->getCount();
$totalItems = $repo->getTotalQuantity();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Service — История заказов</title>
    <style>
        :root {
            --bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #2d3436;
            --text-secondary: #636e72;
            --accent: #00b894;
            --border: #dfe6e9;
            --row-hover: #f1f3f5;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            line-height: 1.6;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border);
        }

        h2 { font-weight: 700; margin: 0; font-size: 1.5rem; letter-spacing: -0.5px; }

        .nav-links a {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-left: 20px;
            transition: color 0.2s;
        }

        .nav-links a:hover { color: var(--accent); }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0; /* Убираем внутренний отступ для таблицы */
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }

        th {
            background-color: #fafbfc;
            text-align: left;
            padding: 16px 24px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
        }

        tr:last-child td { border-bottom: none; }

        tr:hover td { background-color: var(--row-hover); }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
            background: #e9ecef;
            color: #495057;
        }

        .badge-online {
            background: #e6fffa;
            color: #087f5b;
        }

        .btn-back {
        display: inline-block;
        padding: 10px 20px;
        background-color: #636e72;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
        }
        .btn-back:hover {
            background-color: #2d3436;
        }

        .empty-state {
            padding: 40px;
            text-align: center;
            color: var(--text-secondary);
        }
    </style>
</head>
<body>

<div class="container">
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1>История заказов</h1>
        <a href="index.php" class="btn-back">На главную</a>
    </header>

    <div style="background: #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <b>Статистика:</b> 
        Всего заказов: <?php echo $totalCount; ?> | 
        Всего блюд заказано: <?php echo $totalItems; ?>
    </div>

    <div style="margin-bottom: 15px;">
        <a href="view.php" class="btn">Все заказы</a>
        <a href="view.php?filter=large" class="btn">Только крупные (от 3 блюд)</a>
    </div>

    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Имя</th>
                <th>Кол-во</th>
                <th>Ресторан</th>
                <th>Упаковка</th>
                <th>Онлайн</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td><?php echo $order['quantity']; ?></td>
                <td><?php echo htmlspecialchars($order['restaurant']); ?></td>
                <td><?php echo $order['packaging']; ?></td>
                <td><?php echo $order['online_pay'] ? '✅' : '❌'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>