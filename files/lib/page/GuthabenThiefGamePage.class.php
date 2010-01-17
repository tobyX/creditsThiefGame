<?php
/*
 * +-----------------------------------------+
 * | Copyright (c) 2010 Tobias Friebel       |
 * +-----------------------------------------+
 * | Authors: Tobias Friebel <TobyF@Web.de>	 |
 * +-----------------------------------------+
 * 
 * CC Namensnennung-Keine kommerzielle Nutzung-Keine Bearbeitung
 * http://creativecommons.org/licenses/by-nc-nd/2.0/de/
 * 
 * $Id$
 */

require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');

class GuthabenThiefGamePage extends AbstractPage
{
	public $templateName = 'guthabenThiefGame';
	
	public $neededPermissions = 'guthaben.thief.canuse';

	/**
	 * @see Page::show()
	 */
	public function show()
	{
		// set active header menu item
		PageMenu :: setActiveMenuItem('wcf.header.menu.guthabenmain');
		
		parent :: show();
	}
}

?>