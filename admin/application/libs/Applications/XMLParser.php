<?php
namespace Applications;

class XMLParser
{
	private $result = null;

	public function __construct( $xml_file)
	{
		$header = get_headers( $xml_file, 1 );

		if ( substr( $header[0], 9, 3 ) != '200' ) {
			return false;
		}

		$this->result = simplexml_load_file( $xml_file, 'SimpleXMLElement', \LIBXML_NOCDATA );
	}

	public function getResult( $deep = false )
	{
		return $this->result;
	}
}
?>
