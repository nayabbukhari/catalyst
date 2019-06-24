<?php

$db_host= 'localhost';
$db_username = 'nayab';
$db_password = 'Allh_123';
$db_primaryDatabase = 'catalyst';
$db_primaryTable = 'users';
$filename  = '/home/nayab/Downloads/assignment-task/users.csv';

// Connect to the database
$dbConnection = new mysqli($db_host, $db_username, $db_password);

//$dbConnection = new mysqli($db_host, $db_username, $db_password, $db_primaryDatabase);
//$connect = mysql_connect('localhost','nayab','Allh_123');
// If there are errors (if the no# of errors is > 1), print out the error and cancel loading the page via exit();
if (mysqli_connect_errno()) {
    printf("Could not connect to MySQL databse: %s\n", mysqli_connect_error());
    exit();
}else{
    // Create database

    $sql = "CREATE DATABASE IF NOT EXISTS $db_primaryDatabase DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci";
    echo "Database created successfully \n";
        //$query = "SELECT ID FROM catalyst";
        //$result = mysqli($dbConnection, $query);
        $sql = "USE $db_primaryDatabase";
        //if(empty($result)) {
        $dbConnection->query($sql);
    $sql = "SELECT * FROM $db_primaryTable";
    $table_exists=$dbConnection->query($sql);
    if(empty($table_exists)) {
        $sql = "CREATE TABLE IF NOT EXISTS users (
                id int(11) AUTO_INCREMENT,
                name varchar(15) NOT NULL,
                surname varchar(15) NOT NULL,
                email varchar(255) NOT NULL,
                PRIMARY KEY  (id)
                )";
        $table_created = $dbConnection->query($sql);
    }
    if(empty($table_created) or !empty($table_exists)) {
        $row = 1;
        if (($handle = fopen("$filename", "r")) !== FALSE) {
            //print_r($filename);
            //echo '\n';
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //$num = count($data);
                //echo "Total Records is : ".$num."\n";

                //echo "<p> $num fields in line $row: <br /></p>\n";

                //for ($c=0; $c < $num; $c++) {
                    $blackpowder = $data;
                    $dynamit = implode(";", $blackpowder);
                    $pieces = explode(";", $dynamit);

                    $name = $pieces[0];
                    $surname = ucfirst("$pieces[1]");
                    $email = strtolower("$pieces[2]");

                    $name = ucfirst($name);
/*
             if(!preg_match("/^[a-zA-Z ]*$/",$email)
                 AND (!filter_var($email, FILTER_VALIDATE_EMAIL))
                 AND (substr_count($email,"@") == 1)
                 AND ($row > 1)){
*/
                if((!filter_var($email, FILTER_VALIDATE_EMAIL))
                    AND (substr_count($email,"@") == 1)
                    ){
                 $sql = "INSERT INTO $db_primaryTable(name,surname,email)
                            VALUES('".$name."','".$surname."','".$email."')";

                   echo "$row => "'.ucfirst($name)..'" "'.ucfirst($surname).'" has valid email : "'.$email .'"\n \n";
                   $dbConnection->query($sql);
                   //echo $sql."\n";
                }
                $row++;
            }
        }


/*
        $csvfile = "users.csv"; // Name of your CSV file
            $csvfile = fopen($csvfile, 'r');
            $csvData = fgets($csvfile);
            $i = 0;
            while(!feof($csvfile))
            {
                $csv_Data[] = fgets($csvfile, 1024);
                $csvData = explode(",", $csv_Data[$i]);

                $insert_csv = array();
                $insert_csv['name'] = ucfirst($csvData[0]);
                $insert_csv['surname'] = ucfirst($csvData[1]);
                $insert_csv['email'] = strtolower($csvData[2]);

                if (filter_var($insert_csv['email'], FILTER_VALIDATE_EMAIL)) {

                    $sql = "INSERT INTO users('name','surname','email')
                            VALUES('".$insert_csv['name']."','".$insert_csv['surname']."',
                            '".$insert_csv['email']."')";

                    $n=$dbConnection->query($sql);
                    $i++;
                }
            }
*/
            fclose($handle);
            echo "File data successfully imported to database!! \n";
            $dbConnection->close(); // closing connection


        }else{
        echo "Error creating table: " . $dbConnection->error();
        }

    }



/*
$file = fopen("contacts.csv","r");

while(! feof($file))
  {
  print_r(fgetcsv($file));
  }

fclose($file);

/////////////
mysqli_query($cons, '
    LOAD DATA LOCAL INFILE "'.$file.'"
        INTO TABLE '.$table.'
        FIELDS TERMINATED by \',\'
        LINES TERMINATED BY \'\n\'
')or die(mysql_error($cons));
$result2=mysqli_query($cons,"select count(*) count from $table");
$r2=mysqli_fetch_array($result2);
$count2=(int)$r2['count'];
$count=$count2-$count1;
if($count>0)
echo "Success";
echo "<b> total $count records have been added to the table $table </b> ";

//ucfirst(); //convert first letter upercase
//strtolower($email);  //convert string to lowercase
if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email))
{ 
echo "<center><font face='Verdana' size='2' color=red>Invalid email</font></center>";
}else{
echo "<center><font face='Verdana' size='2' color=green>Valid Email</font></center>";
}
$name = test_input($_POST["name"]);
if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
  $nameErr = "Only letters and white space allowed"; 
}
*/
?>