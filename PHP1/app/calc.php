<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

// 1. pobranie parametrów

$x = $_REQUEST ['x'];
$oprocentowanie = $_REQUEST ['oprocentowanie'];
$liczbaMiesiecy = $_REQUEST ['liczbaMiesiecy'];

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

// sprawdzenie, czy parametry zostały przekazane
if ( ! (isset($x) && isset($oprocentowanie) && isset($liczbaMiesiecy))) {
	$messages [] = 'Błędne wywołanie aplikacji. Brak jednego z parametrów.';
}

// sprawdzenie, czy potrzebne wartości zostały przekazane
if ( $x == "") {
	$messages [] = 'Nie podano wartości kredytu!';
}
if ( $oprocentowanie == "") {
	$messages [] = 'Nie podano oprocentowania!';
}
if ( $liczbaMiesiecy == "") {
	$messages [] = 'Nie podano ilości miesięcy!';
}

//nie ma sensu walidować dalej gdy brak parametrów
if (empty( $messages )) {
	
	// sprawdzenie, czy $x i $y są liczbami całkowitymi
	if ((! is_numeric( $x ))||($x <= 0)) {
		$messages [] = 'Wprowadzono niepoprawną wartość kredytu!';
	}
	
	if ((! is_numeric( $oprocentowanie )) || ($oprocentowanie < 0)) {
		$messages [] = 'Wprowadzono niepoprawne oprocentowanie!';
	}	
		
	if ( (! is_numeric( $liczbaMiesiecy ))||($liczbaMiesiecy <= 0)) {
		$messages [] = 'Wprowadzono niepoprawną ilość miesięcy!';
	}

}

// 3. wykonaj zadanie jeśli wszystko w porządku

if (empty ( $messages )) { // gdy brak błędów
	
	//konwersja parametrów na int i float
	$x = intval($x);
	$oprocentowanie = floatval($oprocentowanie);
	$liczbaMiesiecy = intval($liczbaMiesiecy);

	$poprocentowanie = $oprocentowanie/100;
	$px = ($x * $poprocentowanie) + $x;
	$result = $px/$liczbaMiesiecy;
	
}

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';