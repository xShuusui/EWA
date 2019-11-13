<?php

require_once './Page.php';

/**
 * TODO: Beschreibung Fahrer.php
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Fahrer extends Page{

    /** Contains all orders and orderedPizzas. */
    protected $orders = array();

    //Contains all the customer data
    protected $customersData = array();



    /**
     * Creates a database connection.
     *
     * @return none
     */
    protected function __construct(){

        parent::__construct();
    }

    /**
     * Closes the database connection.
     *
     * @return none
     */
    protected function __destruct(){

        parent::__destruct();
    }

    /**
     * This function can be overwritten to add additional Meta Tags.
     */
    protected function addAdditionalMeta() {
echo <<< HTML
    <meta http-equiv="refresh" content="5" >\n
HTML;
                }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData(){
        // to do: fetch data for this view from the database
        $this->checkDatabaseConnection();

        // Select data from database.
        $sqlSelect = "SELECT orderedPizza.*, order.fullName, order.address, menu.pizzaPrice FROM `orderedPizza` NATURAL JOIN `order` NATURAL JOIN `menu`
        WHERE `status`='Fertig' OR `status`='Unterwegs'";
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
                    $pizza["pizzaPrice"] = htmlspecialchars($row["pizzaPrice"]);

                    //Create data[] and mask special chars, is value for customerData[]
                    $data = array();
                    $data["fullName"] = htmlspecialchars($row["fullName"]);
                    $data["address"] = htmlspecialchars($row["address"]);

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
                    $pizza["pizzaPrice"] = htmlspecialchars($row["pizzaPrice"]);

                    //Create data[] and mask special chars, is value for customerData[]
                    $data = array();
                    $data["fullName"] = htmlspecialchars($row["fullName"]);
                    $data["address"] = htmlspecialchars($row["address"]);

                    // Reset orderedPizzas[] and push pizza[] in orderedPizzas[].
                    $orderedPizzas = array();
                    $orderedPizzas[$orderedPizzaID] = $pizza;

                    // Save currentOrderID.
                    $latestOrderID = $currentOrderID;
                }

                //Push data[] into customerData[]
                $this->customersData[$currentOrderID] = $data;

                // Push orderedPizzas[] in orders[].
                $this->orders[$latestOrderID] = $orderedPizzas;
            }
            $recordSet->free();
            //print_r($this->customersData);
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
    protected function generatePageBody(){
echo <<< HTML
    <h1>Fahrer</h1>
    <section>
        <h2>Abholbereite Lieferungen:</h2>
        <div>
HTML;
            foreach ($this->orders as $orderID => $orderedPizzas) {
                
                $checkOrderFinished = 0;
                foreach ($orderedPizzas as $orderedPizzaID => $pizza) {
                    if($pizza["status"] == "Fertig" || $pizza["status"] == "Unterwegs")
                        $checkOrderFinished++;
                }


                print_r($orderedPizzas);
                if($checkOrderFinished == count($this->orders[$orderID])){
echo <<< HTML
                <p><strong>Bestellnummer: $orderID</strong></p>
HTML;
                $tmpPizzaNames;
                $tmpPizzaStatus = array();
                $tmpTotalPrice = 0;
                foreach ($orderedPizzas as $orderedPizzaID => $pizza) {
                    $tmpPizzaNames = $tmpPizzaNames . $pizza["pizzaName"] . "; ";
                    $tmpPizzaStatus[] = $pizza["status"];
                    $tmpTotalPrice += $pizza["pizzaPrice"]; 
                }

                $tmpAddress = $this->customersData[$orderID]["address"];
                $tmpFullName = $this->customersData[$orderID]["fullName"];

echo <<< HTML
                <p>Kundenname: $tmpFullName</p>
                <p>Kundenadresse: $tmpAddress</p>
                <p>Bestellung: $tmpPizzaNames</p>
                <p>Gesamtpreis: $tmpTotalPrice</p>

                <form action="./Fahrer.php" method="POST">
                    <select name="status" size="2">
                        <option value="Unterwegs">Unterwegs</option>
                        <option value="Geliefert">Geliefert</option>
                    </select>
                    <input type="Submit" value="Übernehmen">
                </form>
HTML;
            }
        }
echo <<< HTML
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
    protected function generateView(){

        $this->getViewData();
        $this->generatePageHeader("Fahrer");
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
    protected function processReceivedData(){

        parent::processReceivedData();

        if(isset($_POST["pizzaIDsOfOrder"]) && isset($_POST["pizzaStatus"])){

            //Save POST-Data into variables and mask special characters.
            $pizzaStateOfPost = $this->connection->real_escape_string($_POST["pizzaStatus"]);
            $pizzaIDsOfPost = array();
            $pizzaIDsOfPost = $_POST['pizzaIDsOfOrder'];

            //Save new states in respective orderedPizzas
            for($i = 0; $i < count($pizzaIDsOfPost); $i++){
                $tmpPizzaID = $this->connection->real_escape_string($pizzaIDsOfPost[$i]);
                $sqlUpdate = "UPDATE orderedPizza SET status=\"$pizzaStateOfPost\" WHERE orderedPizzaID=$tmpPizzaID";
                $this->connection->query($sqlUpdate);
            }

            header('Location: http://localhost/Fahrer.php');
        }
    }

    /**
     * Creates an instance of the class and call
     * the methods processReceivedData() and generateView().
     *
     * @return none 
     */
    public static function main(){
        try {
            $page = new Fahrer();
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
Fahrer::main();
