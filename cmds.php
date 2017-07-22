<?php
function message_processing($chan, $text){
	global $fp;
	fwrite($fp, "PRIVMSG $chan :$text\r\n");
	echo "(".date("H:i:s").") $chan| Bot: $text.\n";
}
function iCMD_pattern($cmd){
	return "/.*!$cmd.*/mi";
}
function inc_message($nick, $chan, $msg) {
		if (preg_match(iCMD_pattern("почта"), $msg)) {
                sleep(1);
                $mailMsg = substr($msg, 12);
				$file = "./jsons/mail.json";
				$superdecodedjsonfile = json_decode(file_get_contents($file), true);
				$mailID = $superdecodedjsonfile["id"]+1;
				echo "(".date("H:i:s").") [$mailID] Отправлена почта от $nick: $mailMsg\n";
                $extraMail = array(
                    'id' => $mailID,
                    'nick' => $nick,
                    'message' => $mailMsg
                );
                $mailData = json_encode($extraMail);
                file_put_contents("./jsons/mail.json", $mailData);
				sleep(10);
				message_processing($chan, "@".$nick." , твоя почта была отправлена, cпасибо за обращение!");
		}
        //else{ echo "(".date("H:i:s").") $chan| <$nick>: $msg\n"; }
}
?>