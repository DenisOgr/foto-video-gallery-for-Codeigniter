Foto-Video Gallery for Codeigniter by DenisOg

Тестировал для версии CI 2.1.0

Foto-Video Gallery for Codeigniter by DenisOg  загружает, обрабатывает и выводит фото и видео контент. 
Foto-Video Gallery for Codeigniter by DenisOg   работает с jpg, jpeg,  gif, png форматами изображений. 
Foto-Video Gallery for Codeigniter by DenisOg  работает с  ссылки на видео ролики с http://www.youtube.com и http://vimeo.com/.  Пример ссылки: http://vimeo.com/35572658
Foto-Video Gallery for Codeigniter by DenisOg используте следующие готовые библиотеки:
-  prettyphoto-jquery-lightbox (http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/ )
-  Multi_upload (http://code-igniter.ru/wiki/Multi_upload )
Автор
Порпленко Денис.
denis.porplenko@gmail.com
  

Для установки необходимо
1.	Распакуйте архив.
2.	Скопировать  папки из Foto-Video Gallery for Codeigniter by DenisOg  в  CodeIgniter  
a.	Скопируйте каталог application/ controllers / в каталог application/ controllers / вашего приложения CodeIgniter  
b.	Скопируйте каталог application/ models/ в каталог application/ models/ вашего приложения CodeIgniter  
c.	Скопируйте каталог application/ views/ в каталог application/ views/ вашего приложения CodeIgniter  
d.	Скопируйте каталог application/ language/ в каталог application/ language/ вашего приложения CodeIgniter  
e.	Скопируйте файл  application/ config/form_validation.php  в каталог application/ config/form_validation.php   вашего приложения CodeIgniter  
f.	Скопируйте файл  application/ config/gallery.php  в каталог application/ config/ gallery.php   вашего приложения CodeIgniter  
g.	Скопируйте файл  application/ libraries / display_lib.php  в каталог application/ libraries / display_lib.php  вашего приложения CodeIgniter  
h.	Скопируйте папку  /css/ в каталог корневой каталог вашего приложения CodeIgniter  
i.	Скопируйте папку  /js/ в каталог корневой каталог вашего приложения CodeIgniter  
j.	Скопируйте папку  /i/ в каталог корневой каталог вашего приложения CodeIgniter  
k.	Скопируйте папку  /upload/ в каталог корневой каталог вашего приложения CodeIgniter/. Сделайте этот каталог доступным для записи веб-сервером.  
l.	Скопируйте папку  /css/ в каталог корневой каталог вашего приложения CodeIgniter  
3.	Установить настройки:
•	В класс Upload добавить функцию system/libraries/ Upload (только одна функция).php .Не копировать файл, только добавить одну функцию.
•	Подключить все классы и настройки, что были скопированы ?. Для нормальной работы должны подключены следующие классы и настройки:
 libraries : 'display_lib', 'database','form_validation'
  helper:  'url'
   language:'gallery_msg'
  model: 'gallery_model'
   config: gallery
•	Настроить размеры картинок для галереи можно в файле /config/gallery
•	Установить константу  define('DROOT', $_SERVER["DOCUMENT_ROOT"].'/');  
•	В  файле /js/script.js прописать адрес сайта var base_url (как и  config`е значение base_url   )

Все :)

Возможные проблемы:
- проверте htaccess файл. если не возможно достучаться до какого нибудь файла
- у папки  upload и входящих в нее должны быть права на запись 

Будут вопросы пишите на   denis.porplenko@gmail.com стучитесь: 444699163 звоните на (skype):denisog1                                       

