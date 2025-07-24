<?php
namespace HITMAILSMTP\app\classes\core;
/**
 * Hauptklasse für das HIT-Security Plugin
 */
class plugin {
    /**
     * Eine Instanz dieser Klasse
     *
     * @var Plugin
     */
    private static $instance = null;

    /**
     * Plugin-Version
     *
     * @var string
     */
    private $version = '1.0.0.0';

    /**
     * Plugin-Name
     *
     * @var string
     */
    private $plugin_name = 'hit-mail-smtp';



    /**
     * Konfigurations-Manager
     *
     * @var Config
     */
    private $config = null;

    /**
     * Logger-Instanz
     *
     * @var Logger
     */
    private $logger = null;


    /**
     * Konstruktor
     */
    private function __construct() {
        $this->init_config();
        $this->init_logger();
        $this->include_files();
        $this->register_services();
        $this->init_hooks();
    }



    /**
     * Singleton-Pattern: Verhindert, dass mehr als eine Instanz dieser Klasse erstellt wird
     *
     * @return Plugin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Bindet notwendige Dateien ein
     */
    private function include_files() {

    }

    /**
     * Initialisiert WordPress Hooks
     */
    private function init_hooks() {
        // Aktivierungs- und Deaktivierungshooks
        register_activation_hook(HITMAILSMTP_PLUGIN_BASENAME, array($this, 'activate'));
        register_deactivation_hook(HITMAILSMTP_PLUGIN_BASENAME, array($this, 'deactivate'));

        // Controller initialisieren
        add_action('init', array($this, 'init_controllers'));

        // Weitere Hooks
        add_action('init', array($this, 'load_textdomain'));
    }

    /**
     * Controller initialisieren
     */
    public function init_controllers() {
        // Beispiel für die Initialisierung eines Controllers
        $admin_controller = new \HITMAILSMTP\App\Classes\Controller\AdminController();
        $admin_controller->init();

        // Beispiel für die Initialisierung eines Frontend-Controllers
        $frontend_controller = new \HITMAILSMTP\App\Classes\Controller\FrontendController();
        $frontend_controller->init();
    }

    /**
     * Wird beim Aktivieren des Plugins ausgeführt
     */
    public function activate() {
        // Überprüfung der Plugin-Abhängigkeiten
        if (!$this->check_plugin_dependencies()) {
            return false;
        }

        // Aktivierungslogik hier
        flush_rewrite_rules();
        return true;
    }

    /**
     * Wird beim Deaktivieren des Plugins ausgeführt
     */
    public function deactivate() {
        // Deaktivierungslogik hier
        flush_rewrite_rules();
    }

    /**
     * Lädt die Textdomäne für Internationalisierung
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'testcase',
            false,
            dirname(HITMAILSMTP_PLUGIN_BASENAME) . '/languages'
        );
    }


    /**
     * Überprüft auf Updates und führt Datenbank-Migrationen aus
     */


    /**
     * Wird beim Deinstallieren des Plugins ausgeführt
     */
    public static function uninstall() {
        // Plugin-Daten vollständig entfernen

        // Datenbanktabellen löschen
        $plugin = self::get_instance();
        $plugin->services['db']->drop_tables();

        // Optionen löschen
        delete_option('HITMAILSMTP_options');

        // Cron-Jobs entfernen
        wp_clear_scheduled_hook('HITMAILSMTP_daily_event');
    }

    /**
     * Setzt Standard-Optionen für das Plugin
     */
    private function set_default_options() {
        $options = get_option('HITMAILSMTP_options', array());

        $defaults = array(
            'version' => $this->version,
            'enabled_features' => array('feature1', 'feature2'),
            'api_key' => '',
            'debug_mode' => false
        );

        // Neue Optionen mit bestehenden zusammenführen
        $options = wp_parse_args($options, $defaults);

        // Optionen aktualisieren
        update_option('HITMAILSMTP_options', $options);
    }


    /**
     * Zeigt Admin-Notice für Plugin-Abhängigkeiten
     */
    public function show_dependency_notice() {
        // Nur anzeigen wenn das aktuelle Plugin aktiv ist aber die Abhängigkeit fehlt
        if (is_plugin_active(HITMAILSMTP_PLUGIN_BASENAME) && !$this->is_required_plugin_active()) {
            ?>
            <div class="notice notice-error is-dismissible">
                <p>
                    <strong><?php echo $this->plugin_name; ?>:</strong>
                    Das erforderliche Plugin "<?php echo $this->required_plugin['name']; ?>" ist nicht aktiv.
                    Einige Funktionen sind möglicherweise nicht verfügbar.
                </p>
                <p>
                    <a href="<?php echo admin_url('plugins.php'); ?>" class="button button-primary">
                        Plugins verwalten
                    </a>
                </p>
            </div>
            <?php
        }
    }


    /**
     * Überprüft ob das erforderliche Plugin aktiv ist
     *
     * @return bool
     */
    private function is_required_plugin_active() {
        return is_plugin_active($this->required_plugin['main_file']);
    }

    /**
     * Überprüft ob das erforderliche Plugin installiert ist
     *
     * @return bool
     */
    private function is_required_plugin_installed() {
        $plugin_path = WP_PLUGIN_DIR . '/' . $this->required_plugin['main_file'];
        return file_exists($plugin_path);
    }


    /**
     * Zeigt Aktivierungsfehler an
     *
     * @param string $message
     */
    private function show_activation_error($message) {
        // Plugin deaktivieren
        deactivate_plugins(HITMAILSMTP_PLUGIN_BASENAME);

        // Fehlermeldung anzeigen
        wp_die(
            sprintf(
                '<h1>%s</h1><p>%s</p><p><strong>%s:</strong> %s</p><p><a href="%s">%s</a></p>',
                'Plugin-Aktivierung fehlgeschlagen',
                'Dieses Plugin benötigt das "' . $this->required_plugin['name'] . '" Plugin.',
                'Fehler',
                $message,
                admin_url('plugins.php'),
                'Zurück zu den Plugins'
            ),
            'Plugin-Abhängigkeit fehlt',
            array('back_link' => true)
        );
    }




}