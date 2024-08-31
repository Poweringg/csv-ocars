<?php
class CsvProcessor {
    private $errors = [];
    private $data = [];



    //kontrola nahranych souboru
    public function getFiles($files){
        //chci holky i hochy, takze chci 2 soubory.
        $numFiles = count($_FILES['csvFiles']['tmp_name']);
        if($numFiles === 2){
            //loop na uploadnute soubory
            foreach($_FILES['csvFiles']['tmp_name'] as $index => $tmpFilePath){
                $fileName = $_FILES['csvFiles']['name'][$index];
                //kontrola pokud je to soubor s priponou .csv
                if(in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), ['csv'])){
                    $this->readCsv($_FILES['csvFiles']['tmp_name'][$index],strtolower($fileName)); //zpracovat data
                }else{
                    $this->addError('Soubor "'.$fileName.'" není CSV.<br>Data nemusí být kompletní.');
                }
            }
        }else{
            $this->addError('Očekával se vstup dvou souborů, nikoliv '.$numFiles);
        }
    }



    //ziskat obsah z csv souboru a vlozit do array $data
    public function readCsv($file,$filename){
        $gender = "";
        if (strpos($filename, 'female') !== false) {
            $gender = "female";
        } elseif (strpos($filename, 'male') !== false) {
            $gender = "male";
        }

        //otevrit soubor pro cteni
        if(($handle = fopen($file, "r")) !== FALSE){
            $header = fgetcsv($handle); //cist prvni radek, ziskat nazvy sloupcu
            $header[] = "gender"; //pridat do array klic "gender"

            //cist csv soubor line-by-line
            while(($row = fgetcsv($handle)) !== FALSE){
                $row = array_map('trim', $row); //smazat prebytecne mezery
                $row['gender'] = $gender; //pridat ke klici ziskane pohlavi

                //pokud se rovnaji sloupce poctu hodnot na radek
                if(count($header) === count($row)){
                    $this->data[] = array_combine($header, $row); //priradit hodnoty ke klicum a pridat do array $data
                }
            }
            
            fclose($handle); //konec cteni, zavrit soubor
        }
    }



    //zmenit serazeni dat v array dle pozadavku
    public function sortData($by){
        if($by == "year"){
            $years = array_column($this->data, 'Year'); //zajima me klic "Year"
            array_multisort($years, SORT_DESC, $this->data); //descending razeni
        }
        if($by == "movie"){
            $movies = array_column($this->data, 'Movie'); //zajima me klic "Movie"
            array_multisort($movies, SORT_ASC, $this->data); //abecedni razeni
        }
    }



    //zformatovany vystup pro zeny a muze
    public function formatNameAndMovie($row){
        return "<b>".$row['Name']."</b> (".$row['Age'].")<br>".$row['Movie'];
    }



    //vykreslit kompletni tabulku s hodnotama - prehled dle roku
    public function getTableByYear(){
        $this->sortData("year"); //seradit data podle roku
        echo '
        <table class="table table-striped table-hover shadow-lg">
            <thead>
                <tr>
                    <th scope="col">Rok</th>
                    <th scope="col">Ženy</th>
                    <th scope="col">Muži</th>
                </tr>
            </thead>
            <tbody>
            ';
            //loop na kazdy radek
            foreach($this->data as $row){
                echo '<tr>
                <td>'.$row['Year'].'</td>
                <td>'.($row['gender']=="female" ? $this->formatNameAndMovie($row) : '/').'</td>
                <td>'.($row['gender'] == "male" ? $this->formatNameAndMovie($row) : '/').'</td>
                </tr>';
            }
            echo '
            </tbody>
        </table>';
    }



    //vykreslit kompletni tabulku s hodnotama - Filmy ktere dostaly oscara
    public function getTableByMovie(){
        $this->sortData("movie"); //seradit data podle nazvu filmu
        echo '
        <table class="table table-striped table-hover shadow-lg">
            <thead>
                <tr>
                    <th scope="col">Název Filmu</th>
                    <th scope="col">Rok</th>
                    <th scope="col">Herečka</th>
                    <th scope="col">Herec</th>
                </tr>
            </thead>
            <tbody>
            ';
            //loop na kazdy radek
            foreach($this->data as $row){
                echo '<tr>
                <td>'.$row['Movie'].'</td>
                <td>'.$row['Year'].'</td>
                <td>'.($row['gender']=="female" ? $this->formatNameAndMovie($row) : '/').'</td>
                <td>'.($row['gender'] == "male" ? $this->formatNameAndMovie($row) : '/').'</td>
                </tr>';
            }
            echo '
            </tbody>
        </table>';
    }



    //pridat chybu
    public function addError($message){
        $this->errors[] = $message;
    }

    //smazat chyby
    public function clearAllErrors(){
        $this->errors = [];
    }
    
    //vypsat vsechny aktualni chyby v html
    public function getErrors(){
        if(!empty($this->errors)){ 
            return "<p class='errorMessage'>".implode("<br>", $this->errors)."</p>";
        }
    }

    //DEBUG - Vypsat array pro html
    public function printData(){
        echo '<br><pre>';
        print_r($this->data);
        echo '</pre>';
    }

}