<?
use PortalManager\Portal;
use PortalManager\Pagination;
use Applications\XMLGenerator;
use Applications\CSVGenerator;

class feliratkozok extends Controller{
		function __construct(){
			parent::__construct();
			parent::$pageTitle = 'Feliratkozók / Adminisztráció';

			$this->exporting = false;

			$this->view->adm = $this->AdminUser;
			$this->view->adm->logged = $this->AdminUser->isLogged();

			$portal = new Portal( array( 'db' => $this->db ) );
			$this->Portal = $portal;


			if(Post::on('filterList')){
				$filtered = false;

				if($_POST['ID'] != ''){
					setcookie('filter_ID',$_POST['ID'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['email'] != ''){
					setcookie('filter_email',$_POST['email'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_email','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['phone'] != ''){
					setcookie('filter_phone',$_POST['phone'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_phone','',time()-100,'/'.$this->view->gets[0]);
				}


				if($_POST['hely'] != ''){
					setcookie('filter_hely',$_POST['hely'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_hely','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['nev'] != ''){
					setcookie('filter_nev',$_POST['nev'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
				}

				if($_POST['leiratkozott'] != ''){
					setcookie('filter_leiratkozott',$_POST['leiratkozott'],time()+60*24,'/'.$this->view->gets[0]);
					$filtered = true;
				}else{
					setcookie('filter_leiratkozott','',time()-100,'/'.$this->view->gets[0]);
				}

				if($filtered){
					setcookie('filtered','1',time()+60*24*7,'/'.$this->view->gets[0]);
				}else{
					setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
				}

				Helper::reload();
			}

			// Feliratkozó törlése
			if ( Post::on('delId') ) {
				$this->db->query( "DELETE FROM feliratkozok WHERE ID = ".$_POST['delId'] );
				Helper::reload('/feliratkozok/');
			}


			// Feliratkozók listája
		 	$filters = Helper::getCookieFilter('filter',array('filtered'));
			$arg = array(
				'limit' => 50,
				'filters' => $filters,
				'page' => ($this->view->gets[1] == 'del' ? 1 : $this->view->gets[2])
			);
			$this->out( 'feliratkozok', $portal->getFeliratkozok( $arg ) );
			$this->out( 'navigator', (new Pagination(array(
				'class' => 'pagination pagination-sm center',
				'current' => $this->view->feliratkozok['info']['pages']['current'],
				'max' => $this->view->feliratkozok['info']['pages']['max'],
				'root' => '/'.__CLASS__.'/-',
				'item_limit' => 18
			)))->render() );

			// Exportálás
			if( Post::on('export') ) {
				$subs = array();

				$arg = array(
					'limit' => 99999999
				);
				$subs_list = $portal->getFeliratkozok( $arg );

				foreach ( $subs_list ['data'] as $s ) {
					if ( $s['leiratkozott'] == '0' ) {
						$subs[] = array( $s['nev'], $s['email'], $s['phone'], $s['hely'] );
					}
				}

				$this->exporting = true;

				switch ( $_POST['export'] ) {
					case 'subs_xml':
						$xml = new XMLGenerator( 'subscribers', 'user', array( 'name', 'email', 'phone', 'signed_here' ), $subs );
						$xml->build();
						$xml->export( $this->view->settings['page_title'].' - Feliratkozók XML Export - '.NOW );
					break;
					case 'subs_csv':
						Applications\CSVGenerator::prepare( array('name', 'email', 'phone', 'signed_here' ), $subs, $this->view->settings['page_title'].' - Feliratkozók CSV Export - '.NOW );
						Applications\CSVGenerator::run();
					break;
					case 'data_json':
						$filename = $this->view->settings['page_title'].' - Feliratkozók adatbázis JSON Export - '.NOW.'.json';
						header("Content-type: text/json; charset=UTF-8");
						header('Content-Disposition: attachment; filename="'.$filename.'"');
						header("Content-Transfer-Encoding: binary");
						header("Pragma: no-cache");
						header("Expires: 0");

						echo json_encode($subs_list['data']);
					break;
					default:

					break;
				}

				unset($subs);
			}


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

		public function import()
		{

			$file = $_FILES['import'];
			$error = false;

			if ( $file['type'] != 'application/octet-stream') {
				$error = true;
				$this->out( 'msg', Helper::makeAlertMsg('pError', 'SIKERTELEN IMPORTÁLÁS: Nem megfelelő fájlt választott ki. Kérjük, hogy a korábban exportált JSON típusú fájlt válassza ki!') );
			}

			if ( !$error ) {
				$data = json_decode(file_get_contents( $file['tmp_name'] ), JSON_UNESCAPED_UNICODE);
				$ins = 0;

				if( is_array($data) && !empty($data)) {
					foreach ( $data as $imd ) {
						if ( !$this->Portal->feliratkozva( $imd['email'] ) ) {
							unset($imd['ID']);
							$ins++;
							$this->Portal->insert(
								"feliratkozok",
								$imd
							);
						}
					}
				Helper::reload('/feliratkozok/?msgkey=msg&msg=SIKERES IMPORTÁLÁS: '.$ins.' db elemet rögzítettünk az adatbázisba.');
				}

				Helper::reload('/feliratkozok/');
			}
		}

		function clearfilters(){
			setcookie('filter_ID','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_nev','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_email','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_phone','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filterhely','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filter_leiratkozott','',time()-100,'/'.$this->view->gets[0]);
			setcookie('filtered','',time()-100,'/'.$this->view->gets[0]);
			Helper::reload('/feliratkozok/');
		}

		function __destruct(){
			// RENDER OUTPUT
				if ( !$this->exporting ) {
					parent::bodyHead();					# HEADER
					$this->view->render(__CLASS__);		# CONTENT
					parent::__destruct();				# FOOTER
				}
		}
	}

?>
