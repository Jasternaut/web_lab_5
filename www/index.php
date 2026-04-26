<?php 
session_start(); 
require_once 'UserInfo.php';
$uInfo = UserInfo::getInfo();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Service — Dashboard</title>
    <style>
        :root {
            --bg: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #2d3436;
            --text-secondary: #636e72;
            --accent: #00b894;
            --border: #dfe6e9;
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
            max-width: 800px;
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

        /* карточки */
        .card {
        background: var(--card-bg);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);

        /* Плавное изменение высоты */
        transition: all 0.3s ease-in-out; 
        height: auto; 
        overflow: hidden;
        }   

        .card-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            margin-bottom: 16px;
            display: block;
        }

        /* секция рецепта */
        #recipe-content {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            /* Анимация появления контента */
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 600px) {
            #recipe-content {
                flex-direction: column;
            }
            #recipe-content img {
                width: 100%;
                height: auto;
            }
        }

        #recipe-content img {
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .recipe-text h4 { margin: 0 0 10px 0; font-size: 1.2rem; }
        .recipe-text p { font-size: 0.95rem; color: var(--text-secondary); margin: 0; }

        /* кнопки */
        .btn {
            display: inline-block;
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: opacity 0.2s;
            margin-top: 20px;
        }

        .btn:hover { opacity: 0.9; }
        .btn:disabled { background: var(--border); cursor: not-allowed; }

        /* системная информация */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            font-size: 0.85rem;
        }

        .info-item b { color: var(--text-secondary); font-weight: 500; }

        /* ошибки */
        .error-list {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 15px;
            border-radius: 8px;
            list-style: none;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h2>Food Service</h2>
        <nav class="nav-links">
            <a href="form.html">+ Новый заказ</a>
            <a href="view.php">История</a>
        </nav>
    </header>

    <?php if(isset($_SESSION['errors'])): ?>
        <ul class="error-list">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <li>— <?= $error ?></li>
            <?php endforeach; unset($_SESSION['errors']); ?>
        </ul>
    <?php endif; ?>

    <div class="card">
        <span class="card-title">Информация о сессии пользователя</span>
        <div class="info-grid">
            <div class="info-item"><b>IP Адрес:</b> <?= htmlspecialchars($uInfo['ip']) ?></div>
            <div class="info-item"><b>Текущее время:</b> <?= $uInfo['time'] ?></div>
            <?php if(isset($_COOKIE['last_order_time'])): ?>
                <div class="info-item"><b>Последняя активность:</b> <?= $_COOKIE['last_order_time'] ?></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <span class="card-title">Рекомендованный рецепт</span>
        <div id="recipe-content">
            <?php if (isset($_SESSION['api_data']['meals'][0])): 
                $meal = $_SESSION['api_data']['meals'][0]; ?>
                <img src="<?= $meal['strMealThumb'] ?>" width="120" height="120" alt="Meal">
                <div class="recipe-text">
                    <h4><?= $meal['strMeal'] ?></h4>
                    <p><?= mb_strimwidth($meal['strInstructions'], 0, 180, "...") ?></p>
                </div>
            <?php else: ?>
                <p>Нет данных. Нажмите кнопку ниже для загрузки.</p>
            <?php endif; ?>
        </div>
        <button id="refresh-api" class="btn">Обновить рецепт</button>
    </div>

    <?php if(isset($_SESSION['last_order']) || isset($_COOKIE['user_name'])): ?>
    <div class="card">
        <span class="card-title">Последняя активность</span>
        <?php if(isset($_COOKIE['user_name'])): ?>
            <p style="margin-bottom: 10px;">С возвращением, <b><?= htmlspecialchars($_COOKIE['user_name']) ?></b>!</p>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['last_order'])): ?>
            <div class="info-grid">
                <div class="info-item"><b>Ресторан:</b> <?= $_SESSION['last_order']['restaurant'] ?></div>
                <div class="info-item"><b>Количество:</b> <?= $_SESSION['last_order']['quantity'] ?></div>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<script>
    document.getElementById('refresh-api').addEventListener('click', function() {
    const btn = this;
    const content = document.getElementById('recipe-content');
    
    btn.innerText = 'Загрузка...';
    btn.disabled = true;
    
    // Легкое затухание перед обновлением
    content.style.opacity = '0.3';

    fetch('refresh_api.php')
        .then(response => response.json())
        .then(data => {
            if(data.meals) {
                const meal = data.meals[0];
                
                // Обновляем HTML
                content.innerHTML = `
                    <img src="${meal.strMealThumb}" width="120" height="120" alt="${meal.strMeal}">
                    <div class="recipe-text">
                        <h4>${meal.strMeal}</h4>
                        <p>${meal.strInstructions}</p> 
                    </div>
                `;
                // Убрал substring(0, 180), чтобы контент был полным и высота росла
            }
        })
        .catch(err => {
            console.error('Error:', err);
            content.innerHTML = '<p>Не удалось загрузить рецепт.</p>';
        })
        .finally(() => {
            btn.innerText = 'Обновить рецепт';
            btn.disabled = false;
            content.style.opacity = '1'; // Возвращаем видимость
        });
    });
</script>

</body>
</html>