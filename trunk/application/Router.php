<?php

class Router {
   protected $url = NULL;
   protected $args = NULL;
   protected $postfix = 'Controller';
   protected $controllerName;
   protected $controllerClass;

   public function __construct() {
      if (isset($_GET['rc'])) {
         $this->url = rtrim($_GET['rc'], '/'); // We don't want no empty arg
         $this->args = explode('/', $this->url);
      }
      
      // Load index controller by default, or first arg if specified
      $controller = ($this->url === null) ? 'null' : array_shift($this->args);
      $this->controllerName = ucfirst($controller);

      $this->route();
      $this->controllerClass->render();
   }

   private function route() {
      if (class_exists($this->controllerName . $this->postfix)) {
         $fullName = $this->controllerName . $this->postfix;
         $this->controllerClass = new $fullName;
         $this->controllerClass->setUp();
         if (count($this->args > 1)) { // Pass args that are not controller class
            $this->controllerClass->setArgs($this->args);
         }

         // Second arg in url is our "action", try that as a method-call
         if (isset($this->args[0]) && method_exists($this->controllerClass, $this->args[0])) {
            $this->controllerClass->{$this->args[0]}();
         }
         
      } else { // No such class. Use our default
         $this->defaultRoute();
      }
   }

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
