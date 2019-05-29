<?php
	class CodiceFiscale{
		public function __construct(){
			//
		}
		private function calcolaNome($nome){
			$vocali = ["A", "E", "I", "O", "U"];
			$nome = str_replace(" ", "", $nome);
			$poscons = [];
			$posvoc = [];
			$cntcons = 0;
			$realn = "";
			for ($i=0; $i < strlen($nome); $i++) {
				if(!in_array(mb_strtoupper($nome[$i]), $vocali))
					array_push($poscons, $nome[$i]);
				else
					array_push($posvoc, $nome[$i]);
			}

			$cnt1 = count($poscons);
			$cnt2 = count($posvoc);
			if($cnt1 == 0 && $cnt2 >= 3)
				$realn = $posvoc[0].$posvoc[1].$posvoc[2];
			else if($cnt1 == 0 && $cnt2 < 3){
				$cnt = 0;
				for ($i=0; $i < $cnt2; $i++) { 
					$realn .= mb_strtoupper($posvoc[$i]);
					$cnt++;
				}
				$tot = 3 - $cnt;
				if($tot != 0){
					for ($i=0; $i < $tot; $i++) { 
						$realn .= "X";
					}
				}
			}
			else if($cnt1 < 3 && $cnt2 != 0){
				$cnt = 0;
				for ($i=0; $i < $cnt1; $i++) { 
					$realn .= mb_strtoupper($poscons[$i]);
					$cnt++;
				}
				for ($i=0; $i < $cnt2; $i++) {
					if($cnt < 3)
						$realn .= mb_strtoupper($posvoc[$i]);
					$cnt++;
				}
				$tot = 3 - $cnt;
				if($tot != 0){
					for ($i=0; $i < $tot; $i++) { 
						$realn .= "X";
					}
				}
			}
			else{
				if(count($poscons) >= 4)
					$realn = mb_strtoupper($poscons[0]).mb_strtoupper($poscons[2]).mb_strtoupper($poscons[3]);
				else
					$realn = mb_strtoupper($poscons[0]).mb_strtoupper($poscons[1]).mb_strtoupper($poscons[2]);
			}
			return $realn;
		}
		private function calcolaCognome($cognome){
			$vocali = ["A", "E", "I", "O", "U"];
			$poscons = [];
			$posvoc = [];
			$cntcons = 0;
			$realcogn = "";
			for ($i=0; $i < strlen($cognome); $i++) {
				if(!in_array(mb_strtoupper($cognome[$i]), $vocali))
					array_push($poscons, $cognome[$i]);
				else
					array_push($posvoc, $cognome[$i]);
			}
			$cnt1 = count($poscons);
			$cnt2 = count($posvoc);
			if($cnt1 == 0 && $cnt2 >= 3)
				$realcogn = $posvoc[0].$posvoc[1].$posvoc[2];
			else if($cnt1 == 0 && $cnt2 < 3){
				$cnt = 0;
				for ($i=0; $i < $cnt2; $i++) { 
					$realcogn .= mb_strtoupper($posvoc[$i]);
					$cnt++;
				}
				$tot = 3 - $cnt;
				if($tot != 0){
					for ($i=0; $i < $tot; $i++) { 
						$realcogn .= "X";
					}
				}
			}
			else if($cnt1 < 3 && $cnt2 != 0){
				$cnt = 0;
				for ($i=0; $i < $cnt1; $i++) { 
					$realcogn .= mb_strtoupper($poscons[$i]);
					$cnt++;
				}
				for ($i=0; $i < $cnt2; $i++) {
					if($cnt < 3)
						$realcogn .= mb_strtoupper($posvoc[$i]);
					$cnt++;
				}
				$tot = 3 - $cnt;
				if($tot != 0){
					for ($i=0; $i < $tot; $i++) { 
						$realcogn .= "X";
					}
				}
			}
			else{
				$realcogn = mb_strtoupper($poscons[0]).mb_strtoupper($poscons[1]).mb_strtoupper($poscons[2]);
			}
			return $realcogn;
		}
		private function calcolaAnno($anno){
			if(strlen($anno) == 4)
				$anno = $anno[2].$anno[3];
			return $anno;
		}
		private function calcolaMese($mese){
			$tabMese = [
				"01" => "A",
				"02" => "B",
				"03" => "C",
				"04" => "D",
				"05" => "E",
				"06" => "H",
				"07" => "L",
				"08" => "M",
				"09" => "P",
				"10" => "R",
				"11" => "S",
				"12" => "T"
			];
			$mese = (string) $mese;
			if(strlen($mese) == 1)
				$mese = "0$mese";
			if(!isset($tabMese[$mese]))
				return "Errore!";
			else
				return $tabMese[$mese];
		}
		private function calcolaGiorno($sesso, $giorno){
			$sesso = strtoupper($sesso);
			$realgiorno = $giorno;
			if($sesso == "F")
				$realgiorno += 40;
			return $realgiorno;
		}
		private function calcolaCatastale($comune){
			$json = json_decode(file_get_contents("comuni.json"), true);
			$catastale = "";
			for ($i=0; $i < count($json); $i++) {
				if(mb_strtoupper($json[$i]["nome"]) == mb_strtoupper($comune)){
					$catastale = $json[$i]["codiceCatastale"];
					break;
				}
			}
			if($catastale == ""){
				$json = json_decode(file_get_contents("Nazioni.json"), true);
				$tmp = str_replace(" ", "", mb_strtoupper($comune));
				for ($i=0; $i < count($json); $i++) { 
					if(isset($json[$tmp])){
						$catastale = $json[$tmp]["code"];
						break;
					}
				}
			}
			return $catastale;
		}
		private function calcolaControllo($cognome, $nome, $anno, $mese, $sesso, $giorno, $comune){
			$cf = $this->calcolaCognome($cognome).$this->calcolaNome($nome).$this->calcolaAnno($anno).$this->calcolaMese($mese).$this->calcolaGiorno($sesso, $giorno).$this->calcolaCatastale($comune);
			$pari = array(
			     '0' =>  0, '1' =>  1, '2' =>  2, '3' =>  3, '4' =>  4, 
			     '5' =>  5, '6' =>  6, '7' =>  7, '8' =>  8, '9' =>  9,
			     'A' =>  0, 'B' =>  1, 'C' =>  2, 'D' =>  3, 'E' =>  4, 
			     'F' =>  5, 'G' =>  6, 'H' =>  7, 'I' =>  8, 'J' =>  9,
			     'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13, 'O' => 14, 
			     'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
			     'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 
			     'Z' => 25
			);

			$dispari = array(  
			    '0' =>  1, '1' =>  0, '2' =>  5, '3' =>  7, '4' =>  9,
			    '5' => 13, '6' => 15, '7' => 17, '8' => 19, '9' => 21,
			    'A' =>  1, 'B' =>  0, 'C' =>  5, 'D' =>  7, 'E' =>  9, 
			    'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21,
			    'K' =>  2, 'L' =>  4, 'M' => 18, 'N' => 20, 'O' => 11, 
			    'P' =>  3, 'Q' =>  6, 'R' =>  8, 'S' => 12, 'T' => 14,
			    'U' => 16, 'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 
			    'Z' => 23
			);
			$controllo = array( 
			       '0'  => 'A', '1'  => 'B', '2'  => 'C', '3'  => 'D', 
			       '4'  => 'E', '5'  => 'F', '6'  => 'G', '7'  => 'H', 
			       '8'  => 'I', '9'  => 'J', '10' => 'K', '11' => 'L', 
			       '12' => 'M', '13' => 'N', '14' => 'O', '15' => 'P', 
			       '16' => 'Q', '17' => 'R', '18' => 'S', '19' => 'T',
			       '20' => 'U', '21' => 'V', '22' => 'W', '23' => 'X', 
			       '24' => 'Y', '25' => 'Z'
			);
			$code = str_split($cf);
			$sum  = 0;
			for($i=1; $i <= count($code); $i++) {
			    $cifra = $code[$i-1];
			    $sum += ($i % 2) ? $dispari[$cifra] : $pari[$cifra];
			}
			$sum %= 26;
			$controllo = $controllo[$sum];
			return $controllo;
		}
		public function calcolaCodiceFiscale($cognome, $nome, $anno, $mese, $sesso, $giorno, $comune, $controllo){
			$cf = $this->calcolaCognome($cognome).$this->calcolaNome($nome).$this->calcolaAnno($anno).$this->calcolaMese($mese).$this->calcolaGiorno($sesso, $giorno).$this->calcolaCatastale($comune).$this->calcolaControllo($cognome, $nome, $anno, $mese, $sesso, $giorno, $comune);
			return $cf;
		}
	}