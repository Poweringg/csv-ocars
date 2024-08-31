<?php
require 'sources/csv_processor.php';

//pokud byl submit
if(isset($_POST['submit'])){
    $csv = new CsvProcessor();
    $csv->getFiles($_FILES['csvFiles']['tmp_name']);
    //$csv->printData();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Přehled Oscarů</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link href="sources/style.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            //show a hide toggle pro nejakou tabulku
            function toggleTable(button,table,name){ 
                $(table).toggle(); 
                if($(table).is(":visible")){
                    $(button).text('Skrýt tabulku "'+name+'"');
                } else {
                    $(button).text('Zobrazit tabulku "'+name+'"');
                }
            }
            function reloadPage(){
                window.location.replace(window.location.href);
            }
        </script>
    </head>
    <body>

    <div class="wrapper">
        <h1 class="text-center">Přehled Oscarů</h1>
        <p class="text-center">Vyberte příslušné CSV soubory ke zpracování.<br>Kdekoli v názvu souboru se očekává slovo *<b>male</b>* nebo *<b>female</b>*</p>

        <div class="upload-form">
            <?php if(!isset($csv)){ ?>
            <form class="row g-3 justify-content-center" action="" method="POST" id="upload" enctype="multipart/form-data">
                <div class="col-auto">
                    <input type="file" class="form-control" name="csvFiles[]" required multiple>
                </div>
                <div class="col-auto">
                    <button type="submit" name="submit" class="btn btn-primary mb-3">Nahrát a zpracovat</button>
                </div>
            </form> 
            <?php 
            }else{
                echo '<p style="text-align:center;color:#575757;">Soubor(y) byly nahrány. | <a href="#" onclick="reloadPage()">Nahrát soubory znovu</a></p>';
            }

            //vypsat chyby a pak je smazat z array.
            if(isset($csv)){
                echo $csv->getErrors();
                $csv->clearAllErrors();
            } 
            ?>   
        </div>

        <?php if(isset($csv)){ ?>
        <div class="upload-buttons">
            <button class="btn btn-secondary" onclick="toggleTable(this,'#year','Dle Roku')">Zobrazit tabulku "Dle Roku"</button>
            <button class="btn btn-secondary" onclick="toggleTable(this,'#movie','Dle Filmu')">Zobrazit tabulku "Dle Filmu"</button>
        </div>
        <?php } ?>

        <div class="table-wrapper">
            <?php 
            if(isset($csv)){
		//podle roku
                echo '
                <div class="table-output" id="year">
                    <h3>Přehled dle roku</h3>
                    <p>Řazení sestupně podle ročníku</p>
                    ';
                    $csv->getTableByYear();
                echo '</div>'; 
                
                //podle filmu
		echo '
		<div class="table-output" id="movie">
			<h3>Filmy, které obdržely oscary</h3>
			<p>Řazení abecedně A-Z</p>
			';
			$csv->getTableByMovie();
		echo '</div>';    
            }
            ?>
        </div>

    </div>


    </body>
</html>
