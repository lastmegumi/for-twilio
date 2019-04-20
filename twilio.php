<?php
require_once(ROOT_PATH . 'include/twilio/autoload.php');
use Twilio\Rest\Client;
use Dompdf\Dompdf;
use Dompdf\Options;

class sms_sys{
	private $Token 	= "314679426ef4f593344c2522ad843c74";
	private $sid 	= "AC55bc3f3fdf99d6b96046ae29d99cec8e";
	private $from = "+15168304418";

	function PDF($content, $filename = null){
		error_reporting(0); 
		set_time_limit(0);
		ini_set('memory_limit', '8096M');
		$content =  $content;

		require_once ROOT_PATH . '/include/dompdf/autoload.inc.php';

		$options = new Options();
		$options->set('defaultFont', 'simhei');
		$dompdf = new Dompdf($options);
		$dompdf->loadHtml($content);

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('LETTER', 'portrait');

		// Render the HTML as PDF
		$dompdf->render();

		// Output the generated PDF to Browser
		#$dompdf->stream();
		#print_r($html);

		$output = $dompdf->output();
		$fn = $filename? ROOT_PATH . '/' . $filename . ".pdf" : ROOT_PATH . '/temp.pdf';
    	file_put_contents( $fn, $output);
    	//return 'http://yeetik.test/temp.pdf';
	}

	function Voice($phone = null){
		#$phone = "+19292453662";

		$client = new Client($this->sid, $this->Token);
		$client->account->calls->create(  
		    $phone,
		    $this->from,
		    array(
		        "url" => "http://ab.vanguardoffroad.com/voice.xml"
		    )
	    );
	}


	function Fax($phone = null, $media = null){
		if(empty($media)){return;}

		#$media = $this->PDF($content);
		$phone = $this->is_phone_check($phone);
		if(!$phone){return;}

		$twilio = new Client($this->sid, $this->Token);
		$twilio->fax->v1->faxes->create($phone, // to
		                                $media, // mediaUrl
		                                array("from" => $this->from)
		                       );
	}

	function Sms($phone = null, $content = null){
		if(empty($content)){return false;}
		$phone = $this->is_phone_check($phone);
		if(!$phone){return false;}
		
		$client = new Client($this->sid, $this->Token);
		$client->messages->create(
		    // the number you'd like to send the message to
		    $phone,
		    array(
		        // A Twilio phone number you purchased at twilio.com/console
		        'from' => $this->from,
		        // the body of the text message you'd like to send
		        'body' => $content
		    )
		);		

        $sms['apiVersion']	=	$m->apiVersion;
        $sms['body']		=	$m->body;
        $sms['dateCreated']	=	$m->dateCreated;
        $sms['dateUpdated']	=	$m->dateUpdated;
        $sms['dateSent']	=	$m->dateSent;
        $sms['errorCode']	=	$m->errorCode;
        $sms['errorMessage']=	$m->errorMessage;
        $sms['sent_from']	=	$m->from;
        $sms['sent_to']		=	$m->to;
        $sms['direction']	=	$m->direction;
        $sms['numMedia']	=	$m->numMedia;
        $sms['numSegments']	=	$m->numSegments;
        $sms['price']		=	$m->price;
        $sms['priceUnit']	=	$m->priceUnit;
        $sms['sid']			=	$m->sid;
       	$sms['status']		=	$m->status;
       	$sms['uri']			=	$m->uri;
       	$sms['note']	= $note?$note:"";


       	if($return){
	       	if(is_null($sms['errorCode'])){
	       		$msg['code'] = 1;
				$msg['message'] = '发送成功';
	       	}else{
				$msg['code'] = $sms['errorCode'];
				$msg['message'] = $sms['errorMessage'];
	       	}
	    $this->redirect("index");
       }
	}

	private function is_phone_check($phone = null){
	   $reg = '/^\S?(?:1(?:\D)?)?(?:\((?=\d{3}\)))?(?P<p1>[0-9]\d{2})(?:(?<=\(\d{3})\))?(\D{1,2})?(?:(?<=\d{2})\D)?(?P<p2>[0-9]\d{2})\D?(?P<p3>\d{4})/';
	   $is_match = preg_match($reg, $phone, $matches);
		   if($is_match){
		        return '+1'.$matches['p1'] . $matches['p2']. $matches['p3'];
		   }
	   return false;
	}
}
?>