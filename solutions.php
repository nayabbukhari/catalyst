<?php
/**
 * @author Engr. Nayab Bukhari, Syed
 * @copyright 2019
 * outputs: save  valid email
 * Dry_run: Don't Save record
 * Get parameters from CLI
 * validate and exception handling
 *
 **/
class catalyst extends mysqli
{
    protected $dbConnection;
    private $db_host;
    private $db_username;
    private $db_password;
    private $db_primaryDatabase;
    public $db_primaryTable;
    public $filename;

    /**
     * @param $argv
     * @return array
     *
     */
    function arguments($argv) {
        $_ARG = array();
        foreach ($argv as $arg) {
            if (preg_match('#^-{1,2}([a-zA-Z0-9]*)=?(.*)$#', $arg, $reg)) {
                $_ARG[$reg[1]] = $reg[2];
            }
        }
        return $_ARG;
    }

    /**
     * @param $argv
     * @return set default variable
     */
    function set_var() {
        foreach ($this->arguments($_SERVER['argv']) as $k => $d) {
            if(strpos($d, '=') == false) {
                ($k == 'u') ? ($this->db_username = $d) : '';
                ($k == 'p') ? ($this->db_password = $d) : '';
                ($k == 'h') ? ($this->db_host = $d) : '';
                ($k == 'create') ? ($this->db_primaryTable = $d) : ($db_primaryTable = 'users');
                ($k == 'db') ? ($this->db_primaryDatabase = $d) : ($this->db_primaryDatabase = 'catalyst');
                ($k == 'file') ? ($this->filename = $d) : ($this->filename = 'users.csv');
                ($k == 'dry') ? ($this->dry_run = true) : '';
            }else{
                $d= substr($d, strpos($d, '=')+1);
                ($k == 'u') ? ($this->db_username = $d) : '';
                ($k == 'p') ? ($this->db_password = $d) : '';
                ($k == 'h') ? ($this->db_host = $d) : '';
                ($k == 'create') ? ($this->db_primaryTable = $d) : ($db_primaryTable = 'users');
                ($k == 'db') ? ($this->db_primaryDatabase = $d) : ($this->db_primaryDatabase = 'catalyst');
                ($k == 'file') ? ($this->filename = $d) : ($this->filename = 'users.csv');
                ($k == 'dry') ? ($this->dry_run = true) : '';

            }
        }
    }

    /**
     * @param string $db_primaryDatabase
     * @return bool
     */
    function create_db($db_primaryDatabase='catalyst'){
        //Connect to the database
        $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password);

        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS $db_primaryDatabase DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";

        if (empty($dbConnection->query($sql))) {
            //update database connection with default database.
            $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);
            $stdout = fwrite(STDERR, "Database : $dbConnection is created/existed\n");
        }
            return $dbConnection;
    }

    /**
     * @param string $db_primaryTable
     * @return bool|int
     */
    function create_table($db_primaryTable = 'users'){
        $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);
        $sql = "SELECT * FROM $db_primaryTable";
        if (empty($dbConnection->query($sql))) {
            $sql = "CREATE TABLE IF NOT EXISTS users (
                    id int(11) AUTO_INCREMENT,
                    name varchar(15) NOT NULL,
                    surname varchar(15) NOT NULL,
                    email varchar(255) NOT NULL,
                    PRIMARY KEY  (id),
                    UNIQUE KEY `email` (`email`)
                    )";
            if (!empty($dbConnection->query($sql))) {
                $stdout = fwrite(STDERR, "Table : $db_primaryTable is created/existed\n");
                //exit(0);
                return $stdout;
            }else{
                return $db_primaryTable;
            }
        }
    }

    /**
     * @param $sql
     * @param $name
     * @param $surname
     * @param $email
     * @return bool
     */
    function insert_record($sql,$name,$surname, $email ){
        $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);

        if (!isset($this->dry_run)) {
            fwrite(STDERR, "\n You are in live mode, only valid record will save to Database\n");
            if ($dbConnection->query($sql)) {
                fwrite(STDERR, "New record $name  $surname  with valid email :  $email  created\n \n");
                return true;
            }
        }else{
            fwrite(STDERR, "You are in dry run mode, no insert record of valid email: $email\n");
        }
        return false;
    }

    /**
     * @complete all process
     */
    function process(){
        $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);
        //$dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password);
        $row = 1;
            if (($handle = fopen($this->filename, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $blackpowder = $data;
                    $dynamit = implode(";", $blackpowder);
                    $pieces = explode(";", $dynamit);

                    $name = ucwords(strtolower($pieces[0]));
                    $surname = ucwords(strtolower("$pieces[1]"));
                    $email = strtolower("$pieces[2]");


                    if(!preg_match("/^[a-zA-Z ]*$/",$email)
                    //AND (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    AND (substr_count($email,"@") == 1)
                    AND ($row > 1)){

                    /*
                    if ((!filter_var($email, FILTER_VALIDATE_EMAIL))
                        AND (substr_count($email, "@") == 1)){
                        */
                        if (!isset($dry_run)) {
                            //echo "$row => $name  $surname has valid email :  $email  \n \n";
                            $sql = "INSERT INTO $this->db_primaryTable(name, surname, email)
                                    VALUES('" . $name . "','" . $surname . "','" . $email . "')";
                            $this->insert_record($sql,$name,$surname, $email);
                        }
                    }else{
                        $stdout = fwrite(STDERR, "$name  $surname has not valid email :  $email  \n");
                    }
                    $row++;
                }
            }
            fclose($handle);
            $dbConnection->close();
    }
}

/////////////Display GUI mode///////////////////
echo "\n \n/////////////////////////////////////////////////////////////////////\n";
echo "//                    WELCOME to php CLI                          ///\n";
echo "// commands:  php solutions.php                                   ///\n";
echo "// argv:  --create_table={tablename}    --u={DBusername}          ///\n";
echo "//        --p={DBpassword} --h={DBhost}   --dry_run    --h        ///\n";
echo "// example: php --h                                               ///\n";
echo "// example: php solutions.php   --dry_run                         ///\n";
echo "// Note:php solutions.php --u --h --p are required to execute file///\n";
echo "///////////////////////////////////////////////////////////////////// \n \n";
$catalyst = new catalyst();
$catalyst->arguments($argv);
$catalyst->set_var($argv);
$catalyst->create_db($db_primaryDatabase='catalyst');
$catalyst->create_table($db_primaryTable = 'users');
$catalyst->process();

?>