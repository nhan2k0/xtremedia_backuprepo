<?php
	
	/**
	* Class for parsing Smiletag specific XML file
	*
	* This class is intended to serialize and deserialize array from/to XML file.
	*
	* @package Smiletag
	* @author Yuniar Setiawan <yuniarsetiawan@smiletag.com>
	* @since 2.3
	*/
	class St_XmlParser{
		
		/**
		* Parse XML from the specified configuration filename into array
		* The config file (path-config.xml,smiletag-config.xml) has simple xml structure
		* Apply locking to synchronize operation among users 
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseMainConfigToArray($fileName) {
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			
			$textData = implode($buffer);
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
				$rootElement =& $xmlDoc->documentElement;
										
				if($rootElement->hasChildNodes()){
					$childNodes =& $rootElement->childNodes;
					$childCount =& $rootElement->childCount;
					global $aaa;
					$aaa = $childCount;
					for($i=0;$i < $childCount;$i++){
						$childArray[trim($childNodes[$i]->nodeName)] = trim($childNodes[$i]->childNodes[0]->nodeValue);
					}
					
					
				}
				
				return $childArray;
			}else{
				return null;
			}
				
		}
		
		/**
		* Updates global configuration
		*
		* @param string $fileName The configuration filename
		* @param array $configuration The configuration which will be updated
		* @access public
		* @return boolean true if no error
		*/
		function updateConfiguration($fileName,$configuration){
			$originalConfig = $this->parseMainConfigToArray($fileName);
			
			
			$textData = '<?xml version="1.0"?>'."\n".'<smiletag_config>'."\n".'</smiletag_config>';
						
			$xmlDoc =& new DOMIT_Lite_Document();
			$xmlDoc->parseXML($textData,false);
												
			$rootElement =& $xmlDoc->documentElement;

			foreach ($originalConfig as $key=>$value){
				if(isset($configuration[$key])){
					$value = $configuration[$key];
				}
				$configElement =& $xmlDoc->createElement($key);
				$configElement->appendChild($xmlDoc->createCDATASection($value));
				$rootElement->appendChild($configElement);
			}		
									
			$buffer = '<?xml version="1.0"?>'."\n".$xmlDoc->toNormalizedString(false);
			
			//save backs to file
			$file = @fopen($fileName,'w') or die("Could not open file $fileName or permission denied");       
			flock($file,LOCK_EX);
			fwrite($file,$buffer);
			flock($file,LOCK_UN);
	        fclose($file);
			
	        return true;
		}
		
		/**
		* Parse XML from the message.xml into array
		* Apply locking to synchronize operation among users 
		*
		* @access public
		* @return array if the file has contents, null if empty
		*/
		function parseMessagesToArray($fileName) {
			$file = @fopen($fileName,'r') or die("Could not open file $fileName or permission denied");
			
			flock($file,LOCK_SH);
			while(!feof($file)){
			   $buffer[] = fgets($file,4096);
			}
			flock($file,LOCK_UN);
			fclose($file);
			//load data from file
			$textData = implode($buffer);
			
			if(!empty($textData)){
				
				$xmlDoc =& new DOMIT_Lite_Document();
				$xmlDoc->parseXML($textData,false);
				$rootElement =& $xmlDoc->documentElement;
				
				//traverse to nodes and save the values into childArray
				if($rootElement->hasChildNodes()){
					$rowNodes =& $rootElement->childNodes;
					$childCount =& $rootElement->childCount;
					global $tong_so_tin_nhan;
					global $page_num;
					global $max_message_per_page;
					$tong_so_tin_nhan = $childCount;
					$page_num = $_GET['p'];
					$begin_message = ($page_num-1)*$max_message_per_page;
					
					for($i=$begin_message; $i < $childCount;$i++){
									
						$currentNode      =& $rowNodes[$i];
						$currentNodeCount =& $currentNode->childCount;
									
						for($j=0;$j< $currentNodeCount;$j++){
							$childArray[$i][trim($currentNode->childNodes[$j]->nodeName)] = trim($currentNode->childNodes[$j]->childNodes[0]->nodeValue);	
						}
						
						
					}
				}
				return $childArray;
			}else{
				return null;
			}
				
		}  

	}
	
?>