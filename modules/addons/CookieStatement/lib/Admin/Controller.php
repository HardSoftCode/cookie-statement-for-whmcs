<?php

namespace WHMCS\Module\Addon\CookieStatement\Admin;
use WHMCS\Database\Capsule;
use App;

class Controller
{
    public function index($vars)
    {
      $modulelink = $vars['modulelink'];
      $LANG       = $vars['_lang'];
      $systemurl  = App::getSystemUrl();

      if(App::get_req_var('success'))
      {
        $message  = ' <script>$(document).ready( function(){ window.setTimeout( function(){ $(".alertbox").slideUp(); }, 2500);});</script>';
        $message .= '<div class="alert alert-success alertbox" style="font-size:14px"><strong><span class="fas fa-check"></span> '.$LANG['admin']['success'].'</strong> '.$LANG['admin']['successhelp'].'</div>';
      }

      $data = Capsule::table('hscode_cookie_statement')->first();

      $id          = $data->id;
      $title       = $data->title;
      $content     = $data->content;
      $description = $data->description;
      $keywords    = $data->keywords;
      $view        = $data->view? $data->view : '0';
      $created     = fromMySQLDate($data->created_at);
      $updated     = fromMySQLDate($data->updated_at);

      return <<<EOF
      {$message}
      <div class="row">
        <div class="col-md-4">
          <div class="alert alert-success text-center" role="alert">
            <strong style="font-size: 20px;">
              {$LANG['admin']['pagevisits']}<br>{$view}
            </strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-info text-center" role="alert">
            <strong style="font-size: 20px;">
              {$LANG['admin']['created']}<br>{$created}
            </strong>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-warning text-center" role="alert">
            <strong style="font-size: 20px;">
              {$LANG['admin']['updated']}<br>{$updated}
            </strong>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal" action="{$modulelink}&a=save&id={$id}" method="post">
            <div class="form-group">
              <label class="col-md-3 control-label">{$LANG['admin']['title']}</label>
              <div class="col-md-6">
                <input type="text" name="title" value="{$title}" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{$LANG['admin']['content']}</label>
              <div class="col-md-9">
                <textarea name="content" class="tinymce">{$content}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{$LANG['admin']['description']}</label>
              <div class="col-md-6">
                <textarea name="description" class="form-control">{$description}</textarea>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label">{$LANG['admin']['keywords']}</label>
              <div class="col-md-6">
                <input type="text" name="keywords" value="{$keywords}" class="form-control">
                <span id="helpBlock" class="help-block">{$LANG['admin']['keywordshelp']}</span>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-offset-3 col-md-6">
              <button type="submit" class="btn btn-primary">{$LANG['admin']['savechanges']}</button>
              <a href="{$systemurl}index.php?m=CookieStatement" class="btn btn-default" target="_blank">{$LANG['admin']['viewpage']}</a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <script type="text/javascript" src="../assets/js/tinymce/tinymce.min.js"></script>
      <script type="text/javascript">
        $().ready(function() {
          var tinymceSettings = {
              selector: "textarea.tinymce",
              height: 300,
              theme: "modern",
              entity_encoding: "raw",
              plugins: "autosave print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template code codesample table charmap hr pagebreak nonbreaking anchor insertdatetime advlist lists textcolor wordcount contextmenu colorpicker textpattern help paste",
              toolbar: [
                  "formatselect,fontselect,fontsizeselect,|,bold,italic,strikethrough,underline,forecolor,backcolor,|,link,unlink,|,justifyleft,justifycenter,justifyright,justifyfull,|,search,replace,|,bullist,numlist,",
                  "outdent,indent,blockquote,|,undo,redo,|,cut,copy,paste,pastetext,pasteword,|,table,|,hr,|,sub,sup,|,charmap,|,print,|,ltr,rtl,|,fullscreen,|,help,code,removeformat"
              ],
              image_advtab: true,
              content_css: [
                  "//fonts.googleapis.com/css?family=Lato:300,300i,400,400i",
                  "//www.tinymce.com/css/codepen.min.css"
              ],
              browser_spellcheck: true,
              convert_urls : false,
              relative_urls : false,
              forced_root_block : "p",
              media_poster: false,
              mobile: {
                  theme: "mobile",
                  plugins: ["autosave", "lists", "autolink"],
                  toolbar: ["undo", "bold", "italic", "styleselect"]
              },
              menu: {
                  file: {title: "File", items: "preview | print"},
                  edit: {title: "Edit", items: "undo redo | cut copy paste pastetext | selectall | searchreplace"},
                  view: {title: "View", items: "visualaid visualchars visualblocks | preview fullscreen"},
                  insert: {title: "Insert", items: "anchor link codesample | charmap hr"},
                  format: {title: "Format", items: "bold italic strikethrough underline superscript subscript codeformat | blockformats align | removeformat"},
                  table: {title: "Table", items: "inserttable tableprops deletetable | cell row column"},
                  help: {title: "Help", items: "help | code"}
              }
          };

          $(document).ready(function() {
              tinymce.init(tinymceSettings).then(function(editors){
                  editorLoaded = true;
              });
          });

          var editorEnabled = true,
              editorLoaded = false;

          function toggleEditor() {
              if (editorEnabled === true) {
                  tinymce.activeEditor.remove();
                  editorEnabled = false;
              } else {
                  tinymce.init(tinymceSettings);
                  editorEnabled = true;
              }
          }
        });
      </script>

EOF;
  }

  public function save($vars)
  {
    $modulelink              = $vars['modulelink'];
    $id                      = App::get_req_var('id');
    $postData['title']       = App::get_req_var('title');
    $postData['content']     = App::get_req_var('content');
    $postData['description'] = App::get_req_var('description');
    $postData['keywords']    = App::get_req_var('keywords');

    if($id)
    {
      Capsule::table('hscode_cookie_statement')->where('id', $id)->update($postData);
    }
    else
    {
      Capsule::table('hscode_cookie_statement')->insert($postData);
    }

    header('Location: '.$modulelink.'&success=true');
    exit;
  }
}
