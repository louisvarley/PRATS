<?php

use \App\Services\FilterService as Filter;

$this->page_data['nav'] = [

		'my-rats' => ['title' => 'My Rats', 'link' => '/rats', 'icon' => 'fa fa-hand-holding-heart'],
		'rats' => ['title' => 'Rats', 'link' => '/rats', 'icon' => 'fa fa-paw'],
		'litters' => ['title' => 'Litters', 'link' => '/litters', 'icon' => 'fa fa-baby-carriage'],		
		'applications' => ['title' => 'Applications', 'link' => '/applications', 'icon' => 'fa fa-file-signature'],		
		'people' => ['title' => 'People', 'link' => '/users/list', 'icon' => 'fa fa-users'],		
		'configuration' => ['title' => 'Configuration', 'icon' => 'fa fa-cog', 'link' => '/settings'],					
		'help' => ['title' => 'Help', 'link' => 'https://www.youtube.com/watch?v=77K8er9_yjo', 'icon' => 'fa fa-question-circle'],	

	];
	
	
	$this->page_data['nav'] = Filter::action("nav_menu", $this->page_data['nav']);
		