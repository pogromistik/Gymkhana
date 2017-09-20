<?php

use yii\db\Migration;

class m170919_064554_add_photo_crop_to_Athletes extends Migration
{
	public function safeUp()
	{
		$this->addColumn(\common\models\Athlete::tableName(), 'photoCrop', $this->string());
	}
	
	public function safeDown()
	{
		$this->dropColumn(\common\models\Athlete::tableName(), 'photoCrop');
	}
}
