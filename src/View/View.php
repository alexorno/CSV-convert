<?php

declare(strict_types = 1);

namespace View;

use App\Controllers\HomeController;

require_once "../Controllers/HomeController.php";

session_start();

class View{
    public function uploadErrorMessage(){
        if(!empty($_SESSION['file_send']['result'])){
            // destroy session after 10 seconds
            if(time() - $_SESSION['file_send']['created'] > 10){
                session_destroy();
            }
            $color = 'green';
            // if we have some type of error then, we change color of text to red 
            if($_SESSION['file_send']['error']){
                $color = 'red';
            }
            echo ("<p style='color: {$color}; margin:10px auto; display: table; font-size: large'>" . $_SESSION['file_send']['result'] . "</p>");
        }
    }
    public function uploadForm(){
        echo 
        '<form action="upload.php" method="post" enctype="multipart/form-data" style="margin:auto; display:table">
            Select csv file to upload:
                <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload File" name="submit">
         </form>';
    }

    public function uploadPage(){
        echo '
        <h3 style:"margin:auto">If you want to comeback on main page, you can click <a href="/"> here </a> </h1>
        ';
    }

    public function createTable($content){
        $controller = new \Controllers\HomeController();
        $incomeExpenseTotal = (array)$controller->getIncomeExpenseTotal();


       echo  "<table style='margin:auto; font-family: Verdana, sans-serif; font-size: 12px;'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Check #</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>";
                // printing each row and column with given data 
                foreach($content as $line){

                    // if number has minus on start then red, if not then green
                    if($line[3][0] === '-'){
                        $line3color = "red";
                    }else{
                        $line3color = "green";
                    }
                    
                    echo '<tr>';
                        echo "<th>{$line[0]}</th> 
                        <th>{$line[1]}</th>
                        <th>{$line[2]}</th>
                        <th style='color:{$line3color}'>{$line[3]}</th>";
                    echo '</tr>';
                }


                echo '
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Income:</th>
                    <td>';

                    echo $incomeExpenseTotal[0];

                    echo'</td>
                </tr>
                <tr>
                    <th colspan="3">Total Expense:</th>
                    <td>';

                    echo $incomeExpenseTotal[1];
                    
                    echo'</td>
                </tr>
                <tr>
                    <th colspan="3">Net Total:</th>
                    <td>';

                    echo $incomeExpenseTotal[2];
                    
                    echo'</td>
                </tr>
            </tfoot>
        </table>';
    }
}