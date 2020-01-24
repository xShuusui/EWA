<?php

require_once './Page.php';

/**
 * Shows orders with their baked pizzas and their current status,
 * the driver can change the order status.
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Fahrer extends Page{

    /** Contains all orders and orderedPizzas. */
    protected $orders = array();

    /** Contains all customer data. */
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
    <!--<meta http-equiv="refresh" content="5" >\n-->
HTML;
    }

    /**
     * This function can be overwritten to add additional CSS files or scripts.
    */
    protected function addAdditionalScript() {
echo <<< HTML
    <link rel="stylesheet" type="text/css" href="styles/driver.css">
HTML;   
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData(){
        
        $this->checkDatabaseConnection();

        // Select data from database.
        $sqlSelect = "SELECT `orderedPizza`.*, `order`.fullName, `order`.address, `menu`.pizzaPrice FROM `orderedPizza` 
            INNER JOIN `menu` ON `menu`.pizzaName=`orderedPizza`.pizzaName
            INNER JOIN `order` ON `order`.orderID=`orderedPizza`.orderID
            WHERE NOT EXISTS 
                (SELECT * FROM `orderedPizza` WHERE `orderedPizza`.orderID=`order`.orderID 
                AND (`status`='Bestellt' OR `status`='Im Ofen' OR `status`='Geliefert')) 
            ORDER BY `orderedPizza`.orderID";

        $recordSet = $this->connection->query($sqlSelect);

        if ($recordSet->num_rows > 0) {

            $orderedPizzas = array();
            $latestOrderID = null;
            while ($row = $recordSet->fetch_assoc()) {

                // Save IDs into variables and mask special characters.
                $currentOrderID = htmlspecialchars($row["orderID"]);
                $orderedPizzaID = htmlspecialchars($row["orderedPizzaID"]);

                // Check if orderIDs are the same and get pizza data.
                if ($latestOrderID === $currentOrderID || $latestOrderID === null) {

                    // Create pizza[] and mask special characters.
                    $pizza = array();
                    $pizza["pizzaName"] = htmlspecialchars($row["pizzaName"]);
                    $pizza["status"] = htmlspecialchars($row["status"]);
                    $pizza["pizzaPrice"] = htmlspecialchars($row["pizzaPrice"]);

                    // Push pizza[] in orderedPizzas[].
                    $orderedPizzas[$orderedPizzaID] = $pizza;

                // If orderIDs different.
                } else {

                    // Create pizza[] and mask special characters.
                    $pizza = array();
                    $pizza["pizzaName"] = htmlspecialchars($row["pizzaName"]);
                    $pizza["status"] = htmlspecialchars($row["status"]);
                    $pizza["pizzaPrice"] = htmlspecialchars($row["pizzaPrice"]);

                    // Reset orderedPizzas[] and push pizza[] in orderedPizzas[].
                    $orderedPizzas = array();
                    $orderedPizzas[$orderedPizzaID] = $pizza;
                }
                
                // Check if orderIDs are different and get customer data.
                if ($latestOrderID != $currentOrderID || $latestOrderID === null) {

                    // Create data[] and mask special characters.
                    $data = array();
                    $data["fullName"] = htmlspecialchars($row["fullName"]);
                    $data["address"] = htmlspecialchars($row["address"]);

                    // Push data[] into customerData[].
                    $this->customersData[$currentOrderID] = $data;
                }

                // Save currentOrderID.
                $latestOrderID = $currentOrderID;

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
<div class="driverPage">
    <h1>Fahrer</h1>
    <h2>Abholbereite Lieferungen:</h2>
    <section class="driver">
        <div>\n
HTML;
            // Iterate through orders[].
            foreach ($this->orders as $orderID => $orderedPizzas) {
echo <<< HTML
                <div class="order">
                    <p><strong>Bestellnummer: $orderID</strong></p>\n
HTML;
                // Iterate through orderedPizzas[] and get variables.
                $tmpPizzaNames = "";
                $tmpTotalPrice = 0;
                foreach ($orderedPizzas as $orderedPizzaID => $pizza) {
                    $tmpPizzaNames = $tmpPizzaNames . $pizza["pizzaName"] . "; ";
                    //$tmpPizzaStatus[] = $pizza["status"];
                    $tmpTotalPrice += $pizza["pizzaPrice"]; 
                }
                $tmpAddress = $this->customersData[$orderID]["address"];
                $tmpFullName = $this->customersData[$orderID]["fullName"];
echo <<< HTML
                    <p>Kundenname: $tmpFullName</p>
                    <p>Kundenadresse: $tmpAddress</p>
                    <p>Bestellung: $tmpPizzaNames</p>
                    <p>Gesamtpreis: $tmpTotalPrice</p>

                    <form id="formID=$orderID" action="./Fahrer.php" method="POST">
                        <input type="hidden" name="orderID" value="$orderID"/>

                        <input type="radio" name="status" value="Unterwegs" onclick="document.forms['formID=$orderID'].submit();" /> Unterwegs
                        <input type="radio" name="status" value="Geliefert" onclick="document.forms['formID=$orderID'].submit();" /> Geliefert
                    </form>
                </div>\n
HTML;
        }
echo <<< HTML
        </div>
    </section>
</div>
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

        // Check if POST variables are declared.
        if (isset($_POST["orderID"]) && isset($_POST["status"])) {

            // Save POST data into variables and mask special characters.
            $orderID = $this->connection->real_escape_string($_POST["orderID"]);
            $status = $this->connection->real_escape_string($_POST["status"]);
            print_r($orderID,$status);
        
            $sqlUpdate = "UPDATE orderedPizza SET `status` = \"$status\" WHERE `orderID` = \"$orderID\"";

            $this->connection->query($sqlUpdate);

            
            // Redirect on Fahrer.php.
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
