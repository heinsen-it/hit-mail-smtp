<?php
/*
Plugin Name: HIT Mailer SMTP
Plugin URI: https://heinsen-it.de/
Description: Kleines Plugin welches die Umstellung von WordPress auf SMTP durchführt.
Author: Heinsen-IT
Version: 1.0.0.0
Letztes Update: 2025-06-28 10:00:00
Author URI: https://heinsen-it.de/
MINIMAL WP: 6.4.0
MINIMAL PHP: 8.2.0
Tested WP: 6.7.1
*/

// Direktzugriff verhindern
if (!defined('ABSPATH')) {
    exit;
}

// Autoloader für Namespaces definieren
spl_autoload_register(function ($class) {
    // Basis-Namespace für das Plugin
    $namespace = 'HITMAILSMTP\\';

    // Prüfen, ob die angeforderte Klasse zu unserem Namespace gehört
    if (strpos($class, $namespace) !== 0) {
        return;
    }

    // Entferne den Plugin-Namespace
    $class = str_replace($namespace, '', $class);

    // Konvertiere Namespace-Trenner in Verzeichnis-Trenner
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Pfad zur Datei erstellen
    $file = plugin_dir_path(__FILE__) .  strtolower($class) . '.php';

    // Datei laden, wenn sie existiert
    if (file_exists($file)) {
        require_once $file;
    }
});

// Plugin-Konstanten definieren
define('HITMAILSMTP_VERSION', '1.0.0.0');
define('HITMAILSMTP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HITMAILSMTP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HITMAILSMTP_PLUGIN_BASENAME', plugin_basename(__FILE__));


// Plugin initialisieren
function hitmailsmtp_init()
{
    // Hauptklasse aus dem Core-Namespace laden und initialisieren
    return \HITMAILSMTP\App\Classes\Core\Plugin::get_instance();
}

// Das Plugin starten
hitmailsmtp_init();





