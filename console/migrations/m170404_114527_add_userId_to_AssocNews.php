<?php

use yii\db\Migration;

class m170404_114527_add_userId_to_AssocNews extends Migration
{
    public function safeUp()
    {
    	$this->addColumn(\common\models\AssocNews::tableName(), 'canEditRegionId', $this->integer());
    	$this->addColumn(\common\models\AssocNews::tableName(), 'creatorUserId', $this->integer()->notNull()->defaultValue(\common\helpers\UserHelper::CONSOLE_LOG_USER_ID));
    }

    public function safeDown()
    {
    	$this->dropColumn(\common\models\AssocNews::tableName(), 'canEditRegionId');
    	$this->dropColumn(\common\models\AssocNews::tableName(), 'creatorUserId');
    }
}
