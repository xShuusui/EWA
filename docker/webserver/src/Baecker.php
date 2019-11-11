<?php

require_once './Page.php';

/**
 * TODO: Beschreibung Baecker.php
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Baecker extends Page {

    /** Contains all ordered pizzas. */
    protected $orderedPizzas = array();
    protected $orderArray = array();
    
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

        // Select some stuff from database.
        $sqlSelect = "SELECT menu.pizzaID, menu.pizzaName, orderedPizza.orderID, orderedPizza.status FROM orderedPizza INNER JOIN menu 
            ON orderedPizza.pizzaID=menu.pizzaID WHERE status='Bestellt' OR status='Im Ofen'";
        $recordSet = $this->connection->query($sqlSelect);

        $orderedPizzas2 = array();
        $pizza = array();

        if ($recordSet->num_rows > 0) {
            while ($row = $recordSet->fetch_assoc()) {
                // Get orderID.
                $orderID = $row["orderID"];

                // Create pizza array.
                $pizza = $row["pizzaID"];
                $pizza = $row["pizzaName"];
                $pizza = $row["status"];
                
                // Push pizza array in orderedPizzas.
                $orderedPizzas2[count($orderedPizzas2)] = $pizza;

                var_dump($pizza);


            }
        }
/*
        if ($recordSet->num_rows > 0) {
            
            // Iterate through recordSet and create new array for each ordered pizza.
            while ($row = $recordSet->fetch_assoc()) {

                $orderedPizza = array();
                $orderedPizza["pizzaID"] = $row["pizzaID"];
                $orderedPizza["pizzaName"] = $row["pizzaName"];
                $orderedPizza["orderID"] = $row["orderID"];
                $orderedPizza["status"] = $row["status"];
                $orderedPizza["checked"] = false;

                // Push the orderedPizza array in the orderedPizzas array.
                $this->orderedPizzas[count($this->orderedPizzas)] = $orderedPizza;
            }
            $recordSet->free();
        } else {
            echo mysqli_error($this->connection);
        }
        */
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
        // Iterate through all orderedPizzas.
        for ($i = 0; $i < count($this->orderedPizzas); $i++) {

            // Iterate through all orderedPizzas.
            for ($j = 0; $j < count($this->orderedPizzas); $j++) {
                $orderID = $this->orderedPizzas[$j]["orderID"];

                // Check if orderID is the same.
                if ($this->orderedPizzas[$i]["orderID"] === $this->orderedPizzas[$j]["orderID"]) {
                    
                    // Check if checked is false.
                    if ($this->orderedPizzas[$i]["checked"] === false) {
                        $pizzaName = $this->orderedPizzas[$i]["pizzaName"];
                        $pizzaID = $this->orderedPizzas[$i]["pizzaID"];

                        $this->orderedPizzas[$i]["checked"] = true;
echo <<< HTML
        <form action="./Baecker.php" method="POST">
            <p>Bestellnummer: $orderID</p>
            <p>Pizza: $pizzaName</p>
            <input type="hidden" name="orderID" value="$orderID" />
            <input type="hidden" name="pizzaID" value="$pizzaID" />
            <select name="status" size="1">
HTML;
                        if ($this->orderedPizzas[$i]["status"] === "Bestellt") {
echo <<< HTML
                <option value="Bestellt" selected>Bestellt</option>
                <option value="Im Ofen">Im Ofen</option>
HTML;
                        } else if ($this->orderedPizzas[$i]["status"] === "Im Ofen") {
echo <<< HTML
                <option value="Im Ofen" selected>Im Ofen</option>
                <option value="Fertig">Fertig</option>
HTML;
                        }
echo <<< HTML
            </select>
            <input type="submit" value="Übernehmen"/>
        </form>
        <p>----------------------------------------------------------</p>\n
HTML;
                    }
                }
            }
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
        $this->generatePageHeader("Baecker");
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
        if (isset($_POST["orderID"]) && isset($_POST["pizzaID"]) && isset($_POST["status"])) {
            
            // Save POST data into variables and mask special characters.
            $orderID = $this->connection->real_escape_string($_POST["orderID"]);
            $pizzaID = $this->connection->real_escape_string($_POST["pizzaID"]);
            $status = $this->connection->real_escape_string($_POST["status"]);

            // Select orderedPizzaID from database.
            if ($status === "Im Ofen") {
                $sqlSelect = "SELECT orderedPizzaID FROM orderedPizza WHERE orderID=$orderID AND pizzaID=$pizzaID AND status=\"Bestellt\" 
                    ORDER BY orderedPizzaID ASC LIMIT 1";
            } else if ($status === "Fertig") {
                $sqlSelect = "SELECT orderedPizzaID FROM orderedPizza WHERE orderID=$orderID AND pizzaID=$pizzaID AND status=\"Im Ofen\"
                ORDER BY orderedPizzaID ASC LIMIT 1";
            }
            $record = $this->connection->query($sqlSelect);

            if ($record->num_rows == 1) {
                $row = $record->fetch_assoc();
                $orderedPizzaID = $row["orderedPizzaID"];
                $record->free();
            }

            // Update orderedPizza in database.
            $sqlUpdate = "UPDATE orderedPizza SET status=\"$status\" WHERE orderedPizzaID=$orderedPizzaID";
            $this->connection->query($sqlUpdate);

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
