<?
use ShopManager\Category;
use ShopManager\Categories;
use ProductManager\Products;
use PortalManager\Template;
use PortalManager\Pagination;

class termekek extends Controller {
		function __construct(){
			parent::__construct();
			$title = 'Termékek';
			$myfavorite = false;

			// Kedvenc termékeim
			if ( isset($_GET['myfavorite']) && $_GET['myfavorite'] == '1' )
			{
				$myfavorite = true;
				$title = 'Kedvenc termékeim';
				$this->out( 'myfavorite', $myfavorite );
			}

			// Template
			$temp = new Template( VIEW . 'templates/' );
			$this->out( 'template', $temp );

			// Kategória adatok
			$cat = new Category(Product::getTermekIDFromUrl(), array( 'db' => $this->db ));
			$this->out( 'category', $cat );


			// Kategória szülő almenüi
			$categories = new Categories( array( 'db' => $this->db ) );

			$parent_id = $cat->getId();
			$parent_list = $categories->getChildCategories( $parent_id );
			$this->out( 'parent_menu', $parent_list );

			if ($parent_list) {
				$parent_row = array_reverse($categories->getCategoryParentRow( $cat->getId(), 'neve'));
				$this->out( 'parent_row', $parent_row );
			}

			// Kategória nav
			if( $parent_id )
			{
				$this->out( 'cat_nav', array_reverse($categories->getCategoryParentRow( $parent_id, false )) );
			}

			if (  $cat->getId() ) {
				// Szülők
				$parent_set = array_reverse( $categories->getCategoryParentRow( $cat->getId(), 'neve', 0 ) );

				$i = 0;
				foreach( $parent_set as $parent_i ) {
					$i++;

					$after = '';

					if( $i == 1 ) {
						$after = '';
					} else if( $i == 2 )  {
						$after = ' kategória';
					}

					$title = $parent_i.$after. ' | '.$title;
				}
			}

			/****
			* Termékek
			*****/

			$filters = array();
			$paramfilters = array();
			$order = array();

			$paramfilters = \Helper::getFilters($_GET,'fil');

			if( $_GET['order']) {
				$xord = explode("_",$_GET['order']);
				$order['by'] 	= $xord[0];
				$order['how'] 	= $xord[1];
			}

			$arg = array(
				'filters' 	=> $filters,
				'paramfilters' 	=> $paramfilters,
				'in_cat' 	=> $cat->getId(),
				'meret' 	=> $_GET['meret'],
				'order' 	=> $order,
				'limit' 	=> 30,
				'page' 		=> Helper::currentPageNum(),
				'favorite' => $myfavorite,
				'archivalt' => 0
			);

			if (!empty($_GET['src'])) {
				$arg['search'] = explode(" ",trim($_GET['src']));
			}

			$products = (new Products( array(
				'db' => $this->db,
				'user' => $this->User->get()
			) ))->prepareList( $arg );

			$this->out( 'products', $products );
			$this->out( 'product_list', $products->getList() );
			$this->out( 'productFilters', $products->productFilters( (array)$products->getLoadedIDS() ) );
			$this->out( 'filters', $products->getFilters($_GET,'fil'));
			$get = $_GET;
			unset($get['tag']);
			$get = http_build_query($get);
			$this->out( 'cget', $get );
			$this->out( 'navigator', (new Pagination(array(
				'class' => 'pagination pagination-sm center',
				'current' => $products->getCurrentPage(),
				'max' => $products->getMaxPage(),
				'root' => '/'.__CLASS__.'/'.$this->view->gets[1].($this->view->gets[2] ? '/'.$this->view->gets[2] : '/-'),
				'after' => ( $get ) ? '?'.$get : '',
				'item_limit' => 12
			)))->render() );
			$this->out( 'slideshow', 	$this->Portal->getSlideshow( $this->view->category->getName() ) );

			// Log AV
			/* */
			$this->shop->logKategoriaView(
				Product::getTermekIDFromUrl()
			);
			/* */

			$this->out( 'product_listing', true );

			// SEO Információk
			$SEO = null;
			// Site info
			$SEO .= $this->view->addMeta('description', 'Minőségi '.strtolower($this->view->category->getName()) . ' a Casada Hungary Kft.-től. Őrizze meg egészségét!');
			$SEO .= $this->view->addMeta('keywords',$this->view->category->getName());
			$SEO .= $this->view->addMeta('revisit-after','3 days');

			// FB info
			$SEO .= $this->view->addOG('type','product.group');
			$SEO .= $this->view->addOG('url',substr(DOMAIN,0,-1).$_SERVER['REQUEST_URI']);
			$SEO .= $this->view->addOG('image',$this->view->settings['domain'].'/admin'.$this->view->category->getImage());
			$SEO .= $this->view->addOG('site_name', $this->view->settings['page_title']);

			$this->view->SEOSERVICE = $SEO;

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
