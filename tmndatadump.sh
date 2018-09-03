#!/bin/zsh
#
#   This file is part of TRC Ninja.
#   https://github.com/terracoin/trcninja-ctl
#
#   TRC Ninja is free software: you can redistribute it and/or modify
#   it under the terms of the GNU General Public License as published by
#   the Free Software Foundation, either version 3 of the License, or
#   (at your option) any later version.
#
#   TRC Ninja is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License
#   along with TRC Ninja.  If not, see <http://www.gnu.org/licenses/>.
#

# Disable logging by default
updatelog=/dev/null
statuslog=/dev/null
votesrrdlog=/dev/null
balancelog=/dev/null
portchecklog=/dev/null
blockparserlog=/dev/null
autoupdatelog=/dev/null

# If parameter 1 is log then enable logging
if [[ "$1" == "log" ]]; then
  rundate=$(date +%Y%m%d%H%M%S)
  updatelog=/var/log/tmn/update.$rundate.log
  statuslog=/var/log/tmn/status.$rundate.log
  votesrrdlog=/var/log/tmn/votesrrd.$rundate.log
  balancelog=/var/log/tmn/balance.$rundate.log
  portchecklog=/var/log/tmn/portcheck.$rundate.log
  blockparserlog=/var/log/tmn/blockparser.$rundate.log
  autoupdatelog=/var/log/tmn/autoupdate.$rundate.log
fi

# Sequentially run scripts
#/opt/tmnctl/terracoindupdate >> $updatelog
/opt/tmnctl/tmnctl status >> $statuslog
#/opt/tmnctl/tmnvotesrrd >> $votesrrdlog
/opt/tmnctl/tmnblockparser >> $blockparserlog

# Concurrently run scripts
/opt/tmnctl/tmnbalance >> $balancelog &
/opt/tmnctl/tmnportcheck db >> $portchecklog &
/opt/tmnctl/tmnautoupdate >> $autoupdatelog &
