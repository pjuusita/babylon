<?php
class UITab {
	
	private $fields = null;
	private $isactive = false;
	private $tabID;
	private $action;
	private $data;
	
	public function __construct($title,$action) {
	
		$this->title = $title;
		$this->action = $action;
	}
	
	public function addField($field) {
		
		if ($this->fields==null) $this->fields = array();
		$this->fields[] = $field;
	} 
	
	public function setActive($isactive) {
		$this->isactive = $isactive;
	}
	
	public function isActive() {
		return $this->isactive;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function setData($data) {
		$this->data = $data;
	}
	
	public function show() {
		
		$fields = $this->fields;

		if ($fields==null) return;
		
		foreach($fields as $index => $field) {
			$field->show();
		}
	}
}
