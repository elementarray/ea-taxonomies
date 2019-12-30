<?php
// EventManagement
class eaTaxonomies_EventManagement_EventManager{

 	public function filter($a,$b){
		$this->string_arg=$a;
		$this->array_arg=$b;
		$out[]=$this->array_arg;
		
		return $out;
	}

	public function add_subscriber(){}
}