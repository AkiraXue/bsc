<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

class AddTableRule extends AbstractMigration
{

    const TABLE_RULE_NAME= 'rule';

    public function up()
    {
        $this->down();

        $table = $this->table(self::TABLE_RULE_NAME, [
            'signed' => false,
            'comment' => '规则',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_unicode_ci '
        ]);

        $table->addColumn('name', 'char', ['null' => false, 'length' => 32, 'default' => '', 'comment' => '字段名称'])
            ->addColumn('type', 'integer', [
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '字段类型 1-总规则 2-冲顶规则'
            ])
            ->addColumn('remark', 'text', ['null' => false, 'comment' => '备注'])
            ->addColumn('state', 'integer', [
                'default' => 1,
                'null' => false,
                'limit' => MysqlAdapter::INT_TINY,
                'signed' => false,
                'comment' => '字段类型 1-有效 2-无效'
            ])
            ->addColumn('created_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => '', 'comment' => '创建时间'])
            ->addColumn('updated_at', 'timestamp', ['null' => false , 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP',  'comment' => '更新时间'])
            ->addIndex(['id'], ['unique' => true, 'name' => 'id'])
            ->save();
    }

    public function down()
    {
        if ($this->table(self::TABLE_RULE_NAME)->exists()) {
            $this->table(self::TABLE_RULE_NAME)->drop()->save();
        }
    }
}
