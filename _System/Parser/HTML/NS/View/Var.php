<?php
final class System__Parser__HTML__NS__View__Var{
	public function beforeParser($node = null, $scope = null){
		
		$document = $scope->ownerDocument;
		$masterPage = $document->getProperty('masterPage');
		
		$placeName = $node->getAttribute('name');
		$placeValue = $node->getAttribute('value');
		
		$placeholders = $masterPage->document->getElementsByTagNamespace('text');
		
		foreach($placeholders as $placeholder){


			if($placeholder->getAttribute('name') == $placeName){

				$parent = $placeholder->parentNode;
				$childNodes = $parent->getChildNodes();
				$count = count($childNodes);
				$position = 0;
				
				while($position < $count){
				
					if($childNodes[$position] === $placeholder){
						
						$parent->removeChildNode($position);
						
						$var = new System__Parser__HTML__Text($placeValue);
						
						$parent->addChildNode($var, $position);
						
						break;
					}
					$position++;
				}
				break;
			}
		}
		
	}
	public function afterParser($node = null, $scope = null){

		
		
	}
	public function lastParser($node = null){
		
	}
}