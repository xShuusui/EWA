<?php

require_once './Page.php';

/**
 * 
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class KundenStatus extends Page {

    /** Contains all orders and orderedPizzas. */
    protected $orders = array();

    /**
     * Creates a database connection.
     *
     * @return none
     */
    protected function __construct() {

        parent::__construct();
    }
    
    /**
     * Closes the database connection.
     *
     * @return none
     */
    protected function __destruct() {

        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData() {
        header("Content-type: application/json; charset=UTF-8");

        $this->checkDatabaseConnection();

        if (isset($_SESSION["orderID"])) {
            $tmpOrderID = $this->connection->real_escape_string($_SESSION["orderID"]);
            
            // Select data from database.
            $sqlSelect = "SELECT * FROM `orderedPizza` WHERE `orderID` = $tmpOrderID";
            $recordSet = $this->connection->query($sqlSelect);

            if ($recordSet->num_rows > 0) {

                while ($row = $recordSet->fetch_assoc()) {

                    $pizza = array();
                    $pizza["orderID"] = htmlspecialchars($row["orderID"]);
                    $pizza["pizzaName"] = htmlspecialchars($row["pizzaName"]);
                    $pizza["status"] = htmlspecialchars($row["status"]);

                    $this->orders[count($this->orders)] = $pizza;
                
                }
                $recordSet->free();
                //print_r($this->orders);
            } else {
                echo mysqli_error($this->connection);
            }
        }
    }

    
    /**
     * First the necessary data is fetched and then the HTML is 
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if avaialable- the content of 
     * all views contained is generated.
     * Finally the footer is added.
     *
     * @return none
     */
    protected function generateView() {

        session_start();
        $this->getViewData();

        // Return data as json;
        echo (json_encode($this->orders));
  
    }
    
    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
     *
     * @return none 
     */
    protected function processReceivedData() {

        parent::processReceivedData();
    }

    /**
     * Creates an instance of the class and call
     * the methods processReceivedData() and generateView().
     *
     * @return none 
     */    
    public static function main() {
        try {
            $page = new KundenStatus();
            $page->processReceivedData();
            $page->generateView();

        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

/**
* Calling main function to construct and build the page.
*/
KundenStatus::main();
