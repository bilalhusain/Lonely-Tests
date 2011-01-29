<?php

function SplitOptions($optionsSerialized) {
	return split("\|", $optionsSerialized);
}

function SplitTags($tagsSerialized) {
	return split(" ", $optionsSerialized);
}

?>

