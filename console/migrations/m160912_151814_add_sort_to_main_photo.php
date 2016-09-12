<?php

use yii\db\Migration;
use common\models\MainPhoto;

class m160912_151814_add_sort_to_main_photo extends Migration
{
    public function up()
    {
        $this->addColumn(MainPhoto::tableName(), 'sort', $this->integer());
    }

    public function down()
    {
        $this->dropColumn(MainPhoto::tableName(), 'sort');
    }
}
