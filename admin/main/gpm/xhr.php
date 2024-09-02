<?php
if($a=='func'){
	$this->gpm->func($b,$c);

}elseif($a=='system_get'){
 $buffer=$this->getSystemLogsBuffer();
  echo json_encode($buffer);
}