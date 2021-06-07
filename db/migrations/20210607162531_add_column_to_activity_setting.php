<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddColumnToActivitySetting extends AbstractMigration
{
    const TABLE_NAME_ACTIVITY_SCHEDULE = 'activity_schedule';
    const TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD = 'activity_participate_record';

    public function up()
    {
        $this->down();
        $this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)
            ->addColumn('is_knowledge_asset_award', 'integer', [
                'after' => 'knowledge_id',
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '知识库是否资产奖励 1-是 2-否'
            ])
            ->addColumn('knowledge_asset_num', 'decimal', ['after' => 'is_knowledge_asset_award', 'precision' => '10', 'scale' => '2', 'comment' => '知识库资产奖励额度', 'default' => '1'])
            ->save();
        $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)
            ->addColumn('is_knowledge_asset_award', 'integer', [
                'after' => 'knowledge_time',
                'null' => false,
                'default' => 1,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '知识库是否资产奖励 1-是 2-否'
            ])
            ->addColumn('knowledge_asset_num', 'decimal', ['after' => 'is_knowledge_asset_award', 'precision' => '10', 'scale' => '2', 'comment' => '知识库资产奖励额度', 'default' => '1'])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)->hasColumn('is_knowledge_asset_award')) {
            $this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)->removeColumn('is_knowledge_asset_award')->save();
        }
        if ($this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)->hasColumn('knowledge_asset_num')) {
            $this->table(self::TABLE_NAME_ACTIVITY_SCHEDULE)->removeColumn('knowledge_asset_num')->save();
        }

        if ($this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)->hasColumn('is_knowledge_asset_award')) {
            $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)->removeColumn('is_knowledge_asset_award')->save();
        }
        if ($this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)->hasColumn('knowledge_asset_num')) {
            $this->table(self::TABLE_NAME_ACTIVITY_PARTICIPATE_RECORD)->removeColumn('knowledge_asset_num')->save();
        }
    }
}
