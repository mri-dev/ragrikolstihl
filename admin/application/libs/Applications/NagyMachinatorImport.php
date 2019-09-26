<?php
namespace Applications;

class NagyMachinatorImport
{
  const SERVERHOST = '188.6.165.137:80';
  const SERVER_USER = 'WebServer';
  const SERVER_PW = 'WEBSERVER';

  private $curl = null;
  private $listname = '';
  private $output_format = 'HTML';
  public $urlparams = array();
  private $db = null;
  private $errors = array();

  public function __construct( $arg = array() )
  {
    set_time_limit ( 120 );
    $this->db = $arg[db];

    return $this;
  }

  public function parseCSVData( $csvstring )
  {
    $items = array();

    $csv = str_getcsv($csvstring);

    foreach ($csv as $s) {
      //$items[] = explode(";", $s);
      $items[] = $s;
    }

    return $items;
  }

  public function setURLParam( $key, $val )
  {
    $this->urlparams[trim($key)] = trim($val);
  }
  public function getURLParams()
  {
    $param = '';
    foreach ((array)$this->urlparams as $gk => $gv) {
      $param .= '&'.URLEncode ( $gk ).'='.URLEncode ( $gv );
    }
    return $param;
  }
  public function setListName( $name )
  {
    $this->listname = URLEncode( trim($name) );
    return $this;
  }

  public function getListName()
  {
    return $this->listname;
  }

  public function outputFormat( $set = false )
  {
    if ($set) {
      $this->output_format = $set;
      return $this->output_format;
    } else {
      return $this->output_format;
    }
  }

  public function requestURI()
  {
    if ($this->getListName() == '') {
      $this->errors[__FUNCTION__][] = 'Hiányzik a Listanév paraméter!';
      return false;
    }
    $url = 'http://'.self::SERVERHOST.'/LGQUERY?PrnForma='.$this->outputFormat().'&'.URLEncode ( 'Listanév' ).'='.$this->getListName().$this->getURLParams();
    return $url;
  }

  public function connect()
  {
    if ( !$this->requestURI() ) {
      throw new \Exception("A lekérdezés URL nem elérhető vagy hiányzik egy szükséges paraméter:<br>".$this->getErrors('requestURI'));
    }

    $this->curl = curl_init();
    curl_setopt($this->curl, CURLOPT_URL, $this->requestURI());
    curl_setopt($this->curl, CURLOPT_USERPWD, self::SERVER_USER.':'.self::SERVER_PW );
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($this->curl);
    curl_close($this->curl);

    return $response;
  }

  public function getErrors( $group )
  {
    $errors = '';
    if ( !empty($this->errors[$group]) ) {
      foreach ((array)$this->errors[$group] as $err) {
        $errors .= "- ".$err."<br>";
      }
    }
    return $errors;
  }


	function convert($size)
	{
	    $unit=array('b','kb','mb','gb','tb','pb');
	    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}

  public function __destruct()
  {
    $this->db = null;
  }
}
?>
