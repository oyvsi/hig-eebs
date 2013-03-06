<?php
/**
 * 
 * This class is responsible to do all routing for the application.
 * All routes are dependant on the url and is passed via index.php?rc=
 * All urls should be in the form http://$rootPath/$controller/method/arg1/argN
 *
 * @author Team Henkars
 */

class Router {
   protected $url = NULL;
   protected $args = NULL;
   protected $postfix = 'Controller';
   protected $controllerName;
   protected $controllerClass;

   /**
    * Default constructor.
    * Sets our arguments based on a '/'-split
    */
   public function __construct() {
      if (isset($_GET['rc'])) {
         $this->url = rtrim($_GET['rc'], '/'); // We don't want no empty arg
         $this->args = explode('/', $this->url);
      }
      
      // Load index controller by default, or first arg if specified
      $controller = ($this->url === null) ? 'null' : array_shift($this->args);
      $this->controllerName = ucfirst($controller);

      // Create controller and call method
      $this->route();
      // Make the controller display something
      $this->controllerClass->render();
   }

   /**
    * This function creates the controller, 
    * gives the controller additional args and calls the method
    */
   private function route() {
      if (class_exists($this->controllerName . $this->postfix)) {
         $fullName = $this->controllerName . $this->postfix;
         $this->controllerClass = new $fullName;
         $this->controllerClass->setUp();
         if (count($this->args > 1)) { // Pass args that are not controller class
            $this->controllerClass->setArgs($this->args);
         }

         // Second arg in url is our "action", try that as a method-call
         $method = strtolower($this->args[0]); // method names are case-insensitive. Might as well take advantage of it.
         if (isset($method) && method_exists($this->controllerClass, $method)) {
            $this->controllerClass->{$this->args[0]}();
         }
         
      } else { // No such class. Use our default
         $this->defaultRoute();
      }
   }

   /**
    * This is our default route. If the controller given does not exist,
    * or not specified, we offer this.
    */
   private function defaultRoute() {
      $this->controllerClass = new IndexController();
      $this->controllerClass->setUp();
      $method = strtolower($this->controllerName);
      if (method_exists($this->controllerClass, $method)) {
         $this->controllerClass->$method();
      } else {
         $this->controllerClass->loadIndex();
      }
   }
}