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
 * @author   Bican Misto
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

        // Check if the database connection failed.
        if ($this->connection->connect_errno) {
            die("Connection to the MySQL database failed: " . $this->connection->connect_errno);
        }
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
     * This function can be overwritten to add additional CSS files or scripts.
     */
    protected function addAdditionalHead() { }
    
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

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Default CSS files. -->
    <link rel="stylesheet" type="text/css" href="styles/main.css" />

    <!-- Default JS imports.-->
    <script src="scripts/main.js"></script>\n
HTML;

    $this->addAdditionalHead();
    
echo <<< HTML
\n</head>
<body>\n
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