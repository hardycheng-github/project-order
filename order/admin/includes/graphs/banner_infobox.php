<?php
/* --------------------------------------------------------------
   $Id: banner_infobox.php,v 1.1 2003/09/06 22:05:29 oldpa   Exp $   

   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(banner_infobox.php,v 1.2 2002/05/09); www.oscommerce.com 
   (c) 2003	 nextcommerce (banner_infobox.php,v 1.5 2003/08/18); www.nextcommerce.org
   (c) 2003	 xt-commerce  www.xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

  require(DIR_WS_CLASSES . 'phplot.php');
global $db;
  $stats = array();
  $banner_stats = $db->Execute("select dayofmonth(banners_history_date) as name, banners_shown as value, banners_clicked as dvalue from " . TABLE_BANNERS_HISTORY . " where banners_id = '" . $banner_id . "' and to_days(now()) - to_days(banners_history_date) < " . $days . " order by banners_history_date");
  while (!$banner_stats->EOF) {
    $stats[] = array($banner_stats->fields['name'], $banner_stats->fields['value'], $banner_stats->fields['dvalue']);
 $banner_stats->MoveNext();
  }

  if (sizeof($stats) < 1) $stats = array(array(date('j'), 0, 0));

  $graph = new PHPlot(200, 220, 'images/graphs/banner_infobox-' . $banner_id . '.' . $banner_extension);

  $graph->SetFileFormat($banner_extension);
  $graph->SetIsInline(1);
  $graph->SetPrintImage(0);

  $graph->draw_vert_ticks = 0;
  $graph->SetSkipBottomTick(1);
  $graph->SetDrawXDataLabels(0);
  $graph->SetDrawYGrid(0);
  $graph->SetPlotType('bars');
  $graph->SetDrawDataLabels(1);
  $graph->SetLabelScalePosition(1);
  $graph->SetMarginsPixels(15,15,15,30);

  $graph->SetTitleFontSize('4');
  $graph->SetTitle('3 Day Statistics');

  $graph->SetDataValues($stats);
  $graph->SetDataColors(array('blue','red'),array('blue', 'red'));

  $graph->DrawGraph();

  $graph->PrintImage();
?>