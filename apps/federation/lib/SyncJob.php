<?php
/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\Federation;

use OC\BackgroundJob\TimedJob;
use OCA\Federation\AppInfo\Application;
use OCP\ILogger;

class SyncJob extends TimedJob {

	/** @var SyncFederationAddressBooks */
	protected $syncService;

	/** @var ILogger */
	protected $logger;

	/**
	 * @param SyncFederationAddressBooks $syncService
	 * @param ILogger $logger
	 */
	public function __construct(SyncFederationAddressBooks $syncService, ILogger $logger) {
		// Run once a day
		$this->setInterval(24 * 60 * 60);
		$this->syncService = $syncService;
		$this->logger = $logger;
	}

	protected function run($argument) {
		$this->syncService->syncThemAll(function($url, $ex) {
			if ($ex instanceof \Exception) {
				$this->logger->error("Error while syncing $url : " . $ex->getMessage(), ['app' => 'fed-sync']);
			}
		});
	}
}
