<?php
/**
 * This abstract class is a common base class for all 
 * HTML-pages to be created. 
 * It manages access to the database and provides operations 
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Julian Segeth
 * @author   Bican Gül
 */ 
abstract class Page {

    /** Reference to our MySQL database. */
    protected $connection = null;

    /** Default page title. */
    protected $title = "Shop | ";
    
    /**
     * Creates a database connection.
     *
     * @return none
     */
    protected function __construct() {

        $servername = "ewa-database";
	    $username = "dbuser";
	    $password = "dbpassword";
	    $database = "shop";

        // Creates a database connection.
        $this->connection = new mysqli(
            $servername,
            $username,
            $password, 
            $database
        );

        $this->checkDatabaseConnection();
    }
    
    /**
     * Closes the database connection.
     *
     * @return none
     */
    protected function __destruct() {

        $this->connection->close();
    }

    /**
     * Check if a connection to the database exist.
     */
    protected function checkDatabaseConnection() {

        if ($this->connection->connect_errno) {
            die("Connection to the MySQL database failed: " . $this->connection->connect_errno);
        }
        $this->connection->query("SET NAMES utf8");
    }

    /**
     * This function can be overwritten to add additional Meta Tags.
     */
    protected function addAdditionalMeta() { }

    /**
     * This function can be overwritten to add additional CSS files or scripts.
     */
    protected function addAdditionalScript() { }
    
    /**
     * Generates the header section of the page.
     *
     * @param $title title of the page.
     *
     * @return none
     */
    protected function generatePageHeader($title = "") {

        // Check title for HTML code.
        $title = htmlspecialchars($this->title . $title);
        header("Content-type: text/html; charset=UTF-8");

echo <<< HTML
<!DOCTYPE html>
<html lang="de">
<head>
    <title>$title</title>

    <!-- Default Meta Tags. -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />\n
HTML;
        $this->addAdditionalMeta();
echo <<< HTML

    <!-- Default CSS files. -->
    <link rel="stylesheet" type="text/css" href="styles/main.css" />

    <!-- Default JS imports.-->
    <script src="scripts/main.js"></script>\n
HTML;
        $this->addAdditionalScript();
echo <<< HTML
\n</head>
<body>\n
HTML;
        $this->generateNavBar();
    }

    protected function generateNavBar(){
echo <<< HTML
    <nav>
        <a href="http://localhost/Bestellung.php">Bestellung</a>
        <a href="http://localhost/Baecker.php">Bäcker</a>
        <a href="http://localhost/Fahrer.php">Fahrer</a>
        <a href="http://localhost/Kunde.php">Kunde</a>
    </nav>\n
HTML;
    }

    /**
     * Generates the footer section of the page.
     * 
     * @return none
     */
    protected function generatePageFooter() {

echo <<< HTML
\n</body>
</html>
HTML;
    }

    /**
     * Processes the data that comes via GET or POST.
     *
     * @return none
     */
    protected function processReceivedData() {

        if (get_magic_quotes_gpc()) {
            throw new Exception
                ("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
}
?>