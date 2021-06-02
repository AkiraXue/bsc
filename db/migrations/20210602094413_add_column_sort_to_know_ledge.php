<?php

use Phinx\Migration\AbstractMigration;

class AddColumnSortToKnowLedge extends AbstractMigration
{
    const TABLE_NAME_KNOWLEDGE= 'knowledge';

    public function up()
    {
        $this->down();
        $this->table(self::TABLE_NAME_KNOWLEDGE)
            ->addColumn('sort', 'integer', ['after' => 'content', 'null' => false, 'signed' => false, 'default' => 0, 'comment' => '默认排序-升序'])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_NAME_KNOWLEDGE)->hasColumn('sort')) {
            $this->table(self::TABLE_NAME_KNOWLEDGE)->removeColumn('sort')->save();
        }
    }
}
