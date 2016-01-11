<?php

include_once( 'OFC/open-flash-chart.php' );
include('config.php');

$sql='select * from tb_users where id='.$_SESSION['uid'];
$res=mysql_query($sql);
$user=mysql_fetch_array($res,MYSQL_ASSOC);

if ($user['gender']=='m')
  $color='#0E1DF0';
else
  $color='#F00E0E';
$title = new title( 'Visitor Graphs' );
$bar = new bar_glass( $color, '#577261' );
$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar );
$chart->set_bg_colour( '#FFFFFF' );

$sql='select count(*),DATE_FORMAT(tct.cdate, "%M-%e-%Y") from tb_client_traffic tct,tb_users tu,tb_users tu2 where (tu.id=tu2.realtorid or tu2.id='.$_SESSION['uid'].') and tu.id='.$_SESSION['uid'].' and tu2.id=tct.userid and tct.cdate BETWEEN (CURRENT_DATE() - INTERVAL 7 DAY) and (CURRENT_DATE() + INTERVAL 1 DAY) group by DATE_FORMAT(tct.cdate, "%M-%d-%Y") order by tct.cdate desc  ';
$res=mysql_query($sql);
$val=array();
$lab=array();
$max=-1;
$lastdate='';
date_default_timezone_set('America/Edmonton');
if (mysql_num_rows($res)==0)
{
    $sCurrentDate = date("M-j-Y",time());
    $sEndDate = date("M-j-Y", strtotime("-7 day", strtotime($sCurrentDate)));
    while($sCurrentDate != $sEndDate)
	{
      $val[]=0;
      $lab[]=$sCurrentDate;
	  $sCurrentDate = date("M-j-Y", strtotime("-1 day", strtotime($sCurrentDate)));
    }
}
else
{
  while ($row=mysql_fetch_array($res,MYSQL_NUM))
  {
    if ($lastdate!='')
    {
      if (-(strtotime($row[1])-strtotime($lastdate))>86400)
      {
        $sCurrentDate = date("M-j-Y", strtotime($lastdate));
        $sEndDate = date("M-j-Y", strtotime($row[1]));
	    while($sCurrentDate > $sEndDate)
	    {
	      $sCurrentDate = date("M-j-Y", strtotime("-1 day", strtotime($sCurrentDate)));
          if (array_search($sCurrentDate,$lab)===FALSE)
          {
            $val[]=0;
            $lab[]=$sCurrentDate;
           }
           $row[1]=$sCurrentDate;
	    }
      }
      $lastdate=$row[1];
    }
    else
      $lastdate=$row[1];
    $r=$row[0]*1.0;
    if ($r>$max)
      $max=$r;
    if (array_search($row[1],$lab)===FALSE)
    {
      $val[]=$r;
      $lab[]=$row[1];
    }
    else
    {
      $idx=array_search($row[1],$lab);
      $val[$idx]+=$r;
    }
  }
}
if (count($val)<7)
{
  $lastedate=$lab[count($lab)];
  while (count($val)<7)
  {
    $val[]=0;
    $lastdate = gmdate("M-j-Y", strtotime("-1 day", strtotime($lastdate)));
    $lab[]=$lastdate;
  }
}
$bar->set_values( array_reverse($val) );
$x = new x_axis();
$x->set_colour( '#428C3E' );
$x->set_grid_colour( '#86BF83' );
$x->set_labels_from_array(array_reverse($lab));
$chart->set_x_axis( $x );
$y = new y_axis();
$steps=round($max/20);
$y->set_range( 0, $max, $steps);
$chart->set_y_axis($y);
echo $chart->toPrettyString();