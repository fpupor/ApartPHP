<?php
final class System__Parser__HTML__NS__View__Include{
	public function beforeParser($node = null, $scope = null){
		$file = $node->getAttribute('file');
		$masterPage = __btsLoadPage($file);

		$parent = $node->parentNode;
		$childNodes = $parent->getChildNodes();
		
		$count = count($childNodes);
		$position = 0;
		
		while($position < $count){
			if($childNodes[$position] === $node){
				
				$parent->removeChildNode($position);
				
				$newChilds = $masterPage->document->getChildNodes();
				
				foreach($newChilds as $newChild){
					$parent->addChildNode( $newChild, $position);
					$position++;
				}
				break;
			}
			$position++;
		}
	}
	public function afterParser($node = null, $scope = null){

	}
	public function lastParser($node = null){
		
	}
}