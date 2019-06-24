<?php
/**
 * @author Engr. Nayab Bukhari, Syed
 * @copyright 2019
 * outputs an \n list of all integers in the range of 1-100
 * in place of all numbers that are divisible by 3, outputs string “foo” instead
 * in place of all numbers that are divisible by 5, outputs string “bar” instead
 * for numbers that are divisible by both 3 and 5, outputs string “foobar” instead
 *
 **/

foreach(range(1,100)as$n){
   echo(''==($s=($n%3==0?"foo":"").($n%5==0?"bar":""))?$n:$s).', ';
}
echo "\n";

?>