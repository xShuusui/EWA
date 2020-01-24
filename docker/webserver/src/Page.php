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
    <!-- Default Meta Tags. -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />\n
HTML;
        $this->addAdditionalMeta();
echo <<< HTML

    <!-- Title of the page. -->
    <title>$title</title>

    <!-- Default CSS and JS files. -->
    <link rel="stylesheet" type="text/css" href="styles/main.css" />\n
HTML;
        $this->addAdditionalScript();
echo <<< HTML
</head>
<body>
    <nav>
        <div class="logo">
            <h4>Pizzeria Void</h4>
        </div>
        <ul class="navbar">
            <li><a href="http://localhost/Bestellung.php">Bestellung</a></li>
            <li><a href="http://localhost/Baecker.php">Bäcker</a></li>
            <li><a href="http://localhost/Fahrer.php">Fahrer</a></li>
            <li><a href="http://localhost/Kunde.php">Kunde</a></li>
        </ul>

        <div class="burger">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <!-- Script for <nav>. -->
    <script src="scripts/nav.js"></script>\n
HTML;
    }

    /**
     * Generates the footer section of the page.
     * 
     * @return none
     */
    protected function generatePageFooter() {

echo <<< HTML
    <footer>
        <p>&copy; 2019 by Julian Segeth &amp; Bican Gül. All Rights Reserved. Praktikum Entwicklung webbasierter Anwendungen.</p>
    </footer>
</body>
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