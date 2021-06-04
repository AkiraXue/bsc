<?php
/**
 * Constants.php
 *
 * @copyright Copyright (c) 2019 AkiraXue
 * @author akira.xue <18862104333@163.com>
 * @created on 5/7/21 1:13 PM
 */

namespace Lib;

class Constants
{
    // 添加-add 修改-modify
    const ACT_ADD = 'add';
    const ACT_MODIFY = 'modify';

    // 值 1-是 2-否
    const YES_VALUE = 1;
    const NO_VALUE  = 2;

    // 视频 - video，图片 - pic， 图文 - graphic，文案 - text
    const MATERIAL_TYPE_VIDEO = 'video';
    const MATERIAL_TYPE_PIC = 'pic';
    const MATERIAL_TYPE_GRAPHIC = 'graphic';
    const MATERIAL_TYPE_TEXT = 'text';

    const KNOWLEDGE_TYPE_BANNER = 'banner';
    const KNOWLEDGE_TYPE_GUIDE = 'guide';

    // tag type
    const TAG_RELATION_TYPE_KNOWLEDGE_ID = 'knowledge_id';

    // 1-总规则 2-冲顶规则
    const RULE_TYPE_ALL = 1;
    const RULE_TYPE_PRIZE = 2;
}
