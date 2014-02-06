<?php
final class System__Parser__HTML__NS__View__Masterpage{
	public function beforeParser($node = null, $scope = null){
		
		$file = $node->getAttribute('file');
		$masterPage = __btsLoadPage($file);
		
		$document = $scope->ownerDocument;
		$document->setProperty('masterPage', $masterPage);
		
		$document->runatServer = true;

	}
	public function afterParser($node = null){
		
	}
	public function lastParser($node = null){
		
	}
}