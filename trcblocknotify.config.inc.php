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

// Indicate for each of your nodes which one you need to retrieve blocktemplate from (bt) and/or block info (block)
// Best practice for now is only retrieve block from one node and blocktemplate from all
$unamelist = array(
       'tmn01' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn02' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn03' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn04' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn05' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn06' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn07' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn08' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn09' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn10' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn11' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn12' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn13' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn14' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tmn15' => array('bt' => true,   'block' => false,   'mempool' => false),
       'p2pool' => array('bt' => true,   'block' => true,   'mempool' => true),
       'ttmn01' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn02' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn03' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn04' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn05' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn06' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn07' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn08' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn09' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn10' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn11' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn12' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn13' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn14' => array('bt' => true,   'block' => false,   'mempool' => false),
       'ttmn15' => array('bt' => true,   'block' => false,   'mempool' => false),
       'tp2pool' => array('bt' => true,   'block' => true,   'mempool' => true),
);

?>
