<?php
date_default_timezone_set("Europe/Moscow");
require("config.php");
require("messages.php");
require("cmds.php");
$fp = fsockopen($server, $port, $errno, $errstr);
if (!$fp) {echo "Error: $errno - $errstr\n"; exit;}
fwrite($fp, "PASS ".$password."\r\n");
fwrite($fp, "NICK ".$nick."\r\n");
while (!preg_match("/:\S+ 376 \S+ :.*/i", $read)) {
	$read = fgets($fp);
}
foreach($channels as $num => $chan) {
	fwrite($fp, "JOIN $chan\r\n");
}
fwrite("CAP REQ :twitch.tv/commands");
stream_set_timeout($fp, 1);
echo "Bot started successfuly!\n";
while (TRUE){
        $read = fgets($fp);
		echo "(".date("H:i:s").") ";
		print_r(stream_get_meta_data($fp));
		echo "\n";
		echo "(".date("H:i:s").") IRC: $read\n";
        if (preg_match("/:(\S+)!\S+@\S+ JOIN (#\S+)/i", $read, $match)) {
            user_joined($match[1], $match[2]);
        }
        if (preg_match("/:(\S+)!\S+@\S+ PART (#\S+)/i", $read, $match)) {
            user_parted($match[1], $match[2]);
        }
        if (preg_match("/:(\S+)!\S+@\S+ PRIVMSG (#\S+) :(.*)/i", $read, $match)) {
            inc_message($match[1], $match[2], $match[3]);
        }
        if (preg_match("/:jtv!jtv@\S+ PRIVMSG $nick :(\S+)/i", $read, $match)) {
            jtv_error($match[1]);
        }
        if (preg_match("/PING :.*/i", $read, $match)) {
            fwrite($fp, "PONG :tmi.twitch.tv\r\n");
            echo "(".date("H:i:s").") Bot ping-ponged!\n";
        }
        if (preg_match("/RECONNECT :.*/i", $read, $match)) {
            fwrite($fp, "PONG :tmi.twitch.tv\r\n");
            echo "(".date("H:i:s").") Bot reconnected!\n";
        }
		if (!feof($fp)) continue;
        sleep(1);
    }
function user_joined($nick, $chan) {
	global $users;
	$users[$chan][] = $nick;
	echo "(".date("H:i:s").") Bot joined {$chan}.\n";
}

function user_parted($nick, $chan) {
	global $users;
	$num = array_search($nick, $users[$chan]);
	if ($num !== FALSE) { unset($users[$chan][$num]); }
	echo "(".date('H:i:s').") Bot parted {$chan}.\n";
}
function jtv_error($msg) {
	echo "(".date('H:i:s').") Message from jtv: $msg\n";
}

function sendServer($msg){
	fwrite($fp, $msg."\r\n");
}

?>