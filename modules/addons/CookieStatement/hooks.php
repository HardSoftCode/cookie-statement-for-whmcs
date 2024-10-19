<?php

add_hook('ClientAreaHeadOutput', 854126, function($vars)
{
  $description = $vars['cookie_statement']['description']? '<meta name="description" content="'.$vars['cookie_statement']['description'].'">' : '';
  $keywords    = $vars['cookie_statement']['keywords']? '<meta name="keywords" content="'.$vars['cookie_statement']['keywords'].'">' : '';

  return <<<HTML
  {$description}
  {$keywords}
HTML;
});
