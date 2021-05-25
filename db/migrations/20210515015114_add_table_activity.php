<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableActivity extends AbstractMigration
{
#region base func
    const TABLE_NAME_ACTIVITY= 'activity';
    const TABLE_NAME_ACTIVITY_SCHEDULE = 'activity_schedule';

    const TABLE_NAME_ACTIVITY_PARTICIPATE_SCHEDULE= 'activity_participate_schedule';
    const TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD = 'activity_participate_record';

    public function up()
    {
        $this->down();

        $this->saveActivity();

        $this->saveActivitySchedule();

        $this->saveActivityParticipateSchedule();

        $this->saveActivityParticipateRecord();
    }

    public function down()
    {
        $this->dropActivity();

        $this->dropActivitySchedule();

        $this->dropActivityParticipateSchedule();

        $this->dropActivityParticipateRecord();
    }
#endregion

#region activity
    public function saveActivity()
    {
        $table = $this->table(self::TABLE_NAME_ACTIVITY, [
            'signed' => false,
            'comment' => '活动',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '名称'])
            ->addColumn('code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '活动唯一code'])
            ->addColumn('days', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '活动周期'])
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
            ->addIndex(['code'], ['name' => 'idx_code'])
            ->create();
    }

    public function dropActivity()
    {
        if ($this->hasTable(self::TABLE_NAME_ACTIVITY)) {
            $this->table(self::TABLE_NAME_ACTIVITY)->drop()->save();
        }
    }
#endregion


#region activity schedule
    public function saveActivitySchedule()
    {
        $table = $this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE, [
            'signed' => false,
            'comment' => '活动排期',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('activity_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '活动唯一code'])
            ->addColumn('day', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '活动第几天'])
            ->addColumn('is_related_knowledge', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否关联知识科普 1-是 2-否'
            ])
            ->addColumn('knowledge_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '知识库id'])
            ->addColumn('is_asset_award', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否资产奖励 1-是 2-否'
            ])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '资产奖励额度', 'default' => '1'])
            ->addColumn('state', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '状态 1-正常 2-关闭'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->addIndex(['activity_code'], ['name' => 'idx_activity_code'])
            ->create();
    }

    public function dropActivitySchedule()
    {
        if ($this->hasTable(self::TABLE_NAME_ACTIVITY_SCHEDULE)) {
            $this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)->drop()->save();
        }
    }
#endregion

#region activity participate schedule
    public function saveActivityParticipateSchedule()
    {
        $table = $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_SCHEDULE, [
            'signed' => false,
            'comment' => '活动参与排期关联',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('activity_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '活动唯一code'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户唯一code'])
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
            ->addIndex(['activity_code'], ['name' => 'idx_activity_code'])
            ->addIndex(['account_id'], ['name' => 'idx_account_id'])
            ->create();
    }

    public function dropActivityParticipateSchedule()
    {
        if ($this->hasTable(self::TABLE_NAME_ACTIVITY_PARTICIPATE_SCHEDULE)) {
            $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_SCHEDULE)->drop()->save();
        }
    }
#endregion

#region activity participate record
    public function saveActivityParticipateRecord()
    {
        $table = $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD, [
            'signed' => false,
            'comment' => '活动排期参与记录',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('activity_code', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '活动唯一code'])
            ->addColumn('account_id', 'char', ['null' => false, 'default' => '', 'length' => 32, 'comment' => '用户唯一code'])
            ->addColumn('day', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '活动第几天'])
            ->addColumn('activity_schedule_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '活动关联排期id'])
            ->addColumn('is_related_knowledge', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否关联知识科普 1-是 2-否'
            ])
            ->addColumn('knowledge_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '知识库id'])
            ->addColumn('is_asset_award', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否资产奖励 1-是 2-否'
            ])
            ->addColumn('asset_num', 'decimal', ['precision' => '10', 'scale' => '2', 'comment' => '资产奖励额度', 'default' => '1'])
            ->addColumn('is_knowledge', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否已知识科普 1-是 2-否'
            ])
            ->addColumn('knowledge_time', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '知识科普时间'])
            ->addColumn('is_punch', 'integer', [
                'null' => false,
                'default' => 2,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '是否打卡  1-是 2-否'
            ])
            ->addColumn('punch_time', 'timestamp', ['null' => false , 'default' => '', 'update' => '', 'comment' => '打卡时间'])
            ->addColumn('punch_date', 'date', ['null' => false, 'comment' => '当前打卡日期'])
            ->addColumn('recent_punch_date',  'date', ['null' => false, 'comment' => '上次打卡日期'])
            ->addColumn('next_punch_date', 'date', ['null' => false, 'comment' => '下次打卡日期'])
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
            ->addIndex(['activity_code'], ['name' => 'idx_activity_code'])
            ->addIndex(['account_id'], ['name' => 'idx_account_id'])
            ->create();
    }

    public function dropActivityParticipateRecord()
    {
        if ($this->hasTable(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)) {
            $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)->drop()->save();
        }
    }
#endregion
}
