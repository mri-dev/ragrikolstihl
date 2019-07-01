<?
use ProductManager\Products;

class app extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = '';

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description','');
			$SEO .= $this->view->addMeta('keywords','');
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('type','website');
			$SEO .= $this->view->addOG('url',DOMAIN);
			$SEO .= $this->view->addOG('image',DOMAIN.substr(IMG,1).'noimg.jpg');
			$SEO .= $this->view->addOG('site_name',TITLE);

			$this->view->SEOSERVICE = $SEO;
		}

		public function nm()
		{
			set_time_limit ( 120 ); // hosszabb futási idejű listák esetén érdemes fentebb állítani!

			// paraméterek:

			$hostandport="86.59.218.107:80"; // ide a webservice tényleges elérését kell írni!

			if (isset($_GET['host'])) {
				$hostandport= $_GET['host'].":80"; // ide a webservice tényleges elérését kell írni!
			}

			$volume = ""; // kötetjel: üres azaz alaértelmezett. "A_" kötet esetén = "_A_"!

			$listname = $_GET['lista']; // lehet még pl: "WebService Cikk Minta", "WebService Keszlet Minta"...

			$username = "WebServer"; // A WebServer terminálhoz megadott felhasználónév/jelszó. Ez az alapértelmezett.

			$password = "WEBSERVER";

			// lekérdezés futtatása:

			header('Content-type: text/xml'); // ez csak ahhoz kell, hogy szépen írja ki a választ.

			$curl = curl_init();

			$param = URLEncode ( 'Listanév' ).'='.URLEncode ( $listname );

			$param .= '&PrnForma=XML';

			curl_setopt($curl,CURLOPT_URL,'http://'.$hostandport.'/LGQUERY'.$volume.'?'.$param);

			curl_setopt($curl, CURLOPT_USERPWD, $username.':'.$password );

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($curl);

			curl_close($curl);

			echo $response;

		}

		/**
		 * Documents click log
		 * */
		public function dcl()
		{
			$key 	= $this->view->gets[2];
			$xkey 	= explode(".",$key);

			$hashkey 	= trim($xkey[0]);
			$uid 		= trim($xkey[1]);
			// Get doc
			$doc = $this->db->squery("SELECT ID, filepath, tipus FROM shop_documents WHERE hashname = :hash;", array('hash'=> $hashkey));

			if ($doc->rowCount() != 0)
			{
				$data = $doc->fetch(\PDO::FETCH_ASSOC);

				// Log
				if ( true ) {
					$this->db->insert(
						'shop_documents_click',
						array(
							'felh_id' => $uid,
							'doc_id' => $data['ID']
						)
					);
				}

				$link = '/';

				switch ($data['tipus'])
				{
					case 'external':
						$link = $data['filepath'];
					break;
					case 'local':
						$link = IMGDOMAIN.$data['filepath'];
					break;
				}

				// Redirect
				Helper::reload($link);
			} else
			{
				Helper::reload('/');
			}
		}

		/**
		 * Ajánló partnerkód kupon szelvény generátor
		 * */
		public function partner_coupon()
		{
			header('Content-type: image/svg+xml');


			echo $this->view->render('templates/render_partner_coupon');

			if ( isset($_GET['print']) ) {
			}
		}

		function arukereso(){
			header('Content-type: text/xml');
			$this->Products = new Products( array( 'db' => $this->db ));
			// Árukereső
			$termekek = $this->Products->prepareList( array(
				'except' => array(
					'arukereso' => 0
				)
			))->getList();

			$osszeghatar = 0;
			$szallitas = $this->db->query("SELECT osszeghatar, koltseg FROM `shop_szallitasi_mod` where osszeghatar != 0 ORDER BY osszeghatar ASC LIMIT 0,1;")->fetch(PDO::FETCH_ASSOC);

			if ( $szallitas['osszeghatar'] != '' || $szallitas['osszeghatar'] > 0 ) {
				$osszeghatar = (int)$szallitas['osszeghatar'];
			}

			/* * /
			echo '<pre>';
			print_r($termekek);
			echo '</pre>';
			/* */

			$wire = '<?xml version="1.0" encoding="UTF-8" ?>';
			$wire .= '<products>';
				foreach($termekek as $t):
					$leiras = 'Szín: '.($t['szin'] ? $t['szin'] : '-').'; '.'Méret: '.($t['meret'] ? $t['meret']:'-').'; '.($t['rovid_leiras'] ? $t['rovid_leiras'].'; ' : '');
					if ( count( $t['hasonlo_termek_ids']['colors']) > 1 )  {
						$leiras .= " További ".count( $t['hasonlo_termek_ids']['colors']).' db színvariáció elérhető!';
					}
					$szall 	= ( $osszeghatar == 0 || ( $osszeghatar > 0 && $t[ar] > $osszeghatar ) ) ? 'ingyenes' : $szallitas['koltseg'];

					$wire .= '<product>';
						$wire .= '<manufacturer><![CDATA[ Arena ]]></manufacturer>';
						$wire .= '<category><![CDATA[ Sport és Fitness > Úszás > '.$t[alap_kategoria].' ]]></category>';
						$wire .= '<image_url><![CDATA[ '.$t[profil_kep].' ]]></image_url>';
						$wire .= '<description><![CDATA[ '.$leiras.' ]]></description>';
						$wire .= '<name><![CDATA[ '.$t[product_nev].' ]]></name>';
						$wire .= '<price>'.$t[ar].'</price>';
						$wire .= '<product_url><![CDATA[ '.$this->view->settings['domain'].'/termek/'.\PortalManager\Formater::makeSafeUrl($t[product_nev],'_-'.$t[product_id]).' ]]></product_url>';
						$wire .= '<delivery_cost><![CDATA[ '.$szall.' ]]></delivery_cost>';

					$wire .= '</product>';
				endforeach;
			$wire .= '</products>';

			echo $wire;
		}

		function argep(){
			header('Content-type: text/xml');
			// Árgép termékek
			$this->Products = new Products(array( 'db' => $this->db ));
			$termekek = $this->Products->prepareList( array(
				'except' => array(
					'argep' => 0
				)
			))->getList();

			$osszeghatar = 0;
			$szallitas = $this->db->query("SELECT osszeghatar, koltseg FROM `shop_szallitasi_mod` where osszeghatar != 0 ORDER BY osszeghatar ASC LIMIT 0,1;")->fetch(PDO::FETCH_ASSOC);

			if ( $szallitas['osszeghatar'] != '' || $szallitas['osszeghatar'] > 0 ) {
				$osszeghatar = (int)$szallitas['osszeghatar'];
			}
				/*echo '<pre>';
				print_r($termekek);
				echo '</pre>';*/

			$wire = '<?xml version="1.0" encoding="UTF-8" ?>';
			$wire .= '<termeklista>';
				foreach($termekek as $t):
					$leiras = 'Szín: '.($t['szin'] ? $t['szin'] : '-').'; '.'Méret: '.($t['meret'] ? $t['meret']:'-').'; '.($t['rovid_leiras'] ? $t['rovid_leiras'].'; ' : '');
					if ( count( $t['hasonlo_termek_ids']['colors']) > 1 )  {
						$leiras .= " További ".count( $t['hasonlo_termek_ids']['colors']).' db színvariáció elérhető!';
					}
					$szall 	= ( $osszeghatar == 0 || ( $osszeghatar > 0 && $t[ar] > $osszeghatar ) ) ? 'ingyenes' : $szallitas['koltseg'];

					$wire .= '<termek>';
						$wire .= '<cikkszam><![CDATA[ '.$t[cikkszam].' ]]></cikkszam>';
						$wire .= '<nev><![CDATA[ '.$t[product_nev].' ]]></nev>';
						$wire .= '<leiras><![CDATA[ '.$leiras.' ]]></leiras>';
						$wire .= '<ar>'.$t[ar].'</ar>';
						$wire .= '<fotolink><![CDATA[ '.$t[profil_kep].' ]]></fotolink>';
						$wire .= '<termeklink><![CDATA[ '.$this->view->settings['domain'].'/termek/'.\PortalManager\Formater::makeSafeUrl($t[product_nev],'_-'.$t[product_id]).' ]]></termeklink>';
						$wire .= '<szallitas>'.$szall.'</szallitas>';
					$wire .= '</termek>';
				endforeach;
			$wire .= '</termeklista>';

			echo $wire;

		}

		function templates()
		{
			$type = $this->view->gets[2];
			$this->view->render(__CLASS__.'/'.__FUNCTION__.'/'.$type, true);
		}

		function __destruct(){
			// RENDER OUTPUT
				//parent::bodyHead();					# HEADER
				//$this->view->render(__CLASS__);		# CONTENT
				//parent::__destruct();				# FOOTER
		}
	}

?>
