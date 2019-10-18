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
        $this->connection = new mysqi(
            $servername, 
            $username, 
            $password, 
            $database
        );

        // Check if the connection failed.
        if ($this->$connection->connect_errno) {
            die("Connection to the MySQL database failed: " . $this->$connection->connect_errno);
        }
    }
    
    /**
     * Close the database connection.
     *
     * @return none
     */
    protected function __destruct() {

        // Close the database connection.
        $this->$connection->close();
    }
    
    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param $title is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($title = "") {

        $title = htmlspecialchars($title);
        header("Content-type: text/html; charset=UTF-8");
        
        // to do: output common beginning of HTML code 
        // including the individual headline
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter() {

        // to do: output common end of HTML code
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
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