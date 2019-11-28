<?
namespace Applications;

class Captcha {
	private static $public_key 	= false;
	private static $private_key = false;
	private static $libFile 	= 'recaptchalib';

	public static function init( $public_key, $private_key ) {
		self::$public_key = $public_key;
		self::$private_key = $private_key;
		return $this;
	}
	
	static function show(){
		echo '<div class="g-recaptcha" data-sitekey="'.self::$public_key.'"></div>';
	}

	static function verify(){
		/*self::build();
		
		$privatekey = self::$private_key;
		
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
		*/

		  if ( isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] == '' ) {
			return false;
		  } else if( !isset($_POST['g-recaptcha-response']) || $_POST['g-recaptcha-response'] != '' ) {
			return true;
		  }
	}
}
?>