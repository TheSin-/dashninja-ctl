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

if (!defined('TMN_SCRIPT') || !defined('TMN_CONFIG') || (TMN_SCRIPT !== true) || (TMN_CONFIG !== true)) {
  die('Not executable');
}

define('TMN_VERSION','1.2.4');

// Start the masternodes
function tmn_start($uname,$conf,$terracoind,$extra="") {

  $testnet = ($conf->getconfig('testnet') == 1);
  $pid = tmn_getpid($uname,$testnet);
  $starttmn = (tmn_checkpid($pid) === false);
  if (!$starttmn) {
    echo "Already running. Nothing to do.";
    $res = true;
  }
  else {
    $tmnenabled = ($conf->getmnctlconfig('enable') == 1);
    if ($tmnenabled) {
      $RUNASUID = tmn_getuid($uname,$RUNASGID);
      if ($testnet) {
        $nice = TMN_NICELEVEL_TEST;
      }
      else {
        $nice = TMN_NICELEVEL_MAIN;
      }
      $trycount = 0;
      $res = false;
      while ((!$res) && (!tmn_checkpid(tmn_getpid($uname,$testnet))) && ($trycount < 3)) {
        echo "T$trycount.";
        exec("/sbin/start-stop-daemon -S -c $RUNASUID:$RUNASGID -N " . $nice . " -x " . $terracoind . " -u $RUNASUID -a " . $terracoind . " -q -b -- -daemon $extra");
        usleep(250000);
        $waitcount = 0;
        while ((!tmn_checkpid(tmn_getpid($uname, $testnet))) && ($waitcount < TMN_STOPWAIT)) {
          usleep(1000000);
          $waitcount++;
          echo ".";
        }
        if (tmn_checkpid(tmn_getpid($uname, $testnet))) {
          echo "Started!";
          $res = true;
        }
        $trycount++;
        if ($trycount == 3) {
          echo "Could not start!";
        };
      }
    }
    else {
      echo "DISABLED";
      $res = true;
    }
  }
  return $res;

}

// Stop the masternode
function tmn_stop($uname,$conf) {

  $testnet = ($conf->getconfig('testnet') == 1);
  if ($testnet) {
    $testinfo = '/testnet3';
  }
  else {
    $testinfo = '';
  }

  $rpc = new Bitcoin($conf->getconfig('rpcuser'),$conf->getconfig('rpcpassword'),'localhost',$conf->getconfig('rpcport'));

  $pid = tmn_getpid($uname,$testnet);

  if ($pid !== false) {
    $tmp = $rpc->stop();
    if (($rpc->response['result'] != "Terracoin server stopping") && ($rpc->response['result'] != "Terracoin Core server stopping")) {
      echo "Unexpected daemon answer (".$rpc->response['result'].") ";
    }
    usleep(250000);
    $waitcount = 0;
    while (tmn_checkpid($pid) && ($waitcount < TMN_STOPWAIT)) {
      usleep(1000000);
      $waitcount++;
      echo ".";
    }
    if (tmn_checkpid($pid)) {
      echo "Soft Stop Failed! Forcing Kill... ";
      exec('kill -s kill '.$pid);
      $waitcount = 0;
      while (tmn_checkpid($pid) && ($waitcount < TMN_STOPWAIT)) {
        echo '.';
        usleep(1000000);
        $waitcount++;
      }
      if (tmn_checkpid($pid)) {
        echo "Failed!";
        $res = false;
      }
      else {
        if (file_exists('/home/'.$uname."/.terracoincore$testinfo/terracoind.pid")) {
          unlink('/home/'.$uname."/.terracoincore$testinfo/terracoind.pid");
        }
        echo "OK (Killed) ";
        $res = true;
      }
    }
    else {
      echo " OK (Soft Stop) ";
      $res = true;
    }
  }
  else {
    echo "NOT started ";
    $res = true;
  }
  return $res;

}

if (($argc < 3) && ($argv > 5)) {
  xecho("Usage: ".basename($argv[0])." uname (start|stop|restart) [terracoind] [extra_params]\n");
  die(1);
}

$uname = $argv[1];
$command = $argv[2];
if ($argc > 3) {
  $terracoind = $argv[3];
}
else {
  $terracoind = TMN_TERRACOIND_DEFAULT;
}
if ($argc > 4) {
  $extra = $argv[4];
}
else {
  $extra = "";
}

if (!is_dir(TMN_PID_PATH.$uname)) {
  xecho("This node don't exist: ".TMN_PID_PATH.$uname."\n");
  die(2);
}

$conf = new TerracoinConfig($uname);
if (!$conf->isConfigLoaded()) {
  xecho("Error (Config could not be loaded)\n");
  die(7);
}

if ($command == 'start') {
  if (!is_executable($terracoind)) {
    xecho("Error ($terracoind is not an executable file)\n");
    die(8);
  }
  xecho("Starting $uname: ");
  if (tmn_start($uname,$conf,$terracoind,$extra)) {
    echo "\n";
    die(0);
  }
  else {
    echo "\n";
    die(5);
  }
}
elseif ($command == 'stop') {
  xecho("Stopping $uname: ");
  if (tmn_stop($uname,$conf)) {
    echo "\n";
    die(0);
  }
  else {
    echo "\n";
    die(6);
  }
}
elseif ($command == 'restart') {
  if (!is_executable($terracoind)) {
    xecho("Error ($terracoind is not an executable file)\n");
    die(8);
  }
  xecho("Restarting $uname: ");
  if (tmn_stop($uname,$conf)) {
    if (tmn_start($uname,$conf,$terracoind,$extra)) {
     echo "\n";
     die(0);
    }
    else {
    echo "\n";
      die(5);
    }
  }
  else {
    echo(" Could not stop daemon. Giving up.\n");
    die(4);
  }
}
else {
  xecho('Unknown command: '.$command."\n");
  die(3);
}

?>
