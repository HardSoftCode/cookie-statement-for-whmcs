<?php
use WHMCS\Module\Addon\CookieStatement\Admin\AdminDispatcher;
use WHMCS\Module\Addon\CookieStatement\Client\ClientDispatcher;
use WHMCS\Database\Capsule;

if(!defined("WHMCS"))
{
  die("This file cannot be accessed directly");
}

function CookieStatement_config()
{
  return [
    'name'          => 'Cookie Statement',
    'description'   => 'Cookie statement addon module create a page for your cookie statement that will build trust and allows users to visit your website with confidence.',
    'author'        => '<a href="https://www.hardsoftcode.com" target="_blank">HSCode</a>',
    'language'      => 'english',
    'version'       => '1.0.1',
    'fields'        => [
    'delete'        => ['FriendlyName' => 'Delete Module DB', 'Type' => 'yesno', 'Size' => '25', 'Description' => 'Tick this box to delete the module database on deactivating'],
    ],
  ];
}

function CookieStatement_activate()
{
  try
  {
    if(!Capsule::schema()->hasTable('hscode_cookie_statement'))
    {
      Capsule::schema()->create('hscode_cookie_statement', function ($table)
      {
        $table->increments('id');
        $table->text('title');
        $table->text('content');
        $table->text('description');
        $table->text('keywords');
        $table->bigInteger('view')->default(0);
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->default(Capsule::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
      });
    }

    return [
      'status'      => 'success',
      'description' => 'The selected module was activated successfully. Click configure to start configuring the module',
    ];
  }
  catch (\Exception $e)
  {
    return [
      'status'      => 'error',
      'description' => 'Unable to create the module database tables: ' . $e->getMessage(),
    ];
  }
}

function CookieStatement_deactivate()
{
  try
  {
    if(Capsule::table('tbladdonmodules')->where('module', 'CookieStatement')->where('setting', 'delete')->value('value'))
    {
      Capsule::schema()->dropIfExists('hscode_cookie_statement');
    }

    return [
      'status'      => 'success',
      'description' => 'The selected module was deactivated successfully',
    ];
  }
  catch(\Exception $e)
  {
    return [
      'status'      => 'error',
      'description' => 'Unable to drop the module database tables: ' . $e->getMessage(),
    ];
  }
}

function CookieStatement_output($vars)
{
  $action = App::get_req_var('a')? App::get_req_var('a') : '';

  $dispatcher = new AdminDispatcher();
  $response = $dispatcher->dispatch($action, $vars);
  echo $response;
}

function CookieStatement_clientarea($vars)
{
  $action = App::get_req_var('a') ? App::get_req_var('a') : '';

  $dispatcher = new ClientDispatcher();
  return $dispatcher->dispatch($action, $vars);
}
