<?php

class AppSession {

	function save() {
		$_SESSION['session_obj'] = $this->data;
	}

	function load() {
		if ( isset($_SESSION['session_obj']) ) {
			$this->data = $_SESSION['session_obj'];
		} else {
			$this->AppSession();
		}
	}

	function check($params) {

		// check if login required
		if ( @$params['login_required'] ) {
			// check if logged_in
			if ( isset($_SESSION['session_obj']['user_id']) && $_SESSION['session_obj']['user_id'] > 0 ){
				AppSession::checkIdle();
			} else {
				// access is denied to non-logged in user
				header('Location: index.php?statpos=logout');
				exit;
			}
		}
		
		// check user type required
		if ( (@$params['require_usertype'])) { 
			if ( $params['require_usertype'] != AppSession::getUserItem('wise_membertype') )	{
				// access is denied to non-logged in user
				header('Location: index.php?statpos=logout');
				exit;
			}
		}
	}

	function create($params) {
		$_SESSION['session_obj']['timer'] = time();

		foreach( $params['newsession'] as $nkey => $nvalue ) {
			$_SESSION['session_obj'][$nkey] = $nvalue;
		}
		
	}

	function reload($params) {
		$_SESSION['session_obj']['timer'] = time();

		foreach( $params['newsession'] as $nkey => $nvalue ) {
			$_SESSION['session_obj'][$nkey] = $nvalue;
		}
		
	}

	function destroy() {
		unset($_SESSION['session_obj']);
	}

	function checkIdle() {
		if ( isset($_SESSION['session_obj']['timer'])) {
			if ( time() - $_SESSION['session_obj']['timer'] > APPCONFIG_IDLE_TIMEOUT ) {
				// forward to error page indicating timeout/expired
				AppSession::destroy();
				header('Location: index.php?statpos=logout&out=1');
				exit;
			}
		} else {
			if ( @$params['login_required'] ) {
				// timer is not set, access is denied
				AppSession::destroy();
				header('Location: index.php?statpos=logout');
				exit;
			}
		}
	}
	
	function getSessionItem($name) {
		return $_SESSION['session_obj'][$name];
	}
	
	function getUserItem($name) {
		return $_SESSION['session_obj']['user_data'][$name];
	}

	function start() {
		session_start();
	}

}



?>