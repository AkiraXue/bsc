<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableKnowledge extends AbstractMigration
{
#region base func
    const TABLE_NAME_TOPIC = 'topic';
    const TABLE_NAME_KNOWLEDGE= 'knowledge';

    public function up()
    {
        $this->down();

        $this->saveKnowledge();

        $this->saveTopic();
    }

    public function down()
    {
        $this->dropKnowledge();

        $this->dropTopic();
    }
#endregion

#region knowledge
    public function saveKnowledge()
    {
        $table = $this->table(self::TABLE_NAME_KNOWLEDGE, [
            'signed' => false,
            'comment' => '知识库',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('title', 'char', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '标题'])
            ->addColumn('type', 'string', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '类型 - 视频 - video，图片 - pic， 图文 - graphic，文案 - text'])
            ->addColumn('pic', 'string', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '图片地址'])
            ->addColumn('content', 'text', ['null' => false, 'comment' => '内容'])
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

    public function dropKnowledge()
    {
        if ($this->hasTable(self::TABLE_NAME_KNOWLEDGE)) {
            $this->table(self::TABLE_NAME_KNOWLEDGE)->drop()->save();
        }
    }
#endregion

#region topic
    public function saveTopic()
    {
        $table = $this->table(self::TABLE_NAME_TOPIC, [
            'signed' => false,
            'comment' => '题库',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('title', 'char', ['null' => false, 'default' => '', 'length' => 254, 'comment' => '问题名称'])
            ->addColumn('type', 'string', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '类型: 视频 - video，图片 - pic， 图文 - graphic，文案 - text'])
            ->addColumn('answer_type', 'string', ['null' => false, 'default' => '', 'length' => 50, 'comment' => '答题方式: 阅读-read 上传-upload 选择-choice'])
            ->addColumn('knowledge_id', 'integer', ['null' => false, 'default' => 0, 'signed' => false, 'comment' => '知识点id'])
            ->addColumn('content', 'text', ['null' => false, 'comment' => '题目内容 - json'])
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

    public function dropTopic()
    {
        if ($this->hasTable(self::TABLE_NAME_TOPIC)) {
            $this->table(self::TABLE_NAME_TOPIC)->drop()->save();
        }
    }
#endregion
}
