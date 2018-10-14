<?php

require __DIR__ . "/../Emojify.inc.php";

$emojify = new Emojify();
$text = "Hey, I just :raising_hand: you, and this is :scream: , but here's my :calling: , so :telephone_receiver: me, maybe?";
$encoded = $emojify->encode($text);

echo $text . "\n";
echo $encoded . "\n";
echo $emojify->decode($encoded) . "\n";
