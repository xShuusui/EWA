<?php
// TODO: Copy and change name 'Kunde' throughout this file.
require_once './Page.php';

/**
 * This is a template for top level classes, which represent 
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class. 
 * 
 * @author   Julian Segeth
 * @author   Bican Misto 
 */
class Kunde extends Page {
    // to do: declare reference variables for members 
    // representing substructures/blocks
    
    /**
     * Creates a database connection.
     *
     * @return none
     */
    protected function __construct() {

        parent::__construct();
        // to do: instantiate members representing substructures/blocks
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
        <div>
            <p>Pizza Salami</p>
            <form action="https://echo.fbi.h-da.de" method ="get">
                <select name="pizzaStatus" size="5">
                    <option value="ordered">Bestellt</option>
                    <option value="inOven">Im Oven</option>
                    <option value="finished">Fertig</option>
                    <option value="onTheWay">Auf dem Weg</option>
                    <option value="delivered">Geliefert</option>
                </select>
                <br>
                <br>
                <input type="submit" name="submitButton" value="SubmitToCheckEcho">
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
        $this->generatePageHeader("Template");
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
