<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTablePrizeContest extends AbstractMigration
{
#region base func
    const TABLE_NAME_PRIZE_CONTEST = 'prize_contest';
    const TABLE_NAME_PRIZE_CONTEST_SCHEDULE = 'prize_contest_schedule';

    const TABLE_NAME_PRIZE_CONTEST_RECORD = 'prize_contest_record';
    const TABLE_NAME_PRIZE_CONTEST_RECORD_ITEM = 'prize_contest_record_item';

    public function up()
    {
        $this->down();

        $this->savePrizeContest();

        $this->savePrizeContestSchedule();

        $this->savePrizeContestRecord();
        $this->savePrizeContestRecordItem();
    }

    public function down()
    {
        $this->dropPrizeContest();

        $this->dropPrizeContestSchedule();

        $this->dropPrizeContestRecord();

        $this->dropPrizeContestRecordItem();
    }
#endregion

#region prize contest
    public function savePrizeContest()
    {
        $table = $this->table(self::TABLE_NAME_PRIZE_CONTEST, [
            'signed' => false,
            'comment' => '有奖竞猜 - 冲顶配置',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '冲顶标题'])
            ->addColumn('entry_num', 'integer', ['null' => false, 'default' => 3, 'signed' => false, 'comment' => '每日参与名额'])
            ->addColumn('topic_num', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '题库数目'])
            ->addColumn('pic', 'string', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '图片地址'])
            ->addColumn('remark', 'text', ['null' => false, 'default' => '','comment' => '内容备注'])
            ->addColumn('is_asset_award_section', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否分段结算资产奖励 1-是 2-否'
            ])
            ->addColumn('start_date', 'date', ['null' => false, 'comment' => '起始日期'])
            ->addColumn('end_date', 'date', ['null' => false, 'comment' => '截止日期'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->create();
    }

    public function dropPrizeContest()
    {
        if ($this->hasTable(self::TABLE_NAME_PRIZE_CONTEST)) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST)->drop()->save();
        }
    }
#endregion

#region prize contest schedule
    public function savePrizeContestSchedule()
    {
        $table = $this->table(self::TABLE_NAME_PRIZE_CONTEST_SCHEDULE, [
            'signed' => false,
            'comment' => '有奖竞猜 - 冲顶赛程设置',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('sort', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '顺序'])
            ->addColumn('prize_contest_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '冲顶赛程id'])
            ->addColumn('is_asset_award', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否资产奖励 1-是 2-否'
            ])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '资产奖励额度', 'default' => '1'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->create();
    }

    public function dropPrizeContestSchedule()
    {
        if ($this->hasTable(self::TABLE_NAME_PRIZE_CONTEST_SCHEDULE)) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST_SCHEDULE)->drop()->save();
        }
    }
#endregion

#region prize contest record
    public function savePrizeContestRecord()
    {
        $table = $this->table(self::TABLE_NAME_PRIZE_CONTEST_RECORD, [
            'signed' => false,
            'comment' => '有奖竞猜 - 冲顶记录',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('prize_contest_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '冲顶赛程id'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户account_id'])
            ->addColumn('date', 'date', ['null' => false, 'comment' => '参与日期'])
            ->addColumn('is_through', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否通关 1-是 2-否'
            ])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '资产奖励额度', 'default' => '1'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('problem_set', 'text', ['null' => false, 'default' => '','comment' => '题集- topic id集合 - json'])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->create();
    }

    public function dropPrizeContestRecord()
    {
        if ($this->hasTable(self::TABLE_NAME_PRIZE_CONTEST_RECORD)) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST_RECORD)->drop()->save();
        }
    }
#endregion

#region prize contest record item
    public function savePrizeContestRecordItem()
    {
        $table = $this->table(self::TABLE_NAME_PRIZE_CONTEST_RECORD_ITEM, [
            'signed' => false,
            'comment' => '有奖竞猜 - 冲顶 - 答题记录',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('prize_contest_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '冲顶赛程id'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户account_id'])
            ->addColumn('date', 'date', ['null' => false, 'comment' => '参与日期'])
            ->addColumn('knowledge_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '知识点id'])
            ->addColumn('topic_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '题目id'])
            ->addColumn('sort', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '顺序'])
            ->addColumn('draft', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '草稿'])
            ->addColumn('answer', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '答案'])
            ->addColumn('is_correct', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否正确 1-是 2-否'
            ])
            ->addColumn('is_asset_award', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否资产奖励 1-是 2-否'
            ])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '资产奖励额度', 'default' => '1'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->create();
    }

    public function dropPrizeContestRecordItem()
    {
        if ($this->hasTable(self::TABLE_NAME_PRIZE_CONTEST_RECORD_ITEM)) {
            $this->table(self::TABLE_NAME_PRIZE_CONTEST_RECORD_ITEM)->drop()->save();
        }
    }
#endregion
}
