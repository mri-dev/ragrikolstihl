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
    $this->autoUpdater( $originid );

    return true;
  }

  public function autoUpdater( $originid = 0, $debug = false )
  {
    $update = array();

    $q = "SELECT
      tp.*
    FROM `xml_temp_products` as tp
    WHERE 1=1 and
    tp.origin_id = {$originid}";

    if (true) {
      $q .= " and
      (SELECT count(ID) FROM shop_termekek WHERE xml_import_origin = {$originid} and xml_import_res_id = tp.ID ) != 0";
    }

    $data = $this->db->query($q);

    if ($data->rowCount() != 0)
    {
      $data = $data->fetchAll(\PDO::FETCH_ASSOC);
      $upsetbench = array();

      $i = 0;
      foreach ((array)$data as $d)
      {
        //echo $d['prod_id'] . '<br>';
        //if($d['prod_id'] != '10000250') continue;
        //$i++; if ($i >= 100) { break; }

        $current_data = $this->db->query("SELECT
          ID,
          nev,
          keszletID,
          szallitasID,
          raktar_keszlet,
          mertekegyseg
        FROM shop_termekek
        WHERE 1=1 and
        xml_import_origin = {$originid} and
        xml_import_res_id = {$d[ID]}")->fetch(\PDO::FETCH_ASSOC);
        //if($current_data['ID'] != 2421) continue;

        $d['will_update'] = $this->whatWantUpdate($originid, $d, $current_data);
        $d['current_data'] = $current_data;

        $update[] = $d;
      }

      foreach ((array)$update as $up) {
        if(empty($up['will_update'])) continue;

        $upset = '';

        foreach ((array)$up['will_update']['what'] as $upkey) {
          if (is_null($up['will_update']['field'][$upkey]['new'])) {
            $upset .= $upkey." = NULL, ";
          } else {
            $v = $this->db->db->quote($up['will_update']['field'][$upkey]['new']);
            $upset .= $upkey." = ".$v.", ";
          }
        }

        if ($upset != '') {
          $upset = rtrim($upset, ', ');
          $upsetbench[$upset][] = $up['current_data']['ID'];
        }
      }

      $xcutr = 0;
      foreach ((array)$upsetbench as $whr => $ids) {
        $upq = "UPDATE shop_termekek SET $whr WHERE xml_import_origin = {$originid} and ID IN (".implode(",", $ids).")";

        $this->db->query($upq);
        //echo $upq."<br>";
      }

      unset($upsetbench);

      //print_r($upsetbench);
    }
    $this->autoIOProducts( $originid );

    if ($debug) {
      return $update;
    } else {
      unset($update);
      unset($data);
      return true;
    }
  }

  public function whatWantUpdate( $originid = 0, $tempdata = array(), $current_data = array() )
  {
    $wupdate = array();

    list($keszletID, $szallitasID) = $this->pushedProductKeszletSzallitasID($originid, $tempdata['termek_keszlet']);

    /* * /
    if ($current_data['netto_ar'] != $netto_ar && $current_data['akcios'] == 0) {
      $wupdate['what'][] = 'netto_ar';
      $wupdate['field']['netto_ar'] = array(
        'new' => $netto_ar,
        'old' => $current_data['netto_ar']
      );
    }
    /* */

    /* * /
    if ($current_data['netto_ar'] != $netto_ar && $current_data['akcios_netto_ar'] != $netto_ar && $current_data['akcios'] == 1) {
      $wupdate['what'][] = 'akcios_netto_ar';
      $wupdate['field']['akcios_netto_ar'] = array(
        'new' => $netto_ar,
        'old' => $current_data['akcios_netto_ar']
      );
    }
    /* */

    /* * /
    if ($current_data['brutto_ar'] != $brutto_ar && $current_data['akcios'] == 0) {
      $wupdate['what'][] = 'brutto_ar';
      $wupdate['field']['brutto_ar'] = array(
        'new' => $brutto_ar,
        'old' => $current_data['brutto_ar']
      );
    }
    /* */

    /* * /
    if ($current_data['brutto_ar'] != $brutto_ar && $current_data['akcios_brutto_ar'] != $brutto_ar && $current_data['akcios'] == 1) {
      $wupdate['what'][] = 'akcios_brutto_ar';
      $wupdate['field']['akcios_brutto_ar'] = array(
        'new' => $brutto_ar,
        'old' => $current_data['akcios_brutto_ar']
      );
    }
    /* */

    /* * /
    if ($egyedi_ar !== false && !empty($egyedi_ar) && $current_data['egyedi_ar'] != $egyedi_ar) {
      $wupdate['what'][] = 'egyedi_ar';
      $wupdate['field']['egyedi_ar'] = array(
        'new' => $egyedi_ar,
        'old' => $current_data['egyedi_ar']
      );
    }
    /* */

    if ($tempdata['termek_nev'] != $current_data['nev']) {
      $wupdate['what'][] = 'nev';
      $wupdate['field']['nev'] = array(
        'new' => $tempdata['termek_nev'],
        'old' => $current_data['nev']
      );
    }

    if ($tempdata['termek_keszlet'] != $current_data['raktar_keszlet']) {
      $wupdate['what'][] = 'raktar_keszlet';
      $wupdate['field']['raktar_keszlet'] = array(
        'new' => $tempdata['termek_keszlet'],
        'old' => $current_data['raktar_keszlet']
      );
    }

    if ($tempdata['mennyisegegyseg'] != $current_data['mertekegyseg']) {
      $wupdate['what'][] = 'mertekegyseg';
      $wupdate['field']['mertekegyseg'] = array(
        'new' => $tempdata['mennyisegegyseg'],
        'old' => $current_data['mertekegyseg']
      );
    }

    if ($keszletID != $current_data['keszletID']) {
      $wupdate['what'][] = 'keszletID';
      $wupdate['field']['keszletID'] = array(
        'new' => $keszletID,
        'old' => $current_data['keszletID']
      );
    }

    if ($szallitasID != $current_data['szallitasID']) {
      $wupdate['what'][] = 'szallitasID';
      $wupdate['field']['szallitasID'] = array(
        'new' => $szallitasID,
        'old' => $current_data['szallitasID']
      );
    }

    // Akciózás
    if ( false )
    {
      if ( isset($tempdata['nagyker_ar_netto_akcios']) && (float)$tempdata['nagyker_ar_netto_akcios'] != 0 )
      {
        $nagyker_netto_akcios = (float)$tempdata['nagyker_ar_netto_akcios'];
        $nagyker_brutto_akcios = $nagyker_netto_akcios * 1.27;
        $kisker_netto = (float)$tempdata['kisker_ar_netto'];
        $kisker_netto_akcios = (float)$tempdata['kisker_ar_netto_akcios'];
        $kisker_brutto_akcios = round($kisker_netto_akcios * 1.27);
        $kisker_brutto = round($kisker_netto * 1.27);

        // 0-5 kerekítés
        $nagyker_netto_akcios = round( $nagyker_netto_akcios / 5 ) * 5;
        $nagyker_brutto_akcios = round( $nagyker_brutto_akcios / 5 ) * 5;
        $kisker_netto = round( $kisker_netto / 5 ) * 5;
        $kisker_netto_akcios = round( $kisker_netto_akcios /  5) * 5;
        $kisker_brutto_akcios = round( $kisker_brutto_akcios /  5) * 5;
        $kisker_brutto = round( $kisker_brutto /  5) * 5;

        // Akciós állapot
        if( $current_data['akcios'] != 1 ) {
          $wupdate['what'][] = 'akcios';
          $wupdate['field']['akcios'] = array(
            'new' => 1,
            'old' => $current_data['akcios']
          );
        }

        // Egyedi ár
        if ( $current_data['egyedi_ar'] != $kisker_brutto_akcios) {
          $wupdate['what'][] = 'egyedi_ar';
          $wupdate['field']['egyedi_ar'] = array(
            'new' => $kisker_brutto_akcios,
            'old' => $current_data['egyedi_ar']
          );
        }

        // Akciós nettó
        if ( $current_data['akcios_netto_ar'] != $nagyker_netto_akcios ) {
          $wupdate['what'][] = 'akcios_netto_ar';
          $wupdate['field']['akcios_netto_ar'] = array(
            'new' => $nagyker_netto_akcios,
            'old' => $current_data['akcios_netto_ar']
          );
        }

        // Akciós bruttó
        if ( $current_data['akcios_brutto_ar'] != $nagyker_brutto_akcios ) {
          $wupdate['what'][] = 'akcios_brutto_ar';
          $wupdate['field']['akcios_brutto_ar'] = array(
            'new' => $nagyker_brutto_akcios,
            'old' => $current_data['akcios_brutto_ar']
          );
        }

        // Egyedi ár akciós bruttó
        if ( $current_data['akcios_egyedi_brutto_ar'] != $kisker_brutto ) {
          $wupdate['what'][] = 'akcios_egyedi_brutto_ar';
          $wupdate['field']['akcios_egyedi_brutto_ar'] = array(
            'new' => $kisker_brutto,
            'old' => $current_data['akcios_egyedi_brutto_ar']
          );
        }
      }

      // Akció levétele
      else if( isset($tempdata['nagyker_ar_netto_akcios']) && (float)$tempdata['nagyker_ar_netto_akcios'] == 0 && $current_data['akcios'] == 1 )
      {
        // Akciós állapot
        if( $current_data['akcios'] == 1 ) {
          $wupdate['what'][] = 'akcios';
          $wupdate['field']['akcios'] = array(
            'new' => 0,
            'old' => $current_data['akcios']
          );
        }

        // Egyedi ár
        if ( $current_data['egyedi_ar'] != 0) {
          $wupdate['what'][] = 'egyedi_ar';
          $wupdate['field']['egyedi_ar'] = array(
            'new' => $egyedi_ar,
            'old' => $current_data['egyedi_ar']
          );
        }

        // Akciós nettó
        if ( !is_null($current_data['akcios_netto_ar']) ) {
          $wupdate['what'][] = 'akcios_netto_ar';
          $wupdate['field']['akcios_netto_ar'] = array(
            'new' => null,
            'old' => $current_data['akcios_netto_ar']
          );
        }

        // Akciós bruttó
        if ( !is_null($current_data['akcios_brutto_ar']) ) {
          $wupdate['what'][] = 'akcios_brutto_ar';
          $wupdate['field']['akcios_brutto_ar'] = array(
            'new' => null,
            'old' => $current_data['akcios_brutto_ar']
          );
        }

        // Egyedi ár akciós bruttó
        if ( !is_null($current_data['akcios_egyedi_brutto_ar']) ) {
          $wupdate['what'][] = 'akcios_egyedi_brutto_ar';
          $wupdate['field']['akcios_egyedi_brutto_ar'] = array(
            'new' => null,
            'old' => $current_data['akcios_egyedi_brutto_ar']
          );
        }
      }
    }

    //print_r($tempdata);

    return $wupdate;
  }

  public function autoIOProducts( $orignid = 0 )
  {
    if ($orignid == 0) {
      return false;
    }

    $q = "SELECT
    ID, nagyker_kod, lathato
    FROM `shop_termekek`
    WHERE
      `xml_import_origin` = $orignid and
      xml_import_done = 1 and
      lathato = 1 and
      (SELECT t.io FROM xml_temp_products as t WHERE t.prod_id != '' and t.prod_id = nagyker_kod and t.origin_id = xml_import_origin) != lathato";

    $data = $this->db->query( $q );

    if ($data->rowCount() != 0) {
      $data = $data->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($data as $d) {
        $io = ($d['lathato'] == 1) ? 0 : 1;
        $u = "UPDATE shop_termekek SET lathato = $io WHERE xml_import_origin = $orignid and ID = ".$d['ID'];
        //echo $u."<br>";
        $this->db->query( $u );
      }
    }
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
