<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine;

abstract class SleeperNotifier extends \Threaded{
	/** @var \Threaded */
	private $threadedSleeper;

	/** @var int */
	private $sleeperId;

	/** @var bool */
	private $notification = false;

	final public function attachSleeper(\Threaded $sleeper, int $id) : void{
		$this->threadedSleeper = $sleeper;
		$this->sleeperId = $id;
	}

	final public function getSleeperId() : int{
		return $this->sleeperId;
	}

	/**
	 * Subclasses should call this method to wake up the main server thread.
	 */
	final protected function notifyServer() : void{
		assert($this->threadedSleeper !== null);

		if(!$this->notification){
			$this->notification = true;

			$this->threadedSleeper->notify();
		}
	}

	final public function hasNotification() : bool{
		return $this->notification;
	}

	final public function clearNotification() : void{
		$this->notification = false;
	}

	/**
	 * Override this to perform actions when the notifier woke the server up from another thread.
	 *
	 * @param Server $server
	 */
	abstract public function onServerNotify(Server $server) : void;
}