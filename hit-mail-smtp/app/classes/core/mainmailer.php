<?php


/**
 * Entwurf
 */
class MainMailer{

    /**
     * Eine Instanz dieser Klasse
     *
     * @var MainMailer
     */
    private static $instance = null;

    /**
     * @var string|null
     */
    private ?string $host;

    private ?bool $SMTPAuth;
    /**
     * @var string|null
     */
    private ?string $username;
    /**
     * @var string|null
     */
    private ?string $password;
    /**
     * @var string|null
     */
    private ?string $port;
    /**
     * @var string|null
     */
    private ?string $from;
    /**
     * @var string|null
     */
    private ?string $from_name;
    /**
     * @var string|null
     */
    private ?string $from_email;
    /**
     * @var string|null
     */
    private ?string $to;
    /**
     * @var string|null
     */
    private ?string $secure;
    /**
     * @var string|null
     */
    private ?string $to_name;
    /**
     * @var string|null
     */
    private ?string $subject;
    /**
     * @var string|null
     */
    private ?string $message;


    public function init(){
        // Hook für WordPress PHPMailer
        add_action('phpmailer_init', array($this, 'configure_phpmailer'));
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Lädt Konfiguration aus WordPress-Optionen
     */
    private function loadConfig() {
        $this->host = get_option('mainmailer_smtp_host');
        $this->SMTPAuth = (bool)get_option('mainmailer_smtp_auth', true);
        $this->username = get_option('mainmailer_smtp_username');
        $this->password = get_option('mainmailer_smtp_password');
        $this->port = get_option('mainmailer_smtp_port', '587');
        $this->secure = get_option('mainmailer_smtp_secure', 'tls');
        $this->from_email = get_option('mainmailer_from_email');
        $this->from_name = get_option('mainmailer_from_name');
    }

    /**
     *
     */
    public function __construct() {
        $this->host = $this->setHost(null);

  }

    /**
     * @return string|null
     */
    public function getHost() : ?string {
      return $this->host;
  }

    /**
     * @param string $host
     * @return void
     */
    public function setHost(?string $host) : void {
      $this->host = $host;
  }

    /**
     * @return string|null
     */
    public function getUsername() : ?string {
      return $this->username;
  }

    /**
     * @param string $username
     * @return void
     */
    public function setUsername(?string $username) : void {
      $this->username = $username;
  }

  public function getSMTPAuth() : ?bool {
        return $this->SMTPAuth;
  }

  public function setSMPTAuth(?bool $SMTPAuth) : void {
        $this->SMTPAuth = $SMTPAuth;
  }

    /**
     * @return string|null
     */
    public function getPassword() : ?string {
      return $this->password;
  }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword(?string $password) : void {
      $this->password = $password;
  }

    /**
     * @return string|null
     */
    public function getPort() : ?string {
      return $this->port;

  }

    /**
     * @param string $port
     * @return void
     */
    public function setPort(?string $port) : void {
      $this->port = $port;
  }

    /**
     * @return string|null
     */
    public function getFrom() : ?string {
      return $this->from;
  }

    /**
     * @param string $from
     * @return void
     */
    public function setFrom(?string $from) : void {
      $this->from = $from;
  }

    /**
     * @return string|null
     */
    public function getFromName() : ?string {
      return $this->from_name;
  }

    /**
     * @param string $from_name
     * @return void
     */
    public function setFromName(?string $from_name) : void {

      $this->from_name = $from_name;
  }


    /**
     * @return string|null
     */
    public function getFromEmail() : ?string {
      return $this->from_email;
  }

    /**
     * @param string $from_email
     * @return void
     */
    public function setFromEmail(?string $from_email) : void {
      $this->from_email = $from_email;
  }

    /**
     * @return string|null
     */
    public function getSecure() : ?string {
      return $this->secure;
  }

    /**
     * @param string $secure
     * @return void
     */
    public function setSecure(?string $secure) : void {
      $this->secure = $secure;
  }

    /**
     * @param string $toname
     * @return void
     */
    public function setToName(?string $toname) : void {
      $this->to_name = $toname;
  }

    /**
     * @return string|null
     */
    public function getToName() : ?string {
      return $this->to_name;
  }

    /**
     * @return string|null
     */
    public function getTo() : ?string {
      return $this->to;
  }

    /**
     * @param string $to
     * @return void
     */
    public function setTo(?string $to) : void {
      $this->to = $to;
  }

    /**
     * @return string|null
     */
    public function getSubject() : ?string {
      return $this->subject;
  }

    /**
     * @param string $subject
     * @return void
     */
    public function setSubject(?string $subject) : void {
      $this->subject = $subject;
  }

    /**
     * @return string|null
     */
    public function getMessage() : ?string {
      return $this->message;
  }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(?string $message) : void {
      $this->message = $message;
  }



    public static function sendEmail($to, $subject, $message, $headers = []) {
        $instance = self::getInstance();
        $instance->loadConfig();

        if (!$instance->isConfigured()) {
            error_log('MainMailer: SMTP nicht konfiguriert');
            return false;
        }

        // WordPress wp_mail nutzen (wird automatisch PHPMailer verwenden)
        $instance->setTo($to);
        $instance->setSubject($subject);
        $instance->setMessage($message);

        // phpmailer_init Hook registrieren falls noch nicht geschehen
        if (!has_action('phpmailer_init', [__CLASS__, 'configure_phpmailer'])) {
            add_action('phpmailer_init', [__CLASS__, 'configure_phpmailer']);
        }

        return wp_mail($to, $subject, $message, $headers);
    }

    /**
     * @param $phpmailer
     * @return void
     */
    public static function configure_phpmailer($phpmailer) {
        $instance = self::getInstance();
        $instance->loadConfig(); // Lade Konfiguration aus WordPress-Optionen

        if (!$instance->isConfigured()) {
            return; // Keine SMTP-Konfiguration vorhanden
        }

        $phpmailer->isSMTP();
        $phpmailer->Host       = $instance->getHost();
        $phpmailer->SMTPAuth   = $instance->getSMTPAuth(); // Typo korrigiert
        $phpmailer->Port       = $instance->getPort();
        $phpmailer->Username   = $instance->getUsername();
        $phpmailer->Password   = $instance->getPassword();
        $phpmailer->SMTPSecure = $instance->getSecure();
        $phpmailer->setFrom($instance->getFromEmail(), $instance->getFromName());
    }

    /**
     * Prüft ob SMTP korrekt konfiguriert ist
     */
    private function isConfigured() {
        return !empty($this->host) &&
            !empty($this->username) &&
            !empty($this->password) &&
            !empty($this->from_email);
    }

}