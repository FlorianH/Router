<?php

require_once('Router.php');



Router::add('/', 'index');

Router::add('/one', 'test_one');

Router::add('/two', 'fzwei');
Router::add('/two/([a-z]+)', 'fzwei');
Router::add('/two/([a-z]+)/([0-9]+)', 'fzwei');

Router::add('/three/([a-z]+)', 'TestController.three');


Router::route();




function index($args) {
  
  echo "This is the <strong>index</strong> action.<br />";
  echo "The args where:<br /><pre>";
  
  print_r($args);
  
  echo "</pre>";

}//index()


function test_one($args) {
  
  echo "This is the <strong>test_one</strong> action.<br />";
  echo "The args where:<br /><pre>";
  
  print_r($args);
  
  echo "</pre>";
  
}//test_one()


function test_two($args) {
  
  echo "This is the <strong>test_two</strong> action.<br />";
  echo "The args where:<br /><pre>";
  
  print_r($args);
  
  echo "</pre>";
  
}//test_two()



class TestController {

  static function three($args) {
  
    echo "This is the <strong>TestController::three</strong> action.<br />";
    echo "The args where:<br /><pre>";

    print_r($args);

    echo "</pre>";
  
  }//three()

}//class TestController