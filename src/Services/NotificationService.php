<?php

namespace App\Services;

use App\Config;
use \App\Services\EntityService as Entities;

class NotificationService
{

	public static $notifications = [];

	public static function fetch(){
		

		return self::$notifications;
		
	}
	
	public static function addNotification($title, $link, $icon){
		
		self::$notifications[] = ['title' => $title, 'link' => $link, 'icon' => $icon];
	}
}

