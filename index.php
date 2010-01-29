<?php

require_once('Router.php');


Router::add('/', 'index');
Router::add('/one', 'test_one');
Router::add('/two', 'test_two');
Router::add('/two/([a-z]+)', 'test_two');
Router::add('/three/([a-z]+)', 'TestController.three');

Router::run();



function index($args) {
  
  echo "This is the <strong>index</strong> action.<br />";

}//index()


function test_one($args) {
  
  echo "This is the <strong>test_one</strong> action.<br />";
  
}//test_one()


function test_two($args) {
  
  echo "This is the <strong>test_two</strong> action.<br />";
  echo "The args where:<br /><pre>";
  print_r($args);
  echo "</pre>";
  
}//test_two()



class TestController {

  function three($args) {
  
    echo "This is the <strong>TestController::three</strong> action.<br />";
    echo "The args where:<br /><pre>";
    print_r($args);
    echo "</pre>";
  
  }

}