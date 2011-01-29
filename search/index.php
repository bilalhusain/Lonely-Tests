<?php
	function searchTests($tag)
	{
		$searchStr = str_replace("'", "''", stripslashes($tag));
		$link = mysql_connect("localhost", "bilalhus_locuzr", "myS33kl");
		mysql_select_db("bilalhus_localdb", $link);
		$query = "select Id, Question from LonelyTest where Tags like '%".$searchStr."%' order by Used desc"; // no limit // Options, Tags not required for the time being

		$result = mysql_query($query, $link);
		while ($row = mysql_fetch_assoc($result)) {
			echo "<div>".$row['Question']."<a href='../take/?test=".$row['Id']."' class='buttonlike'>Take Test</a></div>";
		}

		// done, dispose
		mysql_free_result($result);
		mysql_close();
	}

	if ($_GET["q"]) {
		searchTests($_GET["q"]);
	}
?>

