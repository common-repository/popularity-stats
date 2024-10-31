<?php


// Code based on http://phpredefine.wordpress.com/2007/04/28/alexa/




function horshipsrectors_popularity_getAlexaPage( $url ) {
	$googlehost='data.alexa.com';
	$googleua='Mozilla/5.0 ( Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6 ) Gecko/20060728 Firefox/1.5';

		$ch = horshipsrectors_popularity_getch( $url );
		$fp = fsockopen( $googlehost, 80, $errno, $errstr, 30 );

		if ( $fp ) {
		$out = "GET $url\r\n";
		$out .= "User-Agent: $googleua\r\n";
		$out .= "Host: $googlehost\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite( $fp, $out );


		while ( !feof( $fp ) ) {
			$data = fgets( $fp, 128 );
			$xml .= $data;
		}
		return $xml;

		fclose( $fp );
		}
}


function horshipsrectors_popularity_getAlexaRank( $data ) {
	preg_match( '#<POPULARITY URL="( .*? )" TEXT="( [0-9]+ ){1,}"/>#si', $data, $p );
	$value = ( $p[2] ) ? number_format( $p[2] ) : 0;
	return $value;
}


function horshipsrectors_popularity_getAlexaLinks( $data ) {
	$rankdata = explode( '<LINKSIN NUM="',$data );
	$rankdata = explode( '"',$rankdata[1] );
	return trim( $rankdata[0] );
}



?>