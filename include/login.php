<?php include_once "models/Session.php";
Session::init();
spl_autoload_register(function($models){

  include 'models/'.$models.".php";

});