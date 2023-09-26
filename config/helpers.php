<?php

/**
 *
 * Arquivo onde são definidos os helpers
 *
 * @author Cristina Stanck
 *
 **/

define('LOCAL_URL', '/CandG_WEB/');

return array(
	'URLHelper'			=> new URLHelper(),
	'AdmSession'		=> new AdmSession,
	'UserSession'		=> new UserSession,
	'DateConverter'		=> new DateConverter,
	'PhoneHelper'		=> new PhoneHelper,
);
