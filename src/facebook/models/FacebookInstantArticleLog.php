<?php

namespace ant\facebook\models;

use Yii;

/**
 * This is the model class for table "{{%facebook_instant_article_log}}".
 *
 * @property int $id
 * @property string $url
 * @property string|null $submission_id
 * @property int $status
 * @property string|null $created_at
 */
class FacebookInstantArticleLog extends \yii\db\ActiveRecord
{
	const STATUS_SUCCESS = 0;
	const STATUS_FAILED = 1;
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%facebook_instant_article_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'publishable_id'], 'integer'],
            [['created_at'], 'safe'],
            [['submission_id', 'publishable_class'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'submission_id' => 'Submission ID',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
