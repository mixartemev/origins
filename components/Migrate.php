<?php

namespace app\components;

use yii\db\ColumnSchemaBuilder;
use yii\db\Migration;

class Migrate extends Migration
{
	/**
	 * @param int|null $length
	 * @return ColumnSchemaBuilder $this
	 */
	public function integer($length = 10)
	{
		return parent::integer($length)->unsigned();
	}

    /**
     * @param int $length
     * @return ColumnSchemaBuilder
     */
    public function smallInteger($length = 5)
	{
		return parent::smallInteger($length)->unsigned();
	}
	public function primaryKey($length = 10)
	{
		return parent::primaryKey($length)->unsigned();
	}

	/**
	 * This modifier is only for MySQL!
	 * @param int|null $length
	 * @return ColumnSchemaBuilder $this
	 */
	public function tinyInt($length = 3)
	{
		return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinyint', $length)->unsigned();
	}
}