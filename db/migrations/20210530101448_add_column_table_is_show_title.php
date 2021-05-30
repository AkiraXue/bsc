<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddColumnTableIsShowTitle extends AbstractMigration
{
    const TABLE_TAG_NAME= 'tag';

    public function up()
    {
        $this->down();
        $this->table(self::TABLE_TAG_NAME)
            ->addColumn('is_show_title', 'integer', [
                'after' => 'bg_video',
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '图片类型 1-显示 2-不显示'
            ])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_TAG_NAME)->hasColumn('is_show_title')) {
            $this->table(self::TABLE_TAG_NAME)->removeColumn('is_show_title')->save();
        }
    }
}
