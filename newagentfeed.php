<?
error_reporting(E_ERROR);
include "RETS_class.php";
include "config.php";

ob_end_flush();
$city='Grande Prairie';

$old_error_handler = set_error_handler("myErrorHandler");

$fields_needed = array('zz','fname','lname','email','office_name','office_website','website','userid','matrix_unique_id');
$data_field = array('zz','fname','lname','email','office','office_website','website','realtorid','matrix_id');
$found = array();
$rets = new RETS;

//Login to NAR CRT Test Server
$results = $rets->login(
     $Host = 'http://matrix.gpreb.com/rets/login.ashx'  //RETS server web address to login
    ,$Account = 'Division1'                         //account assigned by Assoication
    ,$Password = 'tonyblum'                 //password assigned by Assoication
    ,$User_Agent = 'RETS_class/1.0'    //string application & version
    ,$User_Agent_Pwd  = null             //string - useragent password assigned by Assoication
    ,$User_Agent_Auth = false           //true | false
    ,$Rets_Version = 'RETS/1.5'          //RETS/1.5 | RETS/1.7 | RETS/2.0
    ,$Standard_Names = false            //true | false
    ,$Post_Requests = true                //true | false - true for POST, false for GET
    ,$Format = 'COMPACT-DECODED'   //COMPACT | COMPACT-DECODED | STANDARD-DML | STANDARD-XML:dtd-version
    ,$HTTP_Logging = true                //true | false - enable logging to file
);

If (!$results ) { exit; }

$response = $rets->Search(
    $Resource ='Roster'
   ,$Class = 'AGENT'
   ,$Count = 1           //0 = data   1 = data & count, 2 = count
   ,$Format               //COMPACT | COMPACT-DECODED | STANDARD-XML | STANDARD-XML:dtd-version
   ,$Limit = 1500
   ,$QueryType = 'DMQL2'
   ,$Standard_Names
   ,$Select = '*'
   ,$Query = '((City=*))'
);

$doc = new DOMDocument();
$doc->loadXML($response);
$col = strtolower($doc->getElementsByTagName( "COLUMNS" )->item(0)->nodeValue);
$columns=explode("\t",$col);

$realtorid=-1;
for ($i=0;$i<count($fields_needed);$i++)
{
  $match=array_search($fields_needed[$i],$columns);
  if ($match>0)
  {
    $found[$i]=$match;
    if ($columns[$match]=='userid')
	{
      $realtorid=$match;
      $columns[$match]=strtolower($columns[$match]);
	}
  }
  else
    $found[$i]=-1;
}
for ($i=1;$i<count($found);$i++)
{
   if ($found[$i]==-1)
     echo $fields_needed[$i]." was not found\n";
}
$data = $doc->getElementsByTagName( "DATA" );
for ($i = 0; $i < $data->length; $i++)
{
  $dismiss=false;
  $line=$data->item($i)->nodeValue;
  $col=explode("\t",$line);
  $update=false;
  $sql='select * from tb_other_realtors where realtorid="'.$col[$realtorid].'"';
  $res=mysql_query($sql);
  if (mysql_num_rows($res)>0)
  {
    $update=true;
    $list=mysql_fetch_array($res,MYSQL_ASSOC);
    $isql='update tb_other_realtors set ';
  }
  else
  {
    $isql='insert into tb_other_realtors (';
    $k=count($fields_needed);
    for ($j=1;$j<$k;$j++)
    {
      if ($data_field[$j]!='')
      {
        if ($found[$j]>0)
		{
          if ($j<count($data_field) && $j>1)
            $isql.=',';
          $isql.=$data_field[$j];
        }
	  }
    }
  }
  if (!$update)
    $isql.=') values (';
  $matrixid=-1;
  for ($j=1;$j<count($fields_needed);$j++)
  {
      if ($isql!='' && $data_field[$j]!='' && $j>1 and $found[$j]>0 )
        $isql.=',';

      if ($data_field[$j]!='' and $found[$j]>0)
      {
	    if ($update)
	     $isql.=$data_field[$j].'=';
        $isql.="'".htmlentities($col[$found[$j]],ENT_QUOTES)."'";
      }
    }
    $lid=-1;
    if ($update)
      $isql.=' where realtorid="'.$col[$realtorid].'"';
    else
      $isql.=')';

    $res=mysql_query($isql);
    if ($res)
    {
      if ($update)
	    $lid=$list['id'];
	  else
       $lid=mysql_insert_id();
	   echo $col[$realtorid],' updated'."\n";
    }
    else
     echo $isql.' '.mysql_error()."\n";
//  echo $isql.'-'.mysql_error().'<br>';

}

$results = $rets->logout();

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        echo " error on line $errline in file $errfile";
        break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}
?>