<?php
// W skrypcie definicji kontrolera nie trzeba dołączać problematycznego skryptu config.php,
// ponieważ będzie on użyty w miejscach, gdzie config.php zostanie już wywołany.

require_once $conf->root_path.'/libs/smarty/Smarty.class.php';
require_once $conf->root_path.'/libs/Messages.class.php';
require_once $conf->root_path.'/app/CalcForm.class.php';
require_once $conf->root_path.'/app/CalcResult.class.php';

/** Kontroler kalkulatora
 * @author Krystian Szewczyk
 *
 */
class CalcCtrl {

	private $msgs;   //wiadomości dla widoku
	private $form;   //dane formularza (do obliczeń i dla widoku)
	private $result; //inne dane dla widoku
	private $hide_intro; //zmienna informująca o tym czy schować intro

	/** 
	 * Konstruktor - inicjalizacja właściwości
	 */
	public function __construct(){
		//stworzenie potrzebnych obiektów
		$this->msgs = new Messages();
		$this->form = new CalcForm();
		$this->result = new CalcResult();
		$this->hide_intro = false;
	}
	
	/** 
	 * Pobranie parametrów
	 */
	public function getParams(){
		$this->form->x = isset($_REQUEST ['x']) ? $_REQUEST ['x'] : null;
		$this->form->liczbaMiesiecy = isset($_REQUEST ['liczbaMiesiecy']) ? $_REQUEST ['liczbaMiesiecy'] : null;
		$this->form->oprocentowanie = isset($_REQUEST ['oprocentowanie']) ? $_REQUEST ['oprocentowanie'] : null;
                $this->form->op = isset($_REQUEST ['op']) ? $_REQUEST ['op'] : null;
	}
	
	/** 
	 * Walidacja parametrów
	 * @return true jeśli brak błedów, false w przeciwnym wypadku 
	 */
	public function validate() {
		// sprawdzenie, czy parametry zostały przekazane
		if (! (isset ( $this->form->x ) && isset ( $this->form->liczbaMiesiecy ) && isset ( $this->form->oprocentowanie ))) {
			// sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
			return false; //zakończ walidację z błędem
		} else { 
			$this->hide_intro = true; //przyszły pola formularza, więc - schowaj wstęp
		}
		
		// sprawdzenie, czy potrzebne wartości zostały przekazane
		if ($this->form->x == "") {
			$this->msgs->addError('Nie podano wartości kredytu!');
		}
		if ($this->form->oprocentowanie == "") {
			$this->msgs->addError('Nie podano oprocentowania!');
		}
		if ($this->form->liczbaMiesiecy == "") {
			$this->msgs->addError('Nie podano ilości miesięcy!');
		}
		// nie ma sensu walidować dalej gdy brak parametrów
		if (! $this->msgs->isError()) {
			
			// sprawdzenie, czy $x i $y są liczbami całkowitymi
			if ((! is_numeric ( $this->form->x ))||($this->form->x <= 0)) {
				$this->msgs->addError('Wprowadzono niepoprawną wartość kredytu!');
			}
			
			if ((! is_numeric ( $this->form->oprocentowanie ))||($this->form->oprocentowanie < 0)) {
				$this->msgs->addError('Wprowadzono niepoprawne oprocentowanie!');
			}
                        if ((! is_numeric ( $this->form->liczbaMiesiecy ))||($this->form->liczbaMiesiecy <= 0)) {
				$this->msgs->addError('Wprowadzono niepoprawną ilość miesięcy!');
			}
		}
		
		return ! $this->msgs->isError();
	}
	
	/** 
	 * Pobranie wartości, walidacja, obliczenie i wyświetlenie
	 */
	public function process(){

		$this->getparams();
		
		if ($this->validate()) {
				
			//konwersja parametrów na int
			$this->form->x = floatval($this->form->x);
			$this->form->oprocentowanie = floatval($this->form->oprocentowanie);
                        $this->form->liczbaMiesiecy = intval($this->form->liczbaMiesiecy);
			$this->msgs->addInfo('Parametry poprawne. Wykonuję obliczenia.');
				
			//wykonanie operacji
			switch ($this->form->x) {
                        case ($this->form->x>99999):
                            if ($role == 'admin') {
                                $poprocentowanie = ($this->form->oprocentowanie)/100;
                                $px = (($this->form->x) * $poprocentowanie) + ($this->form->x);
                                $this->result->result = $px/($this->form->liczbaMiesiecy);
                            }else{
                                $msgs[] = 'Tylko administrator może wykonać operacje na tak wysokiej kwocie !';
                                 }
                        break;
                        default :
                                $poprocentowanie = ($this->form->oprocentowanie)/100;
                                $px = (($this->form->x) * $poprocentowanie) + ($this->form->x);
                                $this->result->result = $px/($this->form->liczbaMiesiecy);
                        break;
                                            }
			
                                $this->msgs->addInfo('Wykonano obliczenia.');
                                        }
		
                                $this->generateView();
                            }
	
	
	/**
	 * Wygenerowanie widoku
	 */
	public function generateView(){
		global $conf;
		
		$smarty = new Smarty();
		$smarty->assign('conf',$conf);
		
                $smarty->assign('page_title','Aplikacja');
                $smarty->assign('page_description','Szablonowania Smarty');
                $smarty->assign('page_header','Kalkulator kredytowy');
				
		$smarty->assign('hide_intro',$this->hide_intro);
		
		$smarty->assign('msgs',$this->msgs);
		$smarty->assign('form',$this->form);
		$smarty->assign('res',$this->result);
		
		$smarty->display($conf->root_path.'/app/CalcView.html');
	}
}
