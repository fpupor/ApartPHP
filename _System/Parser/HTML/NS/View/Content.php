<?php
final class System__Parser__HTML__NS__View__Content{
	public function beforeParser($node = null, $scope = null){
		
		
		
	}
	public function afterParser($node = null, $scope = null){

		$document = $scope->ownerDocument;
		$masterPage = $document->getProperty('masterPage');
		
		$placeName = $node->getAttribute('placeholder');
		
		$placeholders = $masterPage->document->getElementsByTagNamespace('placeholder');
		
		foreach($placeholders as $placeholder){


			if($placeholder->getAttribute('name') == $placeName){

				$parent = $placeholder->parentNode;
				$childNodes = $parent->getChildNodes();
				$count = count($childNodes);
				$position = 0;
				
				while($position < $count){
				
					if($childNodes[$position] === $placeholder){
						
						$parent->removeChildNode($position);
						
						$newChilds = $node->getChildNodes();
						
						foreach($newChilds as $newChild){
							$parent->addChildNode($newChild, $position);
							$position++;
						}
						break;
					}
					$position++;
				}
				break;
			}
		}
		
	}
	public function lastParser($node = null){
		
	}
}