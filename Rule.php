<?php
/**
 * Rule.php
 * @author Revin Roman http://phptime.ru
 */

namespace rmrevin\yii\rbac;

/**
 * Class Rule
 * @package rmrevin\yii\rbac
 */
class Rule extends \yii\rbac\Rule
{

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        throw new \yii\base\NotSupportedException();
    }
}