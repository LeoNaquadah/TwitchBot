<?php
function MIConsole($nick, $chan, $msg) {
	global $fp, $users;
	if($chan != "#melharucos"){
		switch($chan)
		{
			case "#cwelth":
			{
				echo "(".date('H:i:s').") ЗИГ| <$nick>: $msg\n";
				break;
			}
			case "#mrakovey":
			{
				echo "(".date('H:i:s').") МРАК| <$nick>: $msg\n";
				break;
			}
			case "#winegearplay":
			{
				echo "(".date('H:i:s').") ВАЙНГИР| <$nick>: $msg\n";
				break;
			}
			case "#prayda_alpha":
			{
				echo "(".date('H:i:s').") ПРАЙДА| <$nick>: $msg\n";
				break;
			}
			default:
			{
				echo "(".date('H:i:s').") $chan| <$nick>: $msg\n";
			}
		}
	}
}
?>