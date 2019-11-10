<?php

require_once './Page.php';

/**
 * TODO: Beschreibung Kunde.php
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Kunde extends Page {

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
    protected function generatePageBody() {
echo <<< HTML
    <h1>Kunde</h1>
    <section>
        <h2>Kundenbestellungen:</h2>\n
HTML;
        //First for-loop to go over all orders
        for($i = 0; $i < count($this->allOrders); $i++){
            //Get current orderID
            $tmpOrderID = $this->allOrders[$i]['orderID'];
echo <<< HTML
        <div>
            <p>Bestellnummer: $tmpOrderID</p>\n
HTML;
        //Second for-loop to go over all pizzas in the current order
        for($k = 0; $k < count($this->allOrderedPizzas); $k++){
            //Get all infos of ordered pizzas
            $tmpPizzaID = $this->allOrderedPizzas[$k]['pizzaID'];
            $tmpPizzaStatus = $this->allOrderedPizzas[$k]['status'];
            $tmpPizzaOrderID = $this->allOrderedPizzas[$k]['orderID'];
            
            //Get name of ordered pizzas
            $tmpPizzaName = $this->menu[$tmpPizzaID-1]['pizzaName'];
            
            //Only print pizza when from current order
            if($tmpOrderID == $tmpPizzaOrderID){
echo <<< HTML
            <p>$tmpPizzaName : $tmpPizzaStatus</p>\n
HTML;
            }
        }
echo <<< HTML
            <p>---------------</p>
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
