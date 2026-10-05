<?php

/*
    Asatru PHP - Example controller

    Add here all your needed routes implementations related to 'index'.
*/

/**
 * Example index controller
 */
class IndexController extends BaseController {
	const INDEX_LAYOUT = 'layout';
	const MAX_BACKGROUNDS = 10;

	/**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);
	}

	/**
	 * Handles URL: /
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		$username = $request->params()->query('username', '');
		$userteam = $request->params()->query('team', '');

		if ((!empty($username)) && (!Chat::isNameAvailable($username))) {
			$username = $username . '-' . substr(md5(random_bytes(55) . date('Y-m-d H:i:s')), 0, 10);
		}

		return parent::view(['content', 'index'], [
			'max_backgrounds' => self::MAX_BACKGROUNDS,
			'username' => $username,
			'userteam' => $userteam
		]);
	}
}
