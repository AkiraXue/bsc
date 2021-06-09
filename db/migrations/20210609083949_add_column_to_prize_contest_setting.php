<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddColumnToPrizeContestSetting extends AbstractMigration
{
    const TABLE_NAME_PRIZE_CONTEST = 'prize_contest';

    public function up()
    {
        $this->down();
        $this->table(self::TABLE_NAME_PRIZE_CONTEST)
            ->addColumn('is_through', 'integer', [
                'after'     => 'pic',
                'null'      => false,
                'default'   => 2,
                'limit'     => MysqlAdapter::INT_TINY,
                'signed'    => false,
                'comment'   => '回答全部题目 1-是 2-否'
            ])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_NAME_PRIZE_CONTEST)->hasColumn('is_through')) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST)->removeColumn('is_through')->save();
        }
    }
}
