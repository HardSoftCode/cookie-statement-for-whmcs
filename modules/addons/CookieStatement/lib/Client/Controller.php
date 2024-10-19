<?php

namespace WHMCS\Module\Addon\CookieStatement\Client;
use WHMCS\Database\Capsule;
use WHMCS\Input\Sanitize;
use WHMCS\Session;

class Controller
{
  public function index($vars)
  {
    $modulelink = $vars['modulelink'];
    $LANG       = $vars['_lang'];

    $data = Capsule::table('hscode_cookie_statement')->first();

    $id          = $data->id;
    $title       = $data->title? $data->title : $LANG['client']['cookiestatement'];
    $content     = Sanitize::decode($data->content);
    $description = $data->description;
    $keywords    = $data->keywords;
    $view        = $data->view? $data->view : '0';
    $created     = fromMySQLDate($data->created_at);
    $updated     = fromMySQLDate($data->updated_at);

    if(!Session::get('cookieId-'.$id))
    {
      Capsule::table('hscode_cookie_statement')->where('id', $id)->increment('view');

      Session::set('cookieId-'.$id, true);
    }

    return [
      'pagetitle'          => $title,
      'breadcrumb'         => ['index.php?m=CookieStatement' => $title],
      'templatefile'       => 'cookie_statement',
      'vars'               => [
        'modulelink'       => $modulelink,
        '_lang'            => $LANG,
        'cookie_statement' => [
          'id'             => $id,
          'title'          => $title,
          'content'        => $content,
          'description'    => $description,
          'keywords'       => $keywords,
          'created'        => $created,
          'updated'        => $updated,
        ],
      ],
    ];
  }
}
