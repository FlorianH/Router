<?php

/**
* A simple url router
* 
* This file contains a small url router, which is intended to
* be used in very small projects, where no real MVC framework
* fits the needs of the project.
* 
* To create a route, you just have to call the "Router::add"
* function and provide the intended url and the name of the
* function which is supposed to be called if the route matches.
*
* The function name can contain a regular expression. Every
* matching argument, that can be extracted will be stored in
* the $args array.
*
* Every function, that is supposed to be the endpoint of an
* url has to accept a single variable, which will contain the
* array of extracted arguments.
* 
* If you want to use class methods as your endpoints, just
* add the class's name in front of the method's name and add
* a dot after the class's name.
* 
* For the bar method of this class:
* class Foo {
*   function bar($args) {...}
* }
* 
* the endpoint has to be "Foo.bar". See example usage in the
* example file (index.php).
* 
* The Router has only static methods, because that makes the api
* super nice (at least IMHO).
* 
* 
* Usage:
* 
* ---- snipp ----
* 
* require_once('Router.php');
* 
* Router::add('/example', 'example_function');
* 
* function example_function($args) {
* 
*   echo "Hello!<br />";
*   print_r($args);
* 
* }
* 
* ---- snipp ----
* 
* 
* Made 2009 Florian Herlings (florianherlings.de)
* 
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
* Version:    0.1
* Date:       29.01.2009
*/
class Router {


  /**
  * This static array, holds the defined urls,
  * which were added by the static function "add".
  */
  protected static $routes = array();


  /**
  * This static method adds a new route to the router.
  * The first argument is expected to be a string containing
  * the route (can be declared as a regex) while the second
  * string is expected to be the endpoint.
  * An endpoint is the string name of a function, which will
  * be called if the route matches.
  */
  public static function add($route, $endpoint) {
    self::$routes[] = array('route' => '@' . $route . '@i', 'endpoint' => $endpoint);
  }//add()
  
  
  
  /**
  * This static method runs the routing process.
  * Currently the method will iterate through all the
  * routes to find the one matching route. If no route
  * matches the current url arguments, the default route
  * "/" will be triggered (if it was added to the router).
  */
  public static function run() {
  
    $args = self::get_args();
  
  
    //Sort routes by string length to check the long ones first
    //(otherwise a shorter route might already match, while the
    //longer route has not yet been checked).
    usort(self::$routes, create_function('$a,$b', 'return strlen($a["route"]) < strlen($b["route"]);'));
    

    foreach(self::$routes as $route) {
      
      $route_matches_args = preg_match($route['route'], $args, $regs);

      if ($route_matches_args)
      {
        //The first argument contains the original string that matched.
        //This first argument will be removed.
        $regs = array_slice($regs, 1);
        self::execute_matched_route($route['endpoint'], $regs);
        break;
      }
      
      
    }//foreach
    
  
  }//route()




  /**
  * This static method executes a given endpoint with the provided
  * arguments. If the string containing the endpoint's name contains a
  * point (".") the method expects it to be a class's method. If this
  * case is detected, the method instantiates the class of the object
  * and calls the method on the new object.
  */
  protected static function execute_matched_route($endpoint, $arguments) {

      if (strpos($endpoint, '.') === false) {

        $endpoint($arguments);

      } else {

        list($class, $function) =  explode('.', $endpoint);

        $instance = new $class();
        $instance->$function($arguments);

      }
  
  }//execute_matched_route()


  /**
  * This method tries to extract the interesting part from the
  * request url.
  */
  protected static function get_args() {
  
    $prefix_str = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
    
    if ($prefix_str !== '/')
      $args = '/'.str_replace($prefix_str, '', $_SERVER['REQUEST_URI']);
    else
      $args = '/'.$_SERVER['REQUEST_URI'];  
  
    return $args;
  
  }//get_args()



}//class Router