<?
include ("config.php");
$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
//$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr'; // paypal testing url

$ipn_data=array();
$ipn_response='';
global $ipn_data,$ipn_response,$paypal_url;

validate_ipn();

   function validate_ipn()
   {

     global $ipn_data,$ipn_response,$paypal_url;
     $url_parsed=parse_url($paypal_url);

      $post_string = '';
      foreach ($_POST as $field=>$value) {
         $ipn_data["$field"] = $value;
         $post_string .= $field.'='.urlencode(stripslashes($value)).'&';
      }
      $post_string.="cmd=_notify-validate"; // append ipn command

      // open the connection to paypal
      $fp = fsockopen($url_parsed['host'],"80",$err_num,$err_str,30);
      if(!$fp)
      {
         $sql='insert into tb_dump (cdate,msg) values ("'.date('d-m-y H:i:s').'","fsockopen error no. '.$errnum.': '.$errstr.'")';
         $result=mysql_query($sql);
         return false;
      }
      else
      {
         fputs($fp, "POST ".$url_parsed['path']." HTTP/1.1\r\n");
         fputs($fp, "Host: ".$url_parsed['host']."\r\n");
         fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
         fputs($fp, "Content-length: ".strlen($post_string)."\r\n");
         fputs($fp, "Connection: close\r\n\r\n");
         fputs($fp, $post_string . "\r\n\r\n");
         while(!feof($fp)) {
            $ipn_response .= fgets($fp, 1024);
         }
         fclose($fp); // close connection
      }
      if (stripos($ipn_response,"VERIFIED")>0)
      {
         log_ipn_results(true,$last_error);
         return true;
      }
      else
      {
         $last_error = 'IPN Validation Failed.';
         log_ipn_results(false,$last_error);
         return false;
      }
   }

   function log_ipn_results($success,$last_error)
   {
      global $ipn_data,$ipn_response;

      $text = '['.date('m/d/Y g:i A').'] - ';

      if ($success) $text .= "SUCCESS!\n";
      else $text .= 'FAIL: '.$last_error."\n";

      // Log the POST variables
      $text .= "IPN POST Vars from Paypal:\n";
	  $payment=true;
	  foreach ($ipn_data as $key=>$value) {
	    if ($key=='reason_code')
		{
		  if ($value=='refund')
		    $payment=false;
		}
		if ($key=='payment_status')
		{
		    $payment=true;
		    $pvalue='Completed';
		}
		if ($key='txn_type' and $value=='subscr_cancel')
		{
		  $cancel=true;
	      $payment=false;
	    }
	  }
	  if ($payment)
	  {
        foreach ($ipn_data as $key=>$value)
		{
           if ($key=='invoice' && $success)
           {
             $sql='select * from tb_users where id='.$value;
  		     $result=mysql_query($sql);
			 if ($result and mysql_num_rows($result)>0)
			 {
			   if ($pvalue=='Completed')
			   {
			     $sql2='update tb_users set password=aes_encrypt(email,"tonyb"),payment_status="y",add_client="y" where id='.$value;
                 $res=mysql_query($sql2);
               }
               $res=mysql_query($sql);
               $row=mysql_fetch_array($result,MYSQL_ASSOC);
			 }
			$subject = 'Instant Payment Notification - Payment-'.$pvalue;
            $to = 'tonyblum@me.com';    //  your email
            $body =  "An instant payment notification was recieved. Payment".$pvalue."\n";
            $email   = 'tonyblum@me.com';
            $headers = "From:" .$email;
            $body .= "from ".$ipn_data['payer_email']." on ".date('m/d/Y')."\n";
            if ($row['brokerage_image']!='')
              $body.='Brokerage Image: http://myagenotnow.ca/images/'.$row['brokerage_image']."\n";
            if ($row['personal_image']!='')
              $body.='Personal Image: http://myagenotnow.ca/images/'.$row['personal_image'];
            $body .= " at ".date('g:i A')."\n\nDetails:\n";
            foreach ($ipn_data as $key => $value) { $body .= "\n$key: $value"; }
             mail($to, $subject, $body, $headers);
         }
         $text .= "$key=$value, ";
        }
      }
      if ($cancel)
	  {
        foreach ($ipn_data as $key=>$value)
		{
           if ($key=='invoice')
           {
             $sql='select * from tb_users where id='.$value;
  		     $result=mysql_query($sql);
			 if ($result and mysql_num_rows($result)>0)
			 {
               $sql='update tb_users set payment_status="n",add_client="n" where id='.$value;
               $text.=$sql."\n";
               $res=mysql_query($sql);
               $row=mysql_fetch_array($result,MYSQL_ASSOC);
			 }
			$subject = 'Subscription cancellation';
            $to = 'tonyblum@me.com';    //  your email
            $body =  "A subscription cancellation was recieved\n";
            $email   = 'tonyblum@me.com';
            $headers = "From:" .$email;
            $body .= "from ".$ipn_data['payer_email']." on ".date('m/d/Y');
            $body .= " at ".date('g:i A')."\n\nDetails:\n";
            foreach ($ipn_data as $key => $value) { $body .= "\n$key: $value"; }
             mail($to, $subject, $body, $headers);
         }
         $text .= "$key=$value, ";
        }
	  }
      $text .= "\nIPN Response from Paypal Server:\n ".$ipn_response;
      $sql='insert into tb_dump (cdate,msg) values ("'.date('Y-m-d H:i:s').'","'.htmlentities($text,ENT_QUOTES).'")';
      $result=mysql_query($sql);
   }

?>
