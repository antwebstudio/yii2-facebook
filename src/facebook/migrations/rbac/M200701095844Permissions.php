<?php

namespace ant\facebook\migrations\rbac;

use yii\db\Schema;
use ant\rbac\Migration;
use ant\rbac\Role;

class M200701095844Permissions extends Migration
{
	protected $permissions;
	
	public function init() {
		$this->permissions = [
			\ant\facebook\controllers\JsController::class => [
				'logged-in' => ['View own file', [Role::ROLE_GUEST]],
				'publish-instant-article' => ['View own file', [Role::ROLE_GUEST]],
			],
			// \ant\facebook\controllers\DefaultController::class => [
			// 	'index' => ['View own file', [Role::ROLE_GUEST]],
			// ],
			\ant\facebook\backend\controllers\DefaultController::class => [
				'index' => ['View own file', [Role::ROLE_GUEST]],
			],
		];
		
		parent::init();
	}
	
	public function up()
    {
		$this->addAllPermissions($this->permissions);
    }

    public function down()
    {
		$this->removeAllPermissions($this->permissions);
    }
}
