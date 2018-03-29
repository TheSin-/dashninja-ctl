# TRC Ninja Control Scripts (trcninja-ctl)
Based on Dash Ninja By Alexandre (aka elbereth) Devilliers

Check the running live website at https://overview.terracoin.io

This is part of what makes the TRC Ninja monitoring application.
It contains:
* trc-node.php : is a php implementation of Terracoin protocol to retrieve subver during port checking
* trcblocknotify : is the blocknotify script (for stats)
* trcblockretrieve : is a script used to retrieve block information when blocknotify script did not work (for stats)
* terracoindupdate : is an auto-update terracoind script (uses git)
* tmnbalance : is the balance check script (for stats)
* tmnblockcomputeexpected : is a script used to compute and store the expected fields in cmd_info_blocks table
* tmnblockdegapper : is a script that detects if blocks are missing in cmd_info_blocks table and retrieve them if needed
* tmnblockparser : is the block parser script (for stats)
* tmnctl : is the control script (start, stop and status of nodes)
* tmnctlrpc : is the RPC call sub-script for the control script
* tmnctlstartstopdaemon : is the start/stop daemon sub-script for the control script
* tmncron : is the cron script
* tmnportcheck : is the port check script (for stats)
* tmnportcheckdo : is the actual port check sub-script for the port check script
* tmnreset : is the reset .dat files script
* tmnthirdpartiesfetch : is the script that fetches third party data from the web (for stats)
* tmnvotesrrd and tmnvotesrrdexport: are obsolete v11 votes storage and exported (for graphs)

## Requirement:
* TRC Ninja Back-end: https://github.com/terracoin/trcninja-be
* TRC Ninja Database: https://github.com/terracoin/trcninja-db
* TRC Ninja Front-End: https://github.com/terracoin/trcninja-fe
* PHP 5.6 with curl

Important: Almost all the scripts uses the private rest API to retrieve and submit data to the database (only tmnblockcomputeexpected uses direct MySQL access).

## Install:
* Go to /opt
* Get latest code from github:
```shell
git clone https://github.com/terracoin/trcninja-ctl.git
```
* Get sub-modules:
```shell
cd trcninja-ctl
git submodule update --init --recursive
```
* Configure the tool.

## Configuration:
* Copy tmn.config.inc.php.sample to tmn.config.inc.php and setup your installation.
* Add tmncron to your crontab (every minute is what official TRC Ninja uses)
```
*/1 * * * * /opt/trcninja-ctl/tmncron
```
If you want to enable logging, you need to create the /var/log/tmn/ folder and give the user write access.
Then add "log" as first argument when calling tmncron:
```
*/1 * * * * /opt/trcninja-ctl/tmncron log
```
* Add tmnthirdpartiesfetch to your crontab (every minute is fine, can be longer)
```
*/1 * * * * /opt/trcninja-ctl/tmnthirdpartiesfetch >> /dev/null
```

### trcblocknotify:
* You need /dev/shm available and writable.
* Edit trcblocknotify.config.inc.php to indicates each of your nodes you wish to retrieve block info from.
* You can either retrieve block templates (bt = true) and/or block/transaction (blocks = true). For the latter you need to have txindex=1 in your terracoin config file.
* Add in each of your nodes in terracoin.conf a line to enable blocknotify feature:
```
blocknotify=/opt/trcninja-ctl/trcblocknotify
```
* Restart your node.
* On each block received by the node, the script will be called and data will be created in /dev/shm.
