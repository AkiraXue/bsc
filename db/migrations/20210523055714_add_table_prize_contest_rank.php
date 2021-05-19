<?php

use Phinx\Migration\AbstractMigration;

final class AddTablePrizeContestRank extends AbstractMigration
{
    const TABLE_NAME_PRIZE_CONTEST_RANK = 'prize_contest_rank';

    public function up()
    {
        $this->down();

        $table = $this->table(self::TABLE_NAME_PRIZE_CONTEST_RANK, [
            'signed' => false,
            'comment' => '冲顶排行榜',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '关联唯一码'])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '变动金额', 'default' => '1'])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['account_id'], ['name' => 'idx_account_id'])
            ->create();
    }

    public function down()
    {
        if ($this->hasTable(self::TABLE_NAME_PRIZE_CONTEST_RANK)) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST_RANK)->drop()->save();
        }
    }
}
