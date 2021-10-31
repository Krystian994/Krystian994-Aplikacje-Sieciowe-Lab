<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';
//załaduj Smarty
require_once _ROOT_PATH.'/libs/Smarty.class.php';

//pobranie parametrów
function getParams(&$form){
	$form['x'] = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
	$form['liczbaMiesiecy'] = isset($_REQUEST['liczbaMiesiecy']) ? $_REQUEST['liczbaMiesiecy'] : null;
	$form['oprocentowanie'] = isset($_REQUEST['oprocentowanie']) ? $_REQUEST['oprocentowanie'] : null;
}

//walidacja parametrów z przygotowaniem zmiennych dla widoku
function validate(&$form,&$infos,&$msgs,&$hide_intro){

	//sprawdzenie, czy parametry zostały przekazane - jeśli nie to zakończ walidację
	if ( ! (isset($form['x']) && isset($form['liczbaMiesiecy']) && isset($form['oprocentowanie']) ))    return false;
	
	//parametry przekazane zatem
	//nie pokazuj wstępu strony gdy tryb obliczeń (aby nie trzeba było przesuwać)
	// - ta zmienna zostanie użyta w widoku aby nie wyświetlać całego bloku itro z tłem 
	$hide_intro = true;

	$infos [] = 'Przekazano parametry.';

	// sprawdzenie, czy potrzebne wartości zostały przekazane
	if ( $form['x'] == "") $msgs [] = 'Nie podano wartości kredytu!';
    if ( $form['oprocentowanie'] == "") $msgs [] = 'Nie podano oprocentowania!';
    if ( $form['liczbaMiesiecy'] == "") $msgs [] = 'Nie podano ilości miesięcy!';
	
	//nie ma sensu walidować dalej gdy brak parametrów
	if ( count($msgs)==0 ) {
		// sprawdzenie, czy $x i $y są liczbami całkowitymi
		if ((! is_numeric( $form['x'] ))||($form['x'] <= 0)) $msgs [] = 'Wprowadzono niepoprawną wartość kredytu!';
		if ((! is_numeric( $form['oprocentowanie'] ))||($form['oprocentowanie'] < 0)) $msgs [] = 'Wprowadzono niepoprawne oprocentowanie!';
        if ((! is_numeric( $form['liczbaMiesiecy'] ))||($form['liczbaMiesiecy'] <= 0)) $msgs [] = 'Wprowadzono niepoprawną ilość miesięcy!';
    }
	
	if (count($msgs)>0) return false;
	else return true;
}
	
// wykonaj obliczenia
function process(&$form,&$infos,&$msgs,&$result){
    global $role;

    $infos [] = 'Parametry poprawne. Wykonuję obliczenia.';
	
	//konwersja parametrów na int
	$form['x'] = floatval($form['x']);
	$form['oprocentowanie'] = floatval($form['oprocentowanie']);
    $form['liczbaMiesiecy'] = intval($form['liczbaMiesiecy']);

    //wykonanie operacji
	switch ($form['x']) {
        case ($form['x']>99999):
            if ($role == 'admin') {
                $poprocentowanie = ($form['oprocentowanie'])/100;
                $px = (($form['x']) * $poprocentowanie) + ($form['x']);
                $result = $px/($form['liczbaMiesiecy']);
            }else{
                $msgs[] = 'Tylko administrator może wykonać operacje na tak wysokiej kwocie !';
            }
            break;
        default :
            $poprocentowanie = ($form['oprocentowanie'])/100;
            $px = (($form['x']) * $poprocentowanie) + ($form['x']);
            $result = $px/($form['liczbaMiesiecy']);
            break;
	}
}

//inicjacja zmiennych
$form = null;
$infos = array();
$messages = array();
$result = null;
	
getParams($form);
if ( validate($form,$infos,$messages,$hide_intro) ){
	process($form,$infos,$messages,$result);
}

// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Aplikacja');
$smarty->assign('page_description','Szablonowania Smarty');
$smarty->assign('page_header','Kalkulator kredytowy');

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');