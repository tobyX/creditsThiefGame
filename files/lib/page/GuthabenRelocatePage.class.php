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

class GuthabenRelocatePage extends AbstractPage
{
	public $templateName = 'guthabenRelocate';
	
	public $neededPermissions = 'guthaben.thief.canuse';
	
	public $user;
	public $userID;
	public $username;

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters()
	{
		parent :: readParameters();
		
		if (!empty($_REQUEST['userID']))
			$this->userID = intval($_REQUEST['userID']);
		else
			throw new IllegalLinkException();
	}

	/**
	 * @see Page::readData()
	 */
	public function readData()
	{
		parent :: readData();
		
		$this->user = new User($this->userID);
		
		if (!$this->user->userID)
			throw new IllegalLinkException();
		
		$this->userID = $this->user->userID;
		$this->username = $this->user->username;
	}

	/**
	 * give thief guthaben and remove guthaben from prey (if possible)
	 * update counters
	 */
	private function exchange()
	{
		$this->user->getEditor()->updateOptions(array (
			'ambush' => ($this->user->ambush + 1)
		));
		
		if (WCF :: getUser()->userID)
		{
			Guthaben :: add(GUTHABEN_EARN_PER_AMBUSH, 'wcf.guthaben.log.ambushuser', WCF :: getUser()->username, '', $this->user);
			Guthaben :: sub(GUTHABEN_EARN_PER_AMBUSH, 'wcf.guthaben.log.ambushedbyuser', $this->username, '', WCF :: getUser(), true);
			
			WCF :: getUser()->getEditor()->updateOptions(array (
				'ambushed' => ($this->user->ambushed + 1)
			));
		}
		else
		{
			Guthaben :: add(GUTHABEN_EARN_PER_AMBUSH, 'wcf.guthaben.log.ambushguest', '', '', $this->user);
		}
	}

	/**
	 * validate if this ambush is allowed
	 */
	private function validate()
	{
		// check if this user has already robbed someone in the given timeframe
		$sql = "SELECT 	COUNT(*) AS c
				FROM 	wcf" . WCF_N . "_guthaben_thieflog
				WHERE 	userID = " . $this->userID . " AND
						thiefDate > " . (TIME_NOW - GUTHABEN_AMBUSHES_PER_TIME * 60);
		
		$timecheck1 = WCF :: getDB()->getFirstRow($sql);
		
		if ($timecheck1['c'] != 0)
			throw new IllegalLinkException();
			
		// check if this IP or prey was already robbed in the given timeframe
		$sql = "SELECT 	COUNT(*) AS c
				FROM 	wcf" . WCF_N . "_guthaben_thieflog
				WHERE 	userID = " . $this->userID . " AND
						(ipAddress LIKE '" . escapeString(WCF :: getSession()->ipAddress) . "' 
						 OR preyID = " . (WCF :: getUser()->userID ? WCF :: getUser()->userID : -1) . ") AND
						thiefDate > " . (TIME_NOW - GUTHABEN_AMBUSHES_PER_PREY * 60);
		
		$timecheck2 = WCF :: getDB()->getFirstRow($sql);
		
		if ($timecheck2['c'] != 0)
			throw new IllegalLinkException();
	}

	/**
	 * add entry to thieflog
	 */
	private function addLogEntry()
	{
		//add this user to log
		$sql = "INSERT INTO wcf" . WCF_N . "_guthaben_thieflog 
				(userID, preyID, thiefDate, ipAddress)
				VALUES (" . $this->userID . ", " . (WCF :: getUser()->userID ? WCF :: getUser()->userID : 0) . ", " . TIME_NOW . ", '" . escapeString(WCF :: getSession()->ipAddress) . "')";
		WCF :: getDB()->registerShutdownUpdate($sql);
		
		//cleanup table
		$sql = "DELETE FROM wcf" . WCF_N . "_guthaben_thieflog
				WHERE thiefDate < " . (TIME_NOW - (GUTHABEN_AMBUSHES_PER_PREY + GUTHABEN_AMBUSHES_PER_TIME) * 60);
		WCF :: getDB()->registerShutdownUpdate($sql);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables()
	{
		parent :: assignVariables();
		
		WCF :: getTPL()->assign(array (
			'username' => $this->username, 
			'wait' => 30, 
			'url' => 'index.php?page=User&userID=' . $this->userID . SID_ARG_2ND_NOT_ENCODED, 
			'ambushType' => (WCF :: getUser()->userID ? 'user' : 'guest'), 
			'loot' => Guthaben :: format(GUTHABEN_EARN_PER_AMBUSH)
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show()
	{
		// set active header menu item
		PageMenu :: setActiveMenuItem('wcf.header.menu.guthabenmain');
		
		// check permissions
		WCF :: getUser()->checkPermission($this->neededPermissions);
		
		// read data
		$this->readData();
		
		// call show event
		EventHandler :: fireAction($this, 'show');
		
		// validate if this ambush is allowed
		$this->validate();
		
		// add entry to thieflog
		$this->addLogEntry();
		
		// transfer guthaben to user (and remove guthaben from other user)
		$this->exchange();
		
		// assign variables
		$this->assignVariables();
		
		// show template
		WCF :: getTPL()->display($this->templateName);
	}
}

?>