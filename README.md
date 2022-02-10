# giffer

Параметры запуска утилиты:

-w, --width - ширина изображения в пикселях (целочисленное значение);
-h, --height - высота изображения в пикселях (целочисленное значение);
-m, --mark - путь к файлу-картинке, которая используется в качестве водяного знака (строка);
--src - путь к исходному файлу для обработки (строка);
--dest - путь к выходному файлу (строка).

Если при запуске утилиты ширина и высота указаны как 0, то размер исходного обрабатываемого файла не изменяется;
Водяной знак должен быть формата PNG;
Водяной знак устанавливается по центру исходного обрабатываемого файла;
Во время обработки файла создается временная директория "../img/temp", в которую записываются обработанные фреймы исходного файла;
После завершения ообработки, директория "temp" и все созданные в ней фреймы удаляются;

Пример запуска утилиты:

php giffer.php -w 0 -h 400 -m "../img/watermark.png" --src "../img/source.gif" --dest "../img/mark-image.gif"

