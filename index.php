<?php
	// display the popular 10 tests
	// this will be a better but more code if moved to SimpleDB, Java app
	function pullPopularTests()
	{
		$link = mysql_connect("localhost", "_USER_", "_PASSWORD_");
		mysql_select_db("_DB_", $link);
		$query = "select Id, Question, Options, Tags from LonelyTest order by Used desc limit 10";

		$result = mysql_query($query, $link);
		while ($row = mysql_fetch_assoc($result)) {
			echo "<div class='question'>";
			echo "<span onclick='toggleOptionsAndTags(this)'>[+]</span>";
			echo $row['Question']."<a href='take/?test=".$row['Id']."' class='buttonlike'>Take Test</a>";
			echo "<div class='options'>".$row['Options']."</div>";
			echo "<div class='tags'>".$row['Tags']."</div>";
			echo "</div>";
		}

		// done, dispose
		mysql_free_result($result);
		mysql_close();
	}
?>
<!doctype html>
<html>
	<head>
		<title>Lonely Tests</title>
		<style type="text/css">
			.buttonlike { background-color: #48E; margin: 2px; font-weight: bold; color: #303; font-variant: small-caps; border: thin outset blue; padding: 2px; text-decoration: none }
			.question { margin: 10px; padding: 5px; border: thin solid #658; font-size: 16px; font-family: Verdana; background-color: #A9F }
			.options { display: none; font-style: italic }
			.options:before { content: "Options: " }
			.tags { display: none; font-style: italic }
			.tags:before { content: "Tags: " }
		</style>
		<script type="text/javascript">
			function toggleOptionsAndTags(questionElement) {
				var parentElement = questionElement.parentNode;
				var optionsDiv = parentElement.getElementsByClassName("options")[0];
				optionsDiv.style.display = (optionsDiv.style.display == "") ? "block" : "";
				var tagsDiv = parentElement.getElementsByClassName("tags")[0];
				tagsDiv.style.display = (tagsDiv.style.display == "") ? "block" : "";
				questionElement.innerHTML = (questionElement.innerHTML == "[+]") ? "[-]" : "[+]";
			}
		</script>
	</head>
	<body>
		<section>
			<div style="float: right"><form action="search/" method="GET"><input name="q" type="text"></input><input type="submit" value="Search Test"></input></form></div>
			<div style="clear: both" />
		</section>

		<section>
			<h2>Existing tests</h2>
			<p>Note that if you have allowed the access to application, clicking on take test will post a message on your wall without confirmation</p>
			<?php pullPopularTests() ?>
		</section>

		<section>
			<h2>Create your own lonely test</h2>
			<a href="create/" class="buttonlike">New Test</a>
		</section>
	</body>
</html>
