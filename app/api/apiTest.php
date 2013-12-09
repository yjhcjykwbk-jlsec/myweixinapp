<?php
include "../lib/functions.php";
$debug=true;
echo "test simi:<br/>";
require "simSimi.php";
echo "input->test<br/>";
$type="text";
echo simSimi("test",&$type);
?>
