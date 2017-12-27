<?php

use yii\db\Migration;

/**
 * Class m171227_053159_add_offerUserId_to_AssocNews
 */
class m171227_053159_add_offerUserId_to_AssocNews extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
		$this->addColumn(\common\models\AssocNews::tableName(), 'offerUserId', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(\common\models\AssocNews::tableName(), 'offerUserId');
    }
}
