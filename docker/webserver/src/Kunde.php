<?php

require_once './Page.php';

/**
 * Shows the customers order with the status of his pizzas.
 * 
 * @author   Julian Segeth
 * @author   Bican Gül 
 */
class Kunde extends Page {

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
     * This function can be overwritten to add additional CSS files or scripts.
     */
    protected function addAdditionalScript() {
echo <<< HTML
    <script src="scripts/ajax.js"></script>
HTML;   

    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is stored in an easily accessible way e.g. as associative array.
     *
     * @return none
     */
    protected function getViewData() {

    }

    /**
     * Generates the body section of the page.
     * 
     * @return none
     */
    protected function generatePageBody() {
echo <<< HTML
    <h1>Kunde</h1>
    <h2>Kundenbestellungen:</h2>
    <section class="customer" id="cust">
    
    </section>\n
HTML;

echo <<< HTML
    <section class="newOrderButton">
        <div>
            <!--Input-Field of type submit to redirect the user to Bestellung.php-->
            <form action="http://localhost/Bestellung.php">
                <input type="submit" class="actualNewOrderButton" value="Neue Bestellung">
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
