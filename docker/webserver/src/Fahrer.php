<?php

require_once './Page.php';

/**
 * TODO: Beschreibung Fahrer.php
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Fahrer extends Page{

    //Contains all orders
    protected $allOrders = array();

    //Contains all ordered pizzas
    protected $allOrderedPizzas = array();

    //Contains menu
    protected $menu = array();

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
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData(){
        // to do: fetch data for this view from the database
        $this->checkDatabaseConnection();

        //Gets orders from database and stores them in $allOrders
        $this->getOrders();

        //Gets ordered pizzas from DB and stores them in $allOrderedPizzas
        $this->getOrderedPizzas();

        //Gets all pizzas from menu
        $this->getMenu();
    }

    protected function getMenu(){
        $sql = "SELECT * from menu;";
        $recordSet = $this->connection->query($sql);

        if($recordSet->num_rows > 0){
            while($row = $recordSet->fetch_assoc()){
                $this->menu[count($this->menu)] = $row;
            }
            $recordSet->free();
        } else {
            echo mysqli_error($this->connection);
        }   
    }

    protected function getOrderedPizzas(){
        $sql = "SELECT * from orderedPizza;";
        $recordSet = $this->connection->query($sql);

        if($recordSet->num_rows > 0){
            while($row = $recordSet->fetch_assoc()){
                $this->allOrderedPizzas[count($this->allOrderedPizzas)] = $row;
            }
            $recordSet->free();
        } else {
            echo mysqli_error($this->connection);
        }   
    }

    protected function getOrders(){
        $sql = "SELECT * from `order`;";
        $recordSet = $this->connection->query($sql);

        if($recordSet->num_rows > 0){
            while($row = $recordSet->fetch_assoc()){
                $this->allOrders[count($this->allOrders)] = $row;
            }
            $recordSet->free();
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
        <div>\n
HTML;
        //First for-loop to go over all orders
        //All variables and Arrays in first-loop are for the respective order
        for($i = 0; $i < count($this->allOrders); $i++){
            //Get current orderID and orderAddress
            $orderID = $this->allOrders[$i]['orderID'];
            $orderAddress = $this->allOrders[$i]['address'];

            //Array of all pizzaIDs
            $orderPizzaIDs = array();
            //Array of the status of all ordered pizzas
            $orderPizzaStates = array();
            //Array of orderedPizzaIDs
            $orderedPizzaIDs = array();

            //Second for-loop to go over all orderedPizzas
            for($j = 0; $j < count($this->allOrderedPizzas); $j++){

                //Only get pizzas from current order
                if($this->allOrderedPizzas[$j]['orderID'] == $orderID){

                    //Pushes pizzaID to end of array
                    array_push($orderPizzaIDs, $this->allOrderedPizzas[$j]['pizzaID']);
                    //Pushes pizza status to end of array
                    array_push($orderPizzaStates, $this->allOrderedPizzas[$j]['status']);

                    //Push orderedPizzaID into array
                    array_push($orderedPizzaIDs, $this->allOrderedPizzas[$j]['orderedPizzaID']);
                }
            }

            //Total price of order
            $totalOrderPrice = 0;
            //Array of all pizza names
            $orderPizzaNames = array();

            //Third for-loop to go over menu and get prices of pizzas
            for($k = 0; $k < count($orderPizzaIDs); $k++){

                //Get pizzaPrice of current pizzaID and add to $totalOrderPrice
                $totalOrderPrice += $this->menu[$orderPizzaIDs[$k] - 1]['pizzaPrice'];
                //Get the pizzaNames and push them at end of array
                array_push($orderPizzaNames,$this->menu[$orderPizzaIDs[$k] - 1]['pizzaName']);
            }

            //Variable to check if all pizzas are finished
            $areAllPizzasFinished = 0;
            foreach($orderPizzaStates as $currentStatus){
                if($currentStatus == "Fertig" || $currentStatus == "Geliefert")
                    $areAllPizzasFinished += 1;
            }

            //Checks if complete order is finished, and prints it
            if($areAllPizzasFinished == count($orderPizzaStates)){
echo <<< HTML
            <div>
                <form action="./Fahrer.php" method="POST">
                    <p>Bestellung $orderID:</p>
                    <p>$orderAddress,&emsp; $totalOrderPrice €</p>\n
HTML;
                    for($l = 0; $l < count($orderPizzaNames); $l++){
echo <<< HTML
                    <span>$orderPizzaNames[$l];</span>\n
HTML;
                    }
echo <<< HTML
                    <br>
                    <input type="hidden" name="orderedPizzaIDs" value="$orderedPizzaIDs" />
                    <select name="pizzaStatus" size="1">
                        <option value="Unterwegs">Unterwegs</option>
                        <option value="Geliefert">Geliefert</option>
                    </select>
                    <input type="submit" value="Übernehmen" />
                </form>
                <p>---------------</p>
            </div>\n
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
