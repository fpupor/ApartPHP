<?php
final class System__Parser__HTML__NS__View__Text{
	public function beforeParser($node = null, $scope = null){
		$node->runatServer = true;
	}
	public function afterParser($node = null){
		
	}
	public function lastParser($node = null){
		
	}
}