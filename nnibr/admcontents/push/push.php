<?PHP
include "_da.php";
function sendMessage($eng_title, $eng_message, $kor_title, $kor_message){
	$title = array(
		"en" => $eng_title,
		"ko" => $kor_title
		);

	$content = array(
		"en" => $eng_message,
		"ko" => $kor_message
		);
	
	/*
	실제앱
	'app_id' => "de90c3bd-b103-46d9-b347-17178141b425",
	*/

		/* 테스트
		'app_id' => "145831d2-5807-41dc-8875-46329227cfdc",
		*/	
	$fields = array(
		'app_id' => "145831d2-5807-41dc-8875-46329227cfdc",		
		'included_segments' => array('All'),
		//'data' => array("foo" => "bar"),
		'headings' => $title,
		'contents' => $content
	);
	
	$fields = json_encode($fields);
	/*
	print("\nJSON sent:\n");
	print($fields);
	*/
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");

	/* 실제앱
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												'Authorization: Basic ZDg4NTg5MzMtYTFlNC00MTgzLWE5MDctZWJiM2I5NzhjYWU4'));
	*/
	/* 테스트 앱 */
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												'Authorization: Basic ZDkwMWUwYTAtNDUxZi00NWRjLWFkNmItZmUzM2RlMjM1ZGYw'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$response = curl_exec($ch);
	curl_close($ch);
	
	return $response;
}

$response = sendMessage($e_tit, $e_pmsg, $k_tit, $k_pmsg);
if(array_key_exists("error", json_decode($response)))
	goto_url("push_write.html?error=1");
else
	goto_url("push_write.html?success=1");
?>