<?php
//Create Script Menu 
//https://bash.cyberciti.biz/guide/Menu_driven_scripts
//https://serverfault.com/questions/144939/multi-select-menu-in-bash-script
//https://askubuntu.com/questions/1705/how-can-i-create-a-select-menu-in-a-shell-script
//https://stackoverflow.com/questions/11052162/run-bash-command-from-php
// specify connection info
$connect = mysql_connect('localhost','root','12345');
if (!$connect)
{
   die('Could not <span id="IL_AD1" class="IL_AD">
    connect to</span> MySQL: ' . mysql_error());
}

$cid =mysql_select_db('test',$connect); //specify db name

define('CSV_PATH','C:/wamp/www/csvfile/'); // specify CSV file path

$csv_file = CSV_PATH . "infotuts.csv"; // Name of your CSV file
$csvfile = fopen($csv_file, 'r');
$theData = fgets($csvfile);
$i = 0;
while (!feof($csvfile))
{
   $csv_data[] = fgets($csvfile, 1024);
   $csv_array = explode(",", $csv_data[$i]);
   $insert_csv = array();
   $insert_csv['ID'] = $csv_array[0];
   $insert_csv['name'] = $csv_array[1];
   $insert_csv['email'] = $csv_array[2];
   $query = "INSERT INTO csvdata(ID,name,email)
     VALUES('','".$insert_csv['name']."','".$insert_csv['email']."')";
   $n=mysql_query($query, $connect );
   $i++;
}
fclose($csvfile);
echo "File data successfully imported to database!!";
mysql_close($connect); // closing connection
?>

<?php
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
')or die(mysql_error());
$result2=mysqli_query($cons,"select count(*) count from $table");
$r2=mysqli_fetch_array($result2);
$count2=(int)$r2['count'];
$count=$count2-$count1;
if($count>0)
echo "Success";
echo "<b> total $count records have been added to the table $table </b> ";

// check if name only contains letters and whitespace
if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed"; 
    }
	
//ucfirst(); //convert first letter upercase
//strtolower($email);  //convert string to lowercase
$email = test_input($_POST["email"]);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $emailErr = "Invalid email format"; 
}
/**
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
**/
?>