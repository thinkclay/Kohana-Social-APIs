<?php

/**
* Kohana-Facebook
*
* @package        Kohana_Facebook
* @author         Jeremy Bush
* @copyright      (c) 2010 Jeremy Bush
* @license        http://www.opensource.org/licenses/isc-license.txt
*/
class Kohana_Facebook
{
    protected static $_instance;

    protected $_facebook;

    protected $_session;

    protected $_me;
    
    protected function __construct()
    {
    	
    	if (file_exists(MODPATH.'/social/vendor/facebook/src/facebook.php'))
        	include MODPATH.'/social/vendor/facebook/src/facebook.php';
		else
			throw new Exception("Facebook Module was not found. Did you forget to init submodule?");

        // Do class setup
        $this->_facebook = new Facebook(
            array(
                'appId'  => Kohana::$config->load('facebook')->app_id,
                'secret' => Kohana::$config->load('facebook')->secret,
                'cookie' => true, // enable optional cookie support
            )
        );

        $this->_session = $this->_facebook->getUser();

        try
        {
            $this->_me = $this->_facebook->api('/me');
        }
        catch (FacebookApiException $e)
        {
            // Do nothing.
        }
    }

    public static function instance()
    {
        if ( ! isset(self::$_instance))
            Kohana_Facebook::$_instance = new Kohana_Facebook;

        return Kohana_Facebook::$_instance;
    }
	
	
    public function app_id()
    {
        return $this->_facebook->getAppId();
    }

    public function logged_in()
    {
        return $this->_me != NULL;
    }

    public function user_id()
    {
        return $this->_facebook->getUser();
    }

    public function session()
    {
        return $this->_session;
    }

    public function account()
    {
        return $this->_me;
    }

    public function facebook()
    {
        return $this->_facebook;
    }
}