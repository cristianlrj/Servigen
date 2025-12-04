<?php
define('APP_ROOT', dirname(__DIR__));
define('BASE_URL', 'http://localhost/servigen'); //RUTA DEL PROYECTO
define('API_URL', 'http://api.uptos.edu.ve/1.7.7/directory/search_person.json');
define('API_TOKEN', '123');
define('DB_HOST', 'localhost');
define('DB_NAME', 'servigen');
define('DB_USER', 'root');
define('DB_PASS', '');
define('APP_NAME', 'SERVIGEN');

// --- Configuración de PHPMailer ---
define('MAIL_HOST', 'smtp.gmail.com'); // Ej: smtp.gmail.com o el de tu hosting
define('MAIL_USERNAME', 'cristianrojasjimenez2003@gmail.com');
define('MAIL_PASSWORD', 'ofwcffpxxfknucbd');
define('MAIL_PORT', 587); // 587 para TLS, 465 para SSL
define('MAIL_ENCRYPTION', 'tls'); // 'tls' o 'ssl'
define('MAIL_FROM_ADDRESS', 'no-reply@servigen.com');
define('MAIL_FROM_NAME', 'SERVIGEN Notificaciones');

ini_set('display_errors',1);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);