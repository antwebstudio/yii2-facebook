<?php

namespace ant\facebook\migrations\db;

use ant\db\Migration;

/**
 * Class M200705111016CreateFacebookInstantArticleLog
 */
class M200705111016CreateFacebookInstantArticleLog extends Migration
{
	protected $tableName = '{{%facebook_instant_article_log}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'publishable_class' => $this->morphClass(),
			'publishable_id' => $this->morphId(),
			'submission_id' => $this->string()->null()->defaultValue(null),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultValue(NULL),
        ], $this->getTableOptions());
		
		//$this->createIndexFor('publishable_class');
		$this->addForeignKeyTo('{{%model_class}}', 'publishable_class');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200705111016CreateFacebookInstantArticleLog cannot be reverted.\n";

        return false;
    }
    */
}
