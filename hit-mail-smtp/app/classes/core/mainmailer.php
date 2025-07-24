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


    public function loadConfig() {
        $this->host = get_option('smtp_host');
        $this->username = get_option('smtp_username');
        // etc.
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


    /**
     * @param $phpmailer
     * @return void
     */
    public static  function send_smtp_email($phpmailer ) {

        self::$instance = new MainMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host       = self::$instance->getHost();
        $phpmailer->SMTPAuth   = self::$instance->getSMTPAuth();
        $phpmailer->Port       = self::$instance->getPort();
        $phpmailer->Username   = self::$instance->getUsername();
        $phpmailer->Password   = self::$instance->getPassword();
        $phpmailer->SMTPSecure = self::$instance->getSecure();
        $phpmailer->From       = self::$instance->getFrom();
        $phpmailer->FromName   = self::$instance->getFromName();
    }

}