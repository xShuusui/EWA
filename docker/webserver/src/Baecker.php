<?php

require_once './Page.php';

/**
 * Shows customer orders and the corresponding pizzas with their current status,
 * the baker can change the pizza status.
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Baecker extends Page {
    
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
     * This function can be overwritten to add additional Meta Tags.
     */
    protected function addAdditionalMeta() {
echo <<< HTML
    <meta http-equiv="refresh" content="5" />\n
HTML;
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData() {
        
        $this->checkDatabaseConnection();

        // Select data from database.
        $sqlSelect = "SELECT * FROM `orderedPizza` WHERE `status`='Bestellt' OR `status`='Im Ofen'";
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
    }

    /**
     * Generates the body section of the page.
     * 
     * @return none
     */
    protected function generatePageBody() {
echo <<< HTML
    <h1>Bäcker</h1>
    <section>
        <h2>Kundenbestellungen:</h2>\n
HTML;
        // Iterate through orders[].
        foreach ($this->orders as $orderID => $orderedPizzas) {
echo <<< HTML
        <div>
            <p>Bestellnummer: $orderID</p>\n
HTML;
            // Iterate through orderedPizzas[].
            foreach ($orderedPizzas as $orderedPizzaID => $pizza) {
                $pizzaName = $pizza["pizzaName"];
                $status = $pizza["status"];
echo <<< HTML
            <div>
                <p>Pizza: $pizzaName | Status: $status</p>
                <form action="./Baecker.php" method="POST">
                    <input type="hidden" name="orderedPizzaID" value="$orderedPizzaID" />
                    <select name="status" size="3">
                        <option value="Bestellt">Bestellt</option>
                        <option value="Im Ofen">Im Ofen</option>
                        <option value="Fertig">Fertig</option>
                    </select>
                    <input type="submit" value="Übernehmen"/>
                </form>
            </div>\n
HTML;
            }
echo <<< HTML
        </div>
        <p>--------------------------------------------------</p>\n
HTML;
        }
echo <<< HTML
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

        $this->getViewData();
        $this->generatePageHeader("Bäcker");
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

        // Check if POST variables are declared.
        if (isset($_POST["orderedPizzaID"]) && isset($_POST["status"])) {

            $this->checkDatabaseConnection();

            // Save POST data into variables and mask special characters.
            $orderedPizzaID = $this->connection->real_escape_string($_POST["orderedPizzaID"]);
            $status = $this->connection->real_escape_string($_POST["status"]);

            // Update orderedPizza in database.
            $sqlUpdate = "UPDATE `orderedPizza` SET `status`=\"$status\" WHERE `orderedPizzaID`=$orderedPizzaID";
            $this->connection->query($sqlUpdate);

            // Redirect on Baecker.php.
            header('Location: http://localhost/Baecker.php');
        }
    }

    /**
     * Creates an instance of the class and call
     * the methods processReceivedData() and generateView().
     *
     * @return none 
     */    
    public static function main() {
        try {
            $page = new Baecker();
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
Baecker::main();
