<?php 

return [
	"models" => [
		"users" => 'App\Models\User',
		"menu" =>  'App\Models\Menu',
		"roles" =>  'App\Models\Role',
		"permissions" =>  'App\Models\Permission',
	],
	"themes" => [
		[
			"version" => "v1",
			"name" => "AdminLTE Admin Template",
			"description" => "Powered of Bootstrap framework",
			"images" => "v1.png",
			"status" => true
		],
		[
			"version" => "v2",
			"name" => "BULKIT Admin Template",
			"description" => "Powered of Bulma framework",
			"images" => "v2.png",
			"status" => false
		],
	],
	"localization" => [
		"id_ID" => "id", 
		"en_EN" => "en", 
	],
	"first_class" => "superuser",
	"roles" => [
		"superuser" => [
			"menu" => [
				"dashboard",
				"setting",
				"application",
				"menu",
				"users",
				"access control",
				"roles",
				"access",
				"permissions",
				"master",
			],
			"permissions" => [
				"dashboard" => "i",
				"application" => "i,c,st",
				"users" => "i,c,sh,st,e,u,d",
				"menu" => "i,st,e,u",
				"roles" => "i,c,sh,st,e,u,d",
				"access" => "i,st",
				"permissions" => "i,st",
				"profile" => "i,st",
			]
		],
		// "other role" => []
	],
	"permissions" => [
		// "dashboard",
		// "application",
		// "menu",
		// "sales",
		// "profile",
	],
	"permissions_maps" => [
		"i" => "index",
		"c" => "create",
		"sh" => "show",
		"st" => "store",
		"e" => "edit",
		"u" => "update",
		"d" => "destroy",
	],
];