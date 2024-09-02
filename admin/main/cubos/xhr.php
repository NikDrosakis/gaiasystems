<?php 
if($a=='func'){
	$this->func($b,$c);
}elseif($a=='cubos_get'){
  $buffer=$this->getWidgetsBuffer();
  echo json_encode($buffer);
}