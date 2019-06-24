<?php
class Foo {
   function myFoo() {
      return "Foo";
   }
}

class Bar extends Foo {
   function myFoo() {
      return "Bar";
   }
}

$foo = new Foo;
$bar = new Bar;
echo($foo->myFoo()); //"Foo"
echo($bar->myFoo()); //"Bar"
?>

<?php

class Base {
   function method() {
      return "base_method";
   }
}

class Extended extends Base {
   function method() {
      return "extended_method";
   }
}

$base = new Base();
$extended = new Extended();
echo($base->method()); //"base_method"
echo($extended->method()); //"extended_method"