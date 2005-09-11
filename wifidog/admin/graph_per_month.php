<?php
  /********************************************************************\
   * This program is free software; you can redistribute it and/or    *
   * modify it under the terms of the GNU General Public License as   *
   * published by the Free Software Foundation; either version 2 of   *
   * the License, or (at your option) any later version.              *
   *                                                                  *
   * This program is distributed in the hope that it will be useful,  *
   * but WITHOUT ANY WARRANTY; without even the implied warranty of   *
   * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the    *
   * GNU General Public License for more details.                     *
   *                                                                  *
   * You should have received a copy of the GNU General Public License*
   * along with this program; if not, contact:                        *
   *                                                                  *
   * Free Software Foundation           Voice:  +1-617-542-5942       *
   * 59 Temple Place - Suite 330        Fax:    +1-617-542-2652       *
   * Boston, MA  02111-1307,  USA       gnu@gnu.org                   *
   *                                                                  *
   \********************************************************************/
  /**@file graph_per_month.php
   * @author Copyright (C) 2005 Philippe April
   */

define('BASEPATH',"../");
require_once BASEPATH.'admin/graph_common.inc.php';

/**
 * Graph for connections per month
 */
$Graph =& Image_Graph::factory("Image_Graph", array(600, 200));
$Plotarea =& $Graph->add(Image_Graph::factory("Image_Graph_Plotarea"));
$Dataset =& Image_Graph::factory("Image_Graph_Dataset_Trivial");
$Bar =& Image_Graph::factory("Image_Graph_Plot_Bar", &$Dataset);
$Bar->setFillColor("#9db8d2");
$Plot =& $Plotarea->add(&$Bar);

$db->ExecSql("SELECT SUM(daily_connections) AS connections, date_trunc('month', date) AS month FROM (SELECT COUNT(DISTINCT user_id) AS daily_connections, date_trunc('day', timestamp_in) AS date FROM connections WHERE node_id='{$node_id}' ${date_constraint} AND (incoming!=0 OR outgoing!=0) GROUP BY date_trunc('day', timestamp_in)) AS daily_connections_table GROUP BY date_trunc('month', date) ORDER BY month", $results, false);
if ($results != null) {
	foreach($results as $row) {
        /* Cut xxxx-xx-xx xx:xx:Xx to yy-mm */
        $Dataset->addPoint( substr($row['month'],0,7), $row['connections']);
	}
}

$Graph->done();
?>