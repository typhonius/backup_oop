<?php
/**
 * Created by JetBrains PhpStorm.
 * User: adam.malone
 * Date: 20/9/13
 * Time: 12:11 09
 * To change this template use File | Settings | File Templates.
 */

namespace backup;
use Symfony\Component\Yaml\Yaml;


class Config {
  public $servers;
  public $docroots;

  public function __construct() {
    self::loadConfig();
  }

  protected function loadConfig() {
    $servers = File::loadFiles(CONFIG . '/servers', '/.*\.yml/');
    $docroots = File::loadFiles(CONFIG . '/docroots', '/.*\.yml/');

    foreach ($servers as &$server) {
      $server->data = Yaml::parse($server->uri);
      $this->servers[$server->data['machine']] = $server;
    }
    foreach ($docroots as &$docroot) {
      $docroot->data = Yaml::parse($docroot->uri);
      $this->docroots[$docroot->data['machine']] = $docroot;
    }
  }

  public function getServerConfig() {
    return $this->servers;
  }

  public function getDocrootConfig() {
    return $this->docroots;
  }

  public function returnInfoArray($stage, $param) {
    foreach ($this->$stage as $obj) {
      $return[] = $obj->data[$param];
    }
    return $return;

    // TODO add in methods for server and docroot loading
  }

  public function isValidConfig($docroot, $server, $env) {
    // First ensure the docroot machine name is in existence.
    if ($this->docroots[$docroot]) {
      // Now check that the environment exists for this docroot.
      if (array_key_exists($env, $this->docroots[$docroot]->data['environments'])) {
        // Finally ensure that the server passed to this function is in place on that environment.
        if ($server == $this->docroots[$docroot]->data['environments'][$env]['server']) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  public function getDocrootList() {
    foreach ($this->docroots as $docroot) {
      $docroots[] = $docroot->data['name'];
    }
    return $docroots;
  }

  public function getBackupLocation() {
    // TODO set backup location in config somewhere
    $path = ROOT_DIR . '/backups';
    if (File::checkDirectory($path)) {
      return $path;
    }
    else {
      return '/tmp';
    }
  }

}