<?php

/*
    This file is part of TRC Ninja.
    https://github.com/terracoin/trcninja-ctl

    TRC Ninja is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    TRC Ninja is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with TRC Ninja.  If not, see <http://www.gnu.org/licenses/>.

 */

// Deal with terracoin.conf configuration (read/write)
class TerracoinConfig {

  // Normal terracoin.conf configuration
  private $config;

  // Masternode Control configuration
  private $mnctlcfg;

  // Masternode Control Magic Keyword
  const MAGIC = '#mnctlcfg#';

  // Path to config file
  private $configfilename;
  private $configloaded = false;

  // Load the config file
  private function loadconfig() {
    $this->config = array();
    $this->mnctlcfg = array();
    $this->configloaded = false;
    if (file_exists($this->configfilename)) {
      $rawconf = file_get_contents($this->configfilename);
      $conf = explode("\n",trim($rawconf));
      $magiclen = strlen(TerracoinConfig::MAGIC);
      for ($x = 0; $x < count($conf); $x++) {
        if ((substr($conf[$x],0,1) == '#') && (substr($conf[$x],0,$magiclen) != TerracoinConfig::MAGIC)) {
          $lineval = array(0 => $conf[$x]);
        }
        else {
          $lineval = explode('=',$conf[$x]);
        }
        if (substr($lineval[0],0,$magiclen) == TerracoinConfig::MAGIC) {
          $this->mnctlcfg[substr($lineval[0],$magiclen)] = $lineval[1];
        }
        else {
          if (isset($lineval[1])) {
            $this->config[$lineval[0]] = $lineval[1];
          }
          else {
            $this->config[$lineval[0]] = false;
          }
        }
      }
      $this->configloaded = true;
    }
  }

  function __construct($uname) {

    if (file_exists('/home/'.$uname.'/.bitcore/data/terracoin.conf')) {
      $this->configfilename = '/home/'.$uname.'/.bitcore/data/terracoin.conf';
    }
    elseif (file_exists('/etc/terracoincore/terracoin.conf')) {
      $this->configfilename = '/etc/terracoincore/terracoin.conf';
    }
    else {
      $this->configfilename = '/home/'.$uname.'/.terracoincore/terracoin.conf';
    }
    $this->loadconfig();

  }

  function getconfig($key) {

    $res = false;
    if (array_key_exists($key,$this->config)) {
      $res = $this->config[$key];
    }
    return $res;

  }

  function setconfig($key,$value) {

    $this->config[$key] = $value;

  }

  function getmnctlconfig($key) {

    $res = false;
    if (array_key_exists($key,$this->mnctlcfg)) {
      $res = $this->mnctlcfg[$key];
    }
    return $res;

  }

  function setmnctlconfig($key,$value) {

    $this->mnctlcfg[$key] = $value;

  }

  // Save the config file
  function saveconfig() {
    if ($this->configloaded) {
      $rawconf = '';
      foreach ($this->config as $key => $value) {
        if ($value === false) {
          $rawconf .= $key."\n";
        }
        else {
          $rawconf .= $key.'='.$value."\n";
        }
      }
      foreach ($this->mnctlcfg as $key => $value) {
        if ($value === false) {
          $rawconf .= TerracoinConfig::MAGIC.$key."\n";
        }
        else {
          $rawconf .= TerracoinConfig::MAGIC.$key.'='.$value."\n";
        }
      }
      $res = file_put_contents($this->configfilename,$rawconf);
    }
    else {
      $res = false;
    }
    return $res;
  }

  function isConfigLoaded() {
    return $this->configloaded;
  }

}

?>
