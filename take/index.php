<?php
	// This app will post a message on the logged in user's wall that he/she just visited bilalhusain.com

	require '../../facebook-php-sdk/src/facebook.php';
	require '../lonelytest-sdk/util.php';

	$facebook = new Facebook(array(
		'appId' => '176699202371684',
		'secret' => '3ac7c44e09ea40dfafe6818648a444b7',
		'cookie' => true,
	));

	$session = $facebook->getSession();

	if ($session) {
		try {
			$uid = $facebook->getUser();
		} catch (FacebookApiException $fbae) {
			error_log($fbae);
		}
	}

	// post the msg
	// TODO figure out the limit per user for wall post
	function postMsg($facebook, $uid) {

 	        $test = intval($_GET["test"]);
        	if ($test <= 0) {
			return;
		}

                $link = mysql_connect("localhost", "bilalhus_locuzr", "myS33kl");
                mysql_select_db("bilalhus_localdb", $link);
                $query = "select Question, Options from LonelyTest where Id = ".$test; // Tags not required for the time being

                $result = mysql_query($query, $link);
                if ($result) {
                        $row = mysql_fetch_assoc($result);
                        $optionsArray = SplitOptions($row['Options']);
			$testResult = $optionsArray[rand(0, count($optionsArray) - 1)];
                        $msg = $row['Question']."\nAnswer: ".$testResult;
                        mysql_free_result($result);

                	$updateQuery = "update LonelyTest set Used = Used + 1 where Id = ".$test;
			$updateResult = mysql_query($updateQuery, $link);
                }


                // done, dispose
                mysql_close();

		if (!isset($testResult)) {
			echo "Failed to post the message on your wall";
			return;
		}

		$attachment = array(
			'message' => $msg,
			'name' => 'via lonelytests @ bilalhusain.com',
			'link' => 'http://www.bilalhusain.com/facebook/lonelytests',
			'description' => 'Lonely Test | bilalhusain.com',
			'picture' => 'http://www.bilalhusain.com/favicon.ico',
		);
		$facebook->api('/'.$uid.'/feed', 'POST', $attachment);

		echo "<b>".$testResult."</b><br />".$msg."<br />Result also posted to your wall. Hope you don't mind :(";
	}
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
	<head>
		<title>Lonely Tests | bilalhusain.com</title>
		<style type="text/css">
			body { font-size: 12px; font-family: Serif }
		</style>
	</head>
	<body>
		<div id="fb-root"></div>
		<script language="javascript">
			window.fbAsyncInit = function() {
				FB.init({
					appId : '<?php echo $facebook->getAppId(); ?>',
					session: <?php echo json_encode($session); ?>,
					status: true, // login status
					cookie: true,
					xfbml: true
				});

				FB.Event.subscribe('auth.login', function() {
					window.location.reload();
				});
			};

			(function () {
				var scriptElement = document.createElement('script');
				scriptElement.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
				scriptElement.async = true;
				document.getElementById('fb-root').appendChild(scriptElement);
			}());
		</script>

<?php if ($uid): ?>
		<a href="<?php echo $facebook->getLogoutUrl; ?>"><img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif"></a>
		<?php postMsg($facebook, $uid); ?>
<?php else: ?>
		<fb:login-button perms="publish_stream"></fb:login-button>
		<div>You gotta connect to use the application</div>
<?php endif ?>

	</body>
</html>

