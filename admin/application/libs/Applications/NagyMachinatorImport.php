<?php
namespace Applications;

use Applications\XMLParser;

class NagyMachinatorImport
{
  const DB_TEMP_PRODUCTS = 'xml_temp_products';
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

  public function syncStock()
  {
    $xml = SOURCE . 'json/keszlet.xml';
    $originid = 1;

    /* */
    if ( $xml ) {
      $parser = new XMLParser( $xml );
      $parse = $parser->getResult();

      if ( $parse && $parse->Items ) {
        $insert_row = array();
        $insert_header = array('hashkey', 'origin_id', 'cikkszam', 'gyarto_kod','prod_id','termek_keszlet');
        $i = 0;

        // Reset keszlet
         $this->db->update(
           self::DB_TEMP_PRODUCTS,
           array(
             'termek_keszlet' => 0
           )
         );

        // Cikkszam
        // Mennyiseg
        foreach ($parse->Items->children() as $item ) {
          //$i++;
          //if( $i >= 10) break;
          if((string)$item->Cikkszam == '0' || (string)$item->Cikkszam == '') continue;
          $hashkey = md5($originid.'_'.(string)$item->Cikkszam);
          $keszlet = (float)$item->Mennyiseg;

          /*
          $netto_kisker = $item->AlapAr;
          $xar = explode('.', $netto_kisker);
          $afterp = end($xar);
          array_pop($xar);
          $netto_kisker = (float)implode('',  $xar).'.'.$afterp;
          */

          $insert_row[] = array(
            $hashkey,
            $originid,
            addslashes((string)$item->Cikkszam),
            addslashes((string)$item->Cikkszam),
            addslashes((string)$item->Cikkszam),
            $keszlet
          );
        }
        unset($parse);

        if (!empty($insert_row)) {
          $dbx = $this->db->multi_insert(
            self::DB_TEMP_PRODUCTS,
            $insert_header,
            $insert_row,
            array(
              'debug' => false,
              'duplicate_keys' => array( 'hashkey', 'origin_id', 'cikkszam','gyarto_kod','prod_id', 'termek_keszlet' )
            )
          );
          unset($insert_row);
        }
      }
    } else {
      die('Az XML file nem elérhető: '.$xml);
    }

    unset($xml);
    /* */
  }

  public function syncArticles()
  {
    $xml = SOURCE . 'json/articles.xml';
    $originid = 1;
    /* */
    if ( $xml ) {
      $parser = new XMLParser( $xml );
      $parse = $parser->getResult();

      if ( $parse && $parse->Items ) {
        // Reset IO
         $this->db->update(
           self::DB_TEMP_PRODUCTS,
           array(
             'io' => 0
           )
         );

        $insert_row = array();
        $insert_header = array('hashkey', 'origin_id', 'cikkszam', 'prod_id', 'gyarto_kod', 'last_updated', 'termek_nev', 'kisker_ar_netto', 'ar1', 'mennyisegegyseg', 'ean_code', 'io');
        $i = 0;
        // Cikkszam
        // CikkszamForm
        // Nev
        // Nev 2
        // Vonalkod
        // ALapAr
        // Mertekegyseg
        // Afakod
        // Tomeg
        foreach ($parse->Items->children() as $item ) {
          //$i++;
          //if( $i >= 10) break;
          if((string)$item->Cikkszam == '0') continue;
          $hashkey = md5($originid.'_'.(string)$item->Cikkszam);

          // Ár fixálás a helytelen formátum végett
          $netto_kisker = $item->AlapAr;
          $xar = explode('.', $netto_kisker);
          $afterp = end($xar);
          array_pop($xar);
          $netto_kisker = (float)implode('',  $xar).'.'.$afterp;

          $insert_row[] = array(
            $hashkey,
            $originid,
            addslashes((string)$item->Cikkszam),
            addslashes((string)$item->Cikkszam),
            addslashes((string)$item->Cikkszam),
            NOW,
            addslashes((string)$item->Nev),
            (float)$netto_kisker,
            ($netto_kisker * 1.27),
            addslashes((string)$item->Mertekegyseg),
            addslashes((string)$item->Vonalkod),
            1 );
        }
        unset($parse);

        if (!empty($insert_row)) {
          $dbx = $this->db->multi_insert(
            self::DB_TEMP_PRODUCTS,
            $insert_header,
            $insert_row,
            array(
              'debug' => false,
              'duplicate_keys' => array( 'hashkey', 'cikkszam', 'gyarto_kod', 'prod_id', 'last_updated', 'termek_nev', 'kisker_ar_netto', 'ar1', 'mennyisegegyseg', 'ean_code', 'io' )
            )
          );
          unset($insert_row);
        }
      }
    } else {
      die('Az XML file nem elérhető: '.$xml);
    }

    unset($xml);
    /* */
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
