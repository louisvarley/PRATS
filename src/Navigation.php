<?php

use \App\Services\FilterService as Filter;

$this->page_data['nav'] = [
		'rats' => ['title' => 'Rats', 'link' => '#', 'subitems' => [
			['type' => 'item', 'title' => 'New Rat', 'link' => '/rat/new'],
			['type' => 'item', 'title' => 'All Rats', 'link' => '/rat/list?orderby=id&&order=desc'],	
			['type' => 'divider'],	
			['type' => 'item', 'title' => 'My Rats', 'link' => '/rat/list?orderby=id&&order=desc'],			
		]],
		'litters' => ['title' => 'Litters', 'link' => '#', 'subitems' => [
			['type' => 'item', 'title' => 'New Litter', 'link' => '/litter/new'],
			['type' => 'item', 'title' => 'All Litters', 'link' => '/litter/list?orderby=id&order=desc'],	
			['type' => 'divider'],				
			['type' => 'item', 'title' => 'My Litters', 'link' => '/litter/list?orderby=id&order=desc&search=%22for%20sale%22'],
			
		]],
		'applications' => ['title' => 'Applications', 'link' => '#', 'subitems' => [
			['type' => 'item', 'title' => 'New Applications', 'link' => '/applications/new'],
			['type' => 'item', 'title' => 'All Applications', 'link' => '/applications/list?orderby=id&order=desc'],	
			['type' => 'divider'],				
			['type' => 'item', 'title' => 'My Applications', 'link' => '/applications/list?orderby=id&order=desc&search=%22for%20sale%22'],
			
		]],								
		'configuration' => ['title' => 'Configuration', 'link' => '#', 'subitems' => [
			['type' => 'item', 'title' => 'User Management', 'link' => '/user/list'],	
			['type' => 'divider'],				
			['type' => 'item', 'title' => 'Update', 'link' => '/update'],
			['type' => 'item', 'title' => 'Settings', 'link' => '/settings/edit'],				
		]],					
		'help' => ['title' => 'Help', 'link' => '#', 'subitems' => [
			['type' => 'item', 'title' => 'Wiki', 'link' => 'https://github.com/louisvarley/prats/wiki'],			
		]],	

	];
	
	
	$this->page_data['nav'] = Filter::action("nav_menu", $this->page_data['nav']);
		