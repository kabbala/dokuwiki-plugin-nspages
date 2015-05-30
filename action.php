<?php
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_nspages extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the dokuwiki's event controller
     */
    function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER',  $this, 'insert_button', array());
        $controller->register_hook('PLUGIN_POPULARITY_DATA_SETUP', 'AFTER', $this, 'usage_data');
    }

    function insert_button(& $event, $param) {
      $event->data[] = array (
          'type' => 'insert',
          'title' => 'nspages',
          'icon' => '../../plugins/nspages/images/tb_nspages.png',
          'insert' => $this->getConf('toolbar_inserted_markup')
          );
    }

    function usage_data(&$event){
      $event->data['nspages']['version'] = $this->getInfo()['date'];
      $event->data['nspages']['legacySyntax'] = $this->used_legacy_syntax_not_too_long_ago() ? 'true' : 'false';
    }

    private function used_legacy_syntax_not_too_long_ago(){
      $legacySyntax = io_readFile(action_plugin_nspages::legacySyntaxFilename());
      if ( $legacySyntax ){
        if ( $legacySyntax > time() - 365 * 86400 ){
          return true;
        } else {
          unlink(action_plugin_nspages::legacySyntaxFilename());
        }
      }
      return false;
    }

    static function logUseLegacySyntax(){
        $file = action_plugin_nspages::legacySyntaxFilename();
        io_saveFile($file, time());
    }

    static function legacySyntaxFilename(){
        global $conf;
        return $conf['savedir'] . '/nspages_legacy_syntax.txt';
    }
}
