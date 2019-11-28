<?
class feliratkozas extends Controller{
		function __construct(){
			parent::__construct();
			$title = ( $_GET['leave'] ) ? 'Leiratkozás' : 'Feliratkozás';


			// Leiratkozás
			if ( Post::on('unsubscribe') ) {
				try {
					$this->Portal->leiratkozas( $_POST['email'] );
					Helper::reload('/feliratkozas/?leave=1&msgkey=msg&msg=Sikeresen leiratkozott hírlevelünkről!');
				} catch (Exception $e) {
					$this->out( 'msg', Helper::makeAlertMsg( 'pError', $e->getMessage() ) );
				}
			}

			// Feliratkozás
			if ( Post::on('subscribe') ) {
				try {
					$this->Portal->feliratkozas(  $_POST['name'],  $_POST['email'], $_POST['phone'], 'feliratkozás', true );
					Helper::reload('/feliratkozas/?msgkey=msg&msg=Sikeresen feliratkozott hírlevelünkre!');
				} catch (Exception $e) {
					$this->out( 'msg', Helper::makeAlertMsg( 'pError', $e->getMessage() ) );
				}
			}


			parent::$pageTitle = $title;
		}

		function __destruct(){
			// RENDER OUTPUT
				parent::bodyHead();					# HEADER
				$this->view->render(__CLASS__);		# CONTENT
				parent::__destruct();				# FOOTER
		}
	}

?>
