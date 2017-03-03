<?php
namespace app\components;
use Yii;


class Registry {

	protected  static  $store  =  array();   
	private static $instance;
	  
	public static function instance() {
	    if(!isset(self::$instance)) {
	        self::$instance = new self();
	    }
	    return self::$instance;
	}
	  
	public function  isValid($key)  {
		return  array_key_exists($key,  Registry::$store);
	}
	 
	public function  get($key)  {
		if  (array_key_exists($key,  Registry::$store))
		return  Registry::$store[$key];
	}
	 
	public function  set($key,  $obj)  {
		Registry::$store[$key]  =  $obj;
	}
}