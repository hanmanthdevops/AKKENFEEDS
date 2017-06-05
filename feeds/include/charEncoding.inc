<?php
function charEncoding($text)
{
	$chars = array(130=>',',131=>'NLG',132=>'"',133=>'...',134=>'**',135=>'***',136=>'^',137=>'o/oo',138=>'Sh',139=>'<',140=>'OE',145=>'\'',146=>'\'',147=>'"',148=>'"',149=>'-',150=>'-',151=>'--',152=>'~',153=>'(TM)',154=>'sh',155=>'>',156=>'oe',159=>'Y',169=>'(C)',174=>'(R)');

	foreach ($chars as $chr => $replace)
		$text = str_replace(chr($chr), $replace, $text);

	for($i=160;$i<=255;$i++)
		$text = str_replace(chr($i), '', $text);

	for($i=0;$i<=8;$i++)
		$text = str_replace(chr($i), '', $text);

	for($i=11;$i<=31;$i++)
		$text = str_replace(chr($i), '', $text);

	return $text;
}
?>