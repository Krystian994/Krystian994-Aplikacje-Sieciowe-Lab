<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
<head>
    <meta charset="utf-8" />
    <title>Kalkulator kredytowy</title>
</head>
<body>
        <form action="<?php print(_APP_URL);?>/app/calc.php" method="post">
			<label for="id_x">Wartość kredytu: </label>
			<input id="id_x" type="text" name="x" value="<?php if(isset($x)) print($x); ?>" /><br /><br />
			<label for="id_liczbaMiesiecy">Ilość miesięcy: </label>
			<input id="id_liczbaMiesiecy" type="text" name="liczbaMiesiecy" value="<?php if(isset($liczbaMiesiecy)) print($liczbaMiesiecy); ?>" /><br /><br />
			<label for="id_oprocentowanie">Stopa oprocentowania: </label>
			<input id="id_oprocentowanie" type="text" name="oprocentowanie" value="<?php if(isset($oprocentowanie)) print($oprocentowanie); ?>" /><br /><br />
                        <input type="submit" value="Oblicz" />
	</form>	
	<?php 
	//wyświeltenie listy błędów, jeśli istnieją
	if (isset($messages)) {
			echo '<ol style="margin: 20px; padding: 10px 10px 10px 30px; border-radius: 5px; background-color: #f88; width:300px;">';
			foreach ( $messages as $key => $msg ) {
				echo '<li>'.$msg.'</li>';
			}
			echo '</ol>';
	}
	?>

	<?php if (isset($result)){ ?>
	<div style="margin: 20px; padding: 10px; border-radius: 5px; background-color: #ff0; width:300px;">
	<?php echo 'Miesięczna rata kredytu: '.$result; ?>
	</div>
	<?php } ?>
</div>


</body>
</html>