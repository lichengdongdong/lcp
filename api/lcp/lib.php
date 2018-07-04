<?php
class  LcMessage{

}

/***/
class LcError extends Exception{
	public $type="error";
    public $message;
    public $errors=array();

    /**/
    function  construct($message){
    	$this->message=$message;
    }

    /**/
    function show(){
    	//
        if($GLOBALS['look']!=''){
        	$this->look=$GLOBALS['look'];
        }

        //
    	echo json_encode($this,true);
    }
}


/**/
class LcSuccess extends LcMessage{
	public $type="success";
    public $message="";

    /**/
    function  __construct($message){
    	$this->message=$message;
    }

    /**/
    function show(){
        
    	echo json_encode($this,true);
    }
}