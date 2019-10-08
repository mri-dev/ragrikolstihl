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

  public function pushedProductKeszletSzallitasID( $originid = 0, $keszlet = 0 )
  {
    // készlet - szállítás
    switch ($originid) {
      default:
        if ((int)$keszlet <=0 ) {
          return array(1, 9);
        } else {
          return array(2, 8);
        }
      break;
    }

    return false;
  }

  public function importToTermekek()
  {
    $originid = 1;
    $q = "SELECT
      tp.*
    FROM `xml_temp_products` as tp
    WHERE 1=1 and
    tp.origin_id = {$originid} and
    tp.ar1 IS NOT NULL and
    tp.termek_nev IS NOT NULL";

    if (true) {
      $q .= " and
      (SELECT COUNT(xs.ID) FROM xml_temp_products as xs WHERE xs.cikkszam = tp.cikkszam GROUP BY xs.cikkszam) = 1 and
      (SELECT count(ID) FROM shop_termekek WHERE xml_import_origin = {$originid} and xml_import_res_id = tp.ID ) = 0 and
      (SELECT count(ID) FROM shop_termekek WHERE nagyker_kod = tp.prod_id and xml_import_origin != {$originid}) = 0 and
      (SELECT count(ID) FROM shop_termekek WHERE nagyker_kod = tp.prod_id) = 0";
    }
    $data = $this->db->query($q);

    if ($data->rowCount() != 0)
    {
      $data = $data->fetchAll(\PDO::FETCH_ASSOC);

      $insert_header = array(
        'cikkszam',
        'nagyker_kod',
        'nev',
        'leiras',
        'keszletID',
        'szallitasID',
        'netto_ar',
        'brutto_ar',
        'xml_import_origin',
        'xml_import_res_id',
        'xml_import_done',
        'lathato',
        'garancia_honap',
        'raktar_keszlet',
        'mertekegyseg'
      );
      $insert_row = array();

      foreach ( (array)$data as $d )
      {
        list($keszlet_id, $szallitas_id) = $this->pushedProductKeszletSzallitasID($originid, (float)$d['termek_keszlet']);

        /*$irow = array(
          $d['prod_id'], $d['prod_id'], addslashes($d['termek_nev']), addslashes($d['termek_leiras']), $keszlet_id, $szallitas_id, $d['beszerzes_netto'], 0, 0, $originid, $d['ID'], 0, 0, 0, (int)$d['termek_keszlet']
        );*/

        $irow = array(
          addslashes($d['cikkszam']),
          addslashes($d['cikkszam']),
          addslashes($d['termek_nev']),
          addslashes($d['termek_leiras']),
          $keszlet_id,
          $szallitas_id,
          0,
          0,
          $originid,
          $d['ID'],
          1,
          1,
          0,
          (float)$d['termek_keszlet'],
          addslashes($d['mennyisegegyseg']),
        );

        $insert_row[] = $irow;
      }
      unset($data);
      unset($irow);
      //print_r($insert_header);
      //print_r($insert_row);
      //exit;

      if (!empty($insert_row)) {
        /* */

        $debug = $this->db->multi_insert(
          'shop_termekek',
          $insert_header,
          $insert_row,
          array(
            'debug' => false
          )
        );
        unset($insert_header);
        unset($insert_row);
        //echo $debug;
        /* */
      }

      //return count($insert_row);
    }

    // Updater
    //$this->autoUpdater( $originid );

    return true;
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
