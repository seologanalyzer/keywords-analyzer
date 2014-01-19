<?php

class FrontController {

  protected $view;
  protected $params;
  protected $controller_name;
  private $child_controller;
  public $user;
  protected $action_name;
  protected $data;
  protected $post_data;
  public $ips;
  public $alerts;
  public $currency;

  public function __construct($user) {
    
    $this->user = $user;
    $this->params = explode('/', $_SERVER['REQUEST_URI']);
    $this->post_data = $_POST;
    $this->controller_name = (!empty($this->params[1])) ? $this->params[1] : 'Index';

    if (get_class($this) == 'FrontController'):
      $this->getController();
    else:
      $this->getAction();
    endif;
  }

  private function getController() {
    try {
      $controller_classname = ucfirst($this->controller_name) . 'Controller';
      $this->child_controller = new $controller_classname($this->user);
      $this->view = $this->child_controller->setView();
    } catch (Exception $e) {

      //print_r($e);

      switch ($e->getCode()) {
        case 403 :
          header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
        default:
          header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
      }

      if (is_object($this->child_controller)) {
        $this->child_controller->data->message = $e->getMessage();
        $this->child_controller->view = URL_VIEWS . $e->getCode() . '.php';
      } else {
        $this->data->message = $e->getMessage();
        $this->view = URL_VIEWS . $e->getCode() . '.php';
      }
    }
  }

  protected function getAction() {
    $this->action_name = (!empty($this->params[2])) ? $this->params[2] : 'Index';

    $action_classname = $this->action_name . 'Action';

    if (method_exists($this, $action_classname) && !empty($this->params[3]) && !isset($this->params[4])) {

      $this->$action_classname($this->params[3]);
    } elseif (method_exists($this, $action_classname) && !empty($this->params[4])) {

      $this->$action_classname($this->params[3], $this->params[4]);
    } elseif (method_exists($this, $action_classname)) {

      $this->$action_classname();
    } else {

      throw new Exception($action_classname . ' in ' . get_class($this) . ' does not exist.', 404);
    }
  }

  private function setView() {
    $this->view = ($this->controller_name == 'Index') ? URL_VIEWS . 'index.php' : URL_VIEWS . strtolower($this->controller_name) . '/' . strtolower($this->action_name) . '.php';

    if (!is_file($this->view) && !isset($this->post_data['noview'])) {
      throw new Exception('File views/' . strtolower($this->controller_name) . '/' . strtolower($this->action_name) . '.php does not exist.', 404);
    }
  }

  public function getView() {
    return $this->view;
  }

  public function getData() {
    return $this->data;
  }

  /**
   * Constructs a new PDO Object and returns it
   *
   * @return FrontController
   */
  public function getChildController() {
    return (is_object($this->child_controller)) ? $this->child_controller : $this;
  }

  /**
   * Constructs a new PDO Object and returns it
   *
   * @return Client
   */
  public function getClient() {
    return $this->user;
  }

  public function getControllerName() {
    return $this->controller_name;
  }

  public function getActionName() {
    return $this->action_name;
  }

  public function setMessage($message) {
    $this->data->message = $message;
  }

  public function getPostData() {
    return $this->post_data;
  }

  protected function IndexAction() {
    
  }

  protected function LogoutAction() {
    Security::logout();
  }

}

?>