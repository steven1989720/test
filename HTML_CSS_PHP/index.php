<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тест</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js"></script>
</head>

<body style="background: #333; color:#FFF; margin:0px;">
    <div style="position: relative; padding:40px;">
        <h1>Тестовая задача</h1>
        <div id="content">
            <h2>HTML + CSS</h2>
            <p>Измените подключенный style.css так чтобы .grid находился в середине страницы, имел максимальную ширину 1400px</p>
            <p>Все .item должны быть решёткой в 4 колонки, занимать всю ширину .grid, иметь пропорции 4x3 и <b>уменьшаться пропорционально</b> при сжатии страницы. Отступ между блоками - 20px</p>
            <p>Минимальная ширина .item - 250px, при достижении этого предела количество колонок уменьшается, а .items снова занимают всю ширину.</p>
            <p>Если ширина страницы становится менее 800px все .item должны стать списком.</p>
            <div style="position: relative; width:250px; background:#CCC; color:#930; text-align:center;">250px</div>

            <br><br>

            <div class="grid">
                <div class="item">
                    <div class="div"></div>
                </div>
                <div class="item">
                    <div class="div"></div>
                </div>
                <div class="item">
                    <div class="div"></div>
                </div>
                <div class="item">
                    <div class="div">
                        <img src="img/1.jpg">
                    </div>
                </div>
                <div class="item">
                    <div class="div">
                        <img src="img/2.jpg">
                    </div>
                </div>
                <div class="item">
                    <div class="div">
                        <img src="img/3.jpg">
                    </div>
                </div>
                <div class="item">
                    <div class="div">
                        <img src="img/4.jpg">
                    </div>
                </div>
            </div>

            <br><br>

            <p>Пример - как должно получиться.</p>
            <div style="position: relative;">
                <img src="img/example.gif" style="max-width: 600px; box-shadow: 0 0 0 3px #930;" alt="gif">
            </div>

            <br><br><br><br>

            <h1>PHP + JQuery</h1>
            <p>В файле script.js в функции Get() продемонстрируйте как отправить post запрос к файлу get.php c передачей произвольного массива так чтобы в get.php сработало условие получения данных.</p>
            <p>В файле get.php покажите как правильно заполнить запрос к БД MySQL и вернуть данные в JSON формате.</p>

            <br><br>

            <div style="position: relative;">
                <input type="button" value="Push me" onclick="Get()" style="padding: 12px 24px; font-size: 18px;">
                <br><br>
                <div class="getback" style="position: relative; padding: 10px; background:#CCC; color:#000; box-shadow: 0 0 0 1px #999;">Выведите сюда данные полученные из get.php в JSON.stringify()</div>
            </div>

            <br><br><br><br>

            <p>Это всё.</p>
        </div>

    </div>
</body>

</html>