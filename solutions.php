<?php
//php solutions.php --u=nayab -p=Allh_123 --h=localhost --create_table --db=catalyst --file=users.csv
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
    public $dry_run;
    public $create_table;
    public $help;

    /**
     * @param $argv
     * @return array of parameters
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
     * @return set default variable value
     */
    function set_var() {
        foreach ($this->arguments($_SERVER['argv']) as $k => $d) {
            if(strpos($d, '=') == false) {
                ($k == 'u') ? ($this->db_username = $d) : '';
                ($k == 'p') ? ($this->db_password = $d) : '';
                ($k == 'h') ? ($this->db_host = $d) : '';
                ($k == 'db') ? ($this->db_primaryDatabase = $d) : ($this->db_primaryDatabase = 'catalyst');
                ($k == 'f') ? ($this->filename = $d) : '';
                ($k == 'dry') ? ($this->dry_run = true) : '';
                ($k == 'create') ? ($this->create_table = true) : '';
                ($k == 'help') ? ($this->help = true) : '';
            }
        }
        //static table name
        $this->db_primaryTable =  'users';
    }

    /**
     * @return string
     */
    function helps(){
        $result = "Usage: php [options] [--f] <file> [--] [args...] \n
               php [CodeFileName] --[parameter]\n
              --help           This help\n
              --u              Set Database user name\n
              --p              Set Database user password\n
              --h              Set Database host \n
              --db             Set Database name - Optional parameter \n
              --create_table   Run script to create table if not exists \n
              --f              Set CSV file name <file>. \n
              --dry_run        Run script without any DB option. \n ";
    return print_r($result);
    }

    /**
     * @param string $db_primaryDatabase
     * @return database connection
     */
    function create_db(){
        //Connection to the database
        $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password);

        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS $this->db_primaryDatabase DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
        if (empty($dbConnection->query($sql))) {
            //update database connection with default database.
            $dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);
        }
            return $dbConnection;
    }

    /**
     * @param string $db_primaryTable
     * @return bool|int
     */
    function create_table(){
        $sql = "CREATE TABLE IF NOT EXISTS $this->db_primaryTable (
                id int(11) AUTO_INCREMENT, name varchar(15) NOT NULL,
                surname varchar(15) NOT NULL, email varchar(255) NOT NULL,
                PRIMARY KEY  (id), UNIQUE KEY `email` (`email`) )";

        if (!empty($this->create_db()->query($sql))) {
            $stdout = fwrite(STDERR, "Table : $this->db_primaryTable is created/existed\n");
            return $this->db_primaryTable;
        }
    }

    /**
     * @param $sql = sql query, $name, $surname, $email = database fields
     * @return bool  //insert_record
     */
    function insert_record($sql,$name,$surname, $email ){
        //$dbConnection = new mysqli($this->db_host, $this->db_username, $this->db_password, $this->db_primaryDatabase);
        if ($this->create_db()->query($sql)) {
            fwrite(STDERR, "New record email($email) saved\n \n");
        }else{
            $stdout = fwrite(STDERR, "Email($email) already in DB\n");
        }
    }

    /**
     * @complete all process
     */
    function process(){
        //set default variables values
        $this->arguments($_SERVER['argv']);
        $this->set_var($_SERVER['argv']);

        if($this->help==true){
            $this->helps();
            exit();
        }

        if(!$this->dry_run ) {
            $stdout = fwrite(STDERR, "Live Mode Enabled \n");
            if($this->db_username and $this->db_password and $this->db_host) {
                $this->create_db();
            }

           if($this->create_table == true){
                $this->create_table();
            }

           if(($this->create_db() and $this->filename)) {
                $row = 1;
                if (($handle = fopen($this->filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $blackpowder = $data;
                        $dynamit = implode(";", $blackpowder);
                        $pieces = explode(";", $dynamit);
                        //set csv cell value to variable
                        $name = ucwords(strtolower($pieces[0]));
                        $surname = ucwords(strtolower("$pieces[1]"));
                        $email = strtolower("$pieces[2]");

                        if (!preg_match("/^[a-zA-Z ]*$/", $email)
                            AND (substr_count($email, "@") == 1)
                            AND ($row >= 2)) {

                            $sql = "INSERT INTO $this->db_primaryTable(name, surname, email)
                                    VALUES('" . $name . "','" . $surname . "','" . $email . "')";
                            $this->insert_record($sql, $name, $surname, $email);
                            }
                        $row++;
                    }
                }
                fclose($handle);
            }
    }else{
            $stdout = fwrite(STDERR, "Dry Mode Enabled \n");
            if(($this->create_db() and $this->filename)) {
                $row = 1;
                if (($handle = fopen($this->filename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $blackpowder = $data;
                        $dynamit = implode(";", $blackpowder);
                        $pieces = explode(";", $dynamit);
                        //set csv cell value to variable
                        $name = ucwords(strtolower($pieces[0]));
                        $surname = ucwords(strtolower("$pieces[1]"));
                        $email = strtolower("$pieces[2]");

                        if (!preg_match("/^[a-zA-Z ]*$/", $email)
                            AND (substr_count($email, "@") == 1)
                            AND ($row >= 2)) {
                            $stdout = fwrite(STDERR, "$name $surname has valid email($email)\n");
                        }else{
                            $stdout = fwrite(STDERR, "$name $surname has not valid email($email)\n");
                        }
                        $row++;
                    }
                }
                fclose($handle);
            }
        }
        $this->create_db()->close();
    }
}

/////////////Display GUI mode///////////////////
echo "\n \n/////////////////////////////////////////////////////////////////////\n";
echo "//                    WELCOME to php CLI                          ///\n";
echo "// commands:  php solutions.php                                   ///\n";
echo "// argv:  --create_table={tablename}    --u={DBusername}          ///\n";
echo "//        --p={DBpassword} --h={DBhost}   --dry_run    --h        ///\n";
echo "// example: php {filename.php} --help                             ///\n";
echo "// example: php solutions.php   --dry_run                         ///\n";
echo "// Note:php solutions.php --u --h --p are required to execute file///\n";
echo "///////////////////////////////////////////////////////////////////// \n \n";
$catalyst = new catalyst();
$catalyst->process();

?>