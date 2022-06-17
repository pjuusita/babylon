<?php

	class DOCFont {
		
		public $name;
		public $size;
		public $style;
		public $color;
		
		public function __construct($name,$size,$style,$color) {
			
			$this->name  = $name;
			$this->size	 = $size;
			$this->style = $style;
			$this->color = $color;
			
		}
	}


?>