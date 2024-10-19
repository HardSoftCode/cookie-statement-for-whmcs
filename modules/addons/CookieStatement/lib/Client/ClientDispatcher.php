<?php

namespace WHMCS\Module\Addon\CookieStatement\Client;

class ClientDispatcher
{
  public function dispatch($action, $parameters)
  {
    if(!$action)
    {
      $action = 'index';
    }

    $controller = new Controller();

    if (is_callable(array($controller, $action)))
    {
      return $controller->$action($parameters);
    }
  }
}
