<?php

namespace ant\facebook;

/**
 * payment module definition class
 */
class Module extends \yii\base\Module
{
	public const EVENT_LOGGED_IN = 'loggedIn';
	
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
