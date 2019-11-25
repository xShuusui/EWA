<?php

require_once './Page.php';

/**
 * Shows the customers order with the status of his pizzas.
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Kunde extends Page {

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

        $this->checkDatabaseConnection();

        if (isset($_SESSION["orderID"])) {
            $tmpOrderID = $this->connection->real_escape_string($_SESSION["orderID"]);
            
            // Select data from database.
            $sqlSelect = "SELECT * FROM `orderedPizza` WHERE `orderID` = $tmpOrderID";
            $recordSet = $this->connection->query($sqlSelect);

            if ($recordSet->num_rows > 0) {

                $orderedPizzas = array();
                $latestOrderID = null;
                while ($row = $recordSet->fetch_assoc()) {

                    // Save IDs into variables and mask special characters.
                    $currentOrderID = htmlspecialchars($row["orderID"]);
                    $orderedPizzaID = htmlspecialchars($row["orderedPizzaID"]);

                    // Check if orderIDs are the same.
                    if ($latestOrderID === $currentOrderID || $latestOrderID === null) {

                        // Create pizza[] and mask special characters.
                        $pizza = array();
                        $pizza["pizzaName"] = htmlspecialchars($row["pizzaName"]);
                        $pizza["status"] = htmlspecialchars($row["status"]);

                        // Push pizza[] in orderedPizzas[].
                        $orderedPizzas[$orderedPizzaID] = $pizza;

                        // Save currentOrderID.
                        $latestOrderID = $currentOrderID;

                    // If orderIDs different.
                    } else {

                        // Create pizza[] and mask special characters.
                        $pizza = array();
                        $pizza["pizzaName"] = htmlspecialchars($row["pizzaName"]);
                        $pizza["status"] = htmlspecialchars($row["status"]);

                        // Reset orderedPizzas[] and push pizza[] in orderedPizzas[].
                        $orderedPizzas = array();
                        $orderedPizzas[$orderedPizzaID] = $pizza;

                        // Save currentOrderID.
                        $latestOrderID = $currentOrderID;
                    }

                    // Push orderedPizzas[] in orders[].
                    $this->orders[$latestOrderID] = $orderedPizzas;
                }
                $recordSet->free();
                //print_r($this->orders);
            } else {
                echo mysqli_error($this->connection);
            }
        } else {
            echo "Error no SESSION!";
        }
    }

    /**
     * Generates the body section of the page.
     * 
     * @return none
     */
    protected function generatePageBody() {
echo <<< HTML
    <h1>Kunde</h1>
    <section>
        <h2>Kundenbestellungen:</h2>\n
HTML;
        foreach ($this->orders as $orderID => $orderedPizzas) {
echo <<< HTML
        <div>    
            <p>Bestellnummer : $orderID</p>\n
HTML;
            foreach ($orderedPizzas as $orderedPizzaID => $pizza) {
                $pizzaName = $pizza['pizzaName'];
                $pizzaStatus = $pizza['status'];
echo <<< HTML
            <p>$pizzaName : $pizzaStatus</p>\n
HTML;
            }
echo <<< HTML
            <p>--------------------------------------------------</p>
        </div>\n
HTML;
        }
echo <<< HTML
    </section>
    <section>
        <div>
            <!--Input-Field of type submit to redirect the user to Bestellung.php-->
            <form action="http://localhost/Bestellung.php">
                <input type="submit" value="Neue Bestellung">
            </form>
        </div>
    </section>
HTML;
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
        $this->generatePageHeader("Kunde");
        $this->generatePageBody();
        $this->generatePageFooter();
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
            $page = new Kunde();
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
Kunde::main();
