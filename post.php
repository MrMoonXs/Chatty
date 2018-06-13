<?php
	session_start();
	if(isset($_SESSION['name'])){
		$text = $_POST['text'];
		
		$fp = fopen("log.html", 'a');
		fwrite($fp, "<div class='msgln'>(".date("g:i A").") <b>".$_SESSION['name']."</b> says: ".stripslashes(htmlspecialchars($text))." <br /></div>". PHP_EOL ."");
		
		fclose($fp);
	}
?>