<?php

declare(strict_types=1);

namespace Controllers;

use PDO;
use PDOException;

class HomeController
{

    public function __construct()
    {
        // change servername and etc. with env
        $servername = "db";
        $username = "root";
        $password = "root";
        global $conn;

        try {
            $this->$conn = new PDO(
                "mysql:host=$servername;dbname=MainTable",
                $username,
                $password
            );

            $this->$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // echo 'Connection succesful!' . "</br>";
        } catch (PDOException $e) {
            echo "We have a problem, " . $e->getCode();
        }
    }
    // function for adding csv file to database
    public function addFile(array $file)
    {
        $count = 0;

        foreach ($file as &$string) {
            // getting array from strings
            $separated = str_getcsv($string, ",");
            // changing date to mysql accepted format
            $date = date("Y-m-d", ((int)strtotime($separated[0])));
            // explicitly puts NULL if number is empty
            $checkNum = !empty($separated[1]) ? "'$separated[1]'" : "NULL";
            $description = $separated[2];
            // removing everything except digits,dot and minus
            $amount = preg_replace('/[^0-9.-]+/', '', $separated[3]);
            // skipping the first row with names
            if ($count != 0) {
                $sql = "INSERT INTO `Transactions`(`date`, `checkNum`, `description`, `amount`) 
                VALUES ('$date', $checkNum, '$description', '$amount')";
                global $conn;
                    try {
                        $stmt = $this->$conn->prepare($sql)->execute();
                    } catch (PDOException $e) {
                        echo 'Oh, something gone wrong. Error code->' . $e->getCode();
                    }
            } else {
                $count = 1;
            }
        }

        echo "File added succesfully! <br>";
    }

    public function getData()
    {
        global $conn;

        $sql = "SELECT * FROM `Transactions`";
        try {
            $stmt = $this->$conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetchAll();
            return $res;
        } catch (PDOException $e) {
            echo 'Oh, something gone wrong. Error code->' . $e->getCode();
        }
    }

    public function getIncomeExpenseTotal(): array
    {
        global $conn;
        // we`re getting income, expense and total by sum of amounts and returning array with them
        $sql = "SELECT
                (SELECT SUM(amount) FROM `TRANSACTIONS` where amount>0) AS income,
                (SELECT SUM(amount) FROM `TRANSACTIONS` WHERE amount<0) AS expense,
                (SELECT SUM(amount) FROM `TRANSACTIONS`) AS total";

        try {
            $stmt = $this->$conn->prepare($sql);
            $stmt->execute();
            $res = $stmt->fetch();

            return $res;
        } catch (PDOException $e) {
            echo 'Oh, something gone wrong. Error code->' . $e->getCode();
        }
    }
}
