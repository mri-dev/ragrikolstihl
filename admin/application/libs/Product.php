<?

    class Product{
		public static function clear($text){
			return $text;
			//return substr($text,3);
		}
		public static function getTermekIDFromUrl(){
			$url 	= Helper::GET();
			$id 	= false; 
			foreach($url as $u){
				if(strpos($u,'_-') > 0){
					$x 	= explode('_-',$u);
					$id = (int)$x[1];
					 	
					break;
				}
			}
			return $id;
		}
		
		public static function rewriteImageTinyMCE($text)
		{

			$pos = strpos( $text, '../' );

			while( strpos( $text, '../' ) !== false ) 
			{
				$text = str_replace('../', '', $text);
			}

			$text = str_replace('src/uploads/', UPLOADS, $text);

			return $text;
		}

		public static function attackedLink($linkek){
			$ret = false;
			if($linkek == '' || is_null($linkek)) return false;
			
			$linkek = trim($linkek,'||');
			
			$cut = explode('||',$linkek);
			
			foreach($cut as $c){
				$ic = explode("==>",trim($c));
				$ret[] = array(
					'nev' => trim($ic[0]),
					'url' => trim($ic[1])
				);	
			}	
					
			return $ret;
		}
		public static function clearMarka($marka){
			$pattern = '/::(.*)::/i';
			$marka = preg_replace($pattern,'',$marka);
			return $marka;
		}
	
		public static function transTime($trans){
			$transports = array(
				'1' =>  '1-3 munkanap',
				'2' => '3-4 munkanap'
			);
			
			$trs = $transports[$trans];
			
			if($trs == '') return '';
		
			$trs = '('.$trs.')';
			
			return $trs;
		}
    }

?>