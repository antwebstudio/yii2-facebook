<?php

return [
    'id' => 'facebook',
    'class' => \ant\facebook\Module::className(),
    'isCoreModule' => false,
	'modules' => [
		'v1' => \ant\facebook\api\v1\Module::class,
		'backend' => \ant\facebook\backend\Module::class,
	],
];