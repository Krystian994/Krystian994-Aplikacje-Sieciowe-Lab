<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';

// pobranie parametrów
function getParams(&$x,&$oprocentowanie,&$liczbaMiesiecy){
$x = isset($_REQUEST ['x'])?$_REQUEST['x'] : null;
$oprocentowanie = isset($_REQUEST ['oprocentowanie'])?$_REQUEST['oprocentowanie'] : null;
$liczbaMiesiecy = isset($_REQUEST ['liczbaMiesiecy'])?$_REQUEST['liczbaMiesiecy'] : null;
}


//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$x,&$oprocentowanie,&$liczbaMiesiecy,&$messages){
    //sprawdzenie, czy parametry zostały przekazane
    if ( ! (isset($x) && isset($oprocentowanie) && isset($liczbaMiesiecy))) {
            //sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
            return false;
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
    if (count( $messages )!= 0) { 
        return false;
        
    }
   
            // sprawdzenie, czy $loanValue i $interestRate są liczbami całkowitymi
            if ((! is_numeric( $x ))||($x <= 0)) {
                    $messages [] = 'Wprowadzono niepoprawną wartość kredytu!';
            }
            if ((! is_numeric( $oprocentowanie )) || ($oprocentowanie < 0)) {
                    $messages [] = 'Wprowadzono niepoprawne oprocentowanie!';
            }	
            if ( (! is_numeric( $liczbaMiesiecy ))||($liczbaMiesiecy <= 0)) {
                    $messages [] = 'Wprowadzono niepoprawną ilość miesięcy!';
            }
        if (count ( $messages ) != 0) {
            return false;
            
        }else{
            return true;
            
        }
    }


function process(&$x,&$oprocentowanie,&$liczbaMiesiecy,&$messages,&$result){
       global $role;
    
    //konwersja parametrów na int i float
	$x = intval($x);
	$oprocentowanie = floatval($oprocentowanie);
	$liczbaMiesiecy = intval($liczbaMiesiecy);
        
        //wykonanie operacji
	switch ($x) {
		case ($x>99999):
			if ($role == 'admin'){
				$poprocentowanie = $oprocentowanie/100;
                                $px = ($x * $poprocentowanie) + $x;
                                $result = $px/$liczbaMiesiecy;
			} else {
				$messages [] = 'Tylko administrator może tak wysoką kwotę ustawić !';
			}
			break;
		default :
			$poprocentowanie = $oprocentowanie/100;
                        $px = ($x * $poprocentowanie) + $x;
                        $result = $px/$liczbaMiesiecy;
			break;
	}
}
  //definicja zmiennych kontrolera
        $x = null;
        $oprocentowanie = null;
        $liczbaMiesiecy = null;
        $result = null;
        $messages = array();
        
//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($x,$oprocentowanie,$liczbaMiesiecy);
if ( validate($x,$oprocentowanie,$liczbaMiesiecy,$messages) ) { // gdy brak błędów
	process($x,$oprocentowanie,$liczbaMiesiecy,$messages,$result);
}


//Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$oprocentowanie,$liczbaMiesiecy,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';