<?php
	session_start();

	$validPost = $_POST["question"] && $_POST["options"] && $_POST["tags"] && $_POST["captcha"];
	$invalidCaptcha = (empty($_SESSION['security_number']) || ($_SESSION['security_number'] != $_POST["captcha"]));

	if ($validPost && $invalidCaptcha) {
		$added = "invalid captcha";
	} else if ($validPost) {
		$question = str_replace("'", "''", stripslashes($_POST["question"]));
		$options = str_replace("'", "''", stripslashes($_POST["options"]));
		$tags = str_replace("'", "''", stripslashes($_POST["tags"]));
		$query = "insert into LonelyTest (Question, Options, Tags) values ('".$question."', '".$options."', '".$tags."')";

                $link = mysql_connect("localhost", "bilalhus_locuzr", "myS33kl");
                mysql_select_db("bilalhus_localdb", $link);

                $result = mysql_query($query, $link);
                if ($result) {
			$added = "Added <i>".$_POST["question"]."</i>";
                } else {
			$added = "failed to add test :(";
		}

                // done, dispose
                mysql_close();
		//session_unset();
		//session_destroy();
		// for next use
		$_SESSION['security_number'] = rand(10000,99999);
	} else {
		$_SESSION['security_number'] = rand(10000,99999);
	}
?>
<!doctype html>
<html>
	<head>
		<title>Create new test</title>
		<style type="text/css">
			div { margin: 10px }
		</style>
	</head>
	<body>
<?php if ($added) { echo $added; } ?>
		<form method='POST'>
			<fieldset>
				<legend>Add a lonely test</legend>
				<div><label for='question'>Question</label><input name='question' type='text' size='100'></input>Eg, <i> Which is your favourite color?</i></div>
				<div><label for='options'>Options (pipe separated)</label><input name='options' type='text' size='70'></input> Eg, <i>red|blue|green</i></div>
				<div><label for='tags'>Tags (space separated)</label><input name='tags' type='text' size='50'></input> Eg, <i>color hue</i></div>
				<div><img src="../uHuman.php" alt="captcha"></img><input name="captcha" type="text"></input></div>
				<div><input type="submit" value="Create"></input></div>
			</fieldset>
		</form>
		<div>Currently you can't do anything about an option involving the pipe character</div>
	</body>
</html>

