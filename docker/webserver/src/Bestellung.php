<?php

require_once './Page.php';

/**
 * TODO: Beschreibung Bestellung.php
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Bestellung extends Page {

    /** Contains all pizzas. */
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
     * Add additional CSS files and scripts to the head.
     */
    protected function addAdditionalScript() {
echo <<< HTML
    <script src="scripts/cart.js"></script>\n
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

        // Select menu from database.
        $sqlSelect = "SELECT * FROM menu";
        $recordSet = $this->connection->query($sqlSelect);

        if ($recordSet->num_rows > 0) {

            // Iterate through recordSet and get the menu.
            while ($row = $recordSet->fetch_assoc()) {
                $this->menu[count($this->menu)] = $row;
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
    <h1>Bestellung</h1>
    <section>
        <h2>Speisekarte</h2>\n
HTML;    
        for($i = 0; $i < count($this->menu); $i++){
            $price = number_format((float) $this->menu[$i]['pizzaPrice'], 2, ".", ",");
            $pizzaName = $this->menu[$i]['pizzaName'];

echo <<< HTML
        <div>
            <img id="$i" onclick="addToCart('$pizzaName', $price)" src="{$this->menu[$i]['imagePath']}" alt="$pizzaName" width="250" height="250" />
            <p data-price-{$pizzaName}="$price"> Pizza $pizzaName: $price €</p>
        </div>\n
HTML;
        }  
echo <<< HTML
    </section>
    <section>
        <h2>Warenkorb</h2>
        <div>
            <form action="./Bestellung.php" method="POST">

                <!-- All cart items. -->
                <select id="cart" name="cart[]" size="5" multiple required>
                </select>

                <!-- Total cart price. -->
                <p id="totalPrice"></p>

                <!-- Text inputs. -->
                <div>
                    <p>Ihre Adresse:</p>
                    <input type="text" name="address" required />
                </div>

                <!-- Button inputs. -->
                <div>
                    <input type="submit" onclick="selectAllOptions()" value="Bestellung aufgeben" />
                    <input type="button" onclick="deleteSelectedOptions()" value="Auswahl entfernen" />
                    <input type="button" onclick="deleteAllOptions()" value="Warenkorb leeren" />
                </div>
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
        $this->generatePageHeader("Bestellung");
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
        if (isset($_POST["address"]) && isset($_POST["cart"])) {

            $this->checkDatabaseConnection();

            // Save POST data into variables and mask special characters.
            $address = $this->connection->real_escape_string($_POST["address"]);
            $cart = $_POST["cart"];

            // Insert order in database.
            $sqlInsert = "INSERT INTO `order` SET address=\"$address\"";
            $this->connection->query($sqlInsert);

            // Get orderID from the latest insert.
            $orderID = $this->connection->insert_id;

            // Iterate through cart array.
            for ($i = 0; $i < count($cart); $i++) {
                $currentPizza = $this->connection->real_escape_string($cart[$i]);

                // Select pizzaID from database.
                $sqlSelect = "SELECT pizzaID FROM menu WHERE pizzaName=\"$currentPizza\"";
                $record = $this->connection->query($sqlSelect);

                if ($record->num_rows == 1) {
                    $row = $record->fetch_assoc();
                    $pizzaID = $row["pizzaID"];
                    $record->free();
                }

                // Insert orderedPizza in database.
                $sqlInsert = "INSERT INTO orderedPizza SET orderID=$orderID, pizzaID=$pizzaID, status=\"Bestellt\"";
                $this->connection->query($sqlInsert);
            }
            header('Location: http://localhost/Bestellung.php');
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
            $page = new Bestellung();
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
Bestellung::main();
?>