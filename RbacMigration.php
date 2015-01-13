<?php
/**
 * RbacMigration.php
 * @author Revin Roman http://phptime.ru
 */

namespace rmrevin\yii\rbac;

/**
 * Class RbacMigration
 * @package rmrevin\yii\rbac
 */
abstract class RbacMigration extends \yii\db\Migration
{

    /**
     * @throws \yii\base\InvalidConfigException
     * @return \yii\rbac\DbManager
     */
    protected function getAuthManager()
    {
        $authManager = \Yii::$app->getAuthManager();
        if (!$authManager instanceof \yii\rbac\DbManager) {
            throw new \yii\base\InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }
        return $authManager;
    }

    /**
     * @return array
     */
    protected function getInheritance()
    {
        return [];
//        example
//        return [
//            'admin' => [
//                'manager',
//                'backend.access',
//            ],
//            'manager' => [
//                'user',
//                'frontend.statistics.access',
//                'frontend.orders.access',
//            ],
//            'client' => [
//                'user',
//            ],
//            'user' => [
//                'frontend.access',
//                'frontend.feedback.access',
//                'frontend.catalog.access',
//                'frontend.cart.access',
//            ],
//        ];
    }

    /**
     * @return array
     */
    protected function getNewRules()
    {
        return [];
//        example
//        return [
//            'its.my.order',
//            'its.my.comment',
//        ];
    }

    /**
     * @return array
     */
    protected function getRenamedRules()
    {
        return [];
//        example
//        return [
//            'its.my.comment' => [
//                'old' => 'its.my.comment',
//                'new' => 'its-my-comment',
//            ]
//        ];
    }

    /**
     * @return array
     */
    protected function getRemoveRules()
    {
        return [];
//        example
//        return [
//            'its.my.comment',
//        ];
    }

    /**
     * @return array
     */
    protected function getNewRoles()
    {
        return [];
//        example
//        return [
//            'admin'   => 'Administrator',
//            'manager' => RbacFactory::Role('manager', 'Manager'),
//            'client'  => RbacFactory::Role('client',  'Client', rule),
//            'user'    => 'User',
//        ];
    }

    /**
     * @return array
     */
    protected function getRenamedRoles()
    {
        return [];
//        example
//        return [
//            'manager' => [
//                'old' => RbacFactory::Role('manager',    'Manager'),
//                'new' => RbacFactory::Role('supervisor', 'Supervisor'),
//            ],
//        ];
    }

    /**
     * @return array
     */
    protected function getRemoveRoles()
    {
        return [];
//        example
//        return [
//            'client' => 'Client',
//        ];
    }

    /**
     * @return array
     */
    protected function getNewPermissions()
    {
        return [];
//        example
//        return [
//            'frontend.private.access' => 'Can enter to private area',
//            'frontend.private.orders' => 'Can view all orders',
//        ];
    }

    /**
     * @return array
     */
    protected function getRenamedPermissions()
    {
        return [];
//        example
//        return [
//            'frontend.private.access' => [
//                'old' => RbacFactory::Permission('frontend.cart.refresh', 'Can refresh cart'),
//                'new' => RbacFactory::Permission('frontend.cart.refresh', 'Can refresh cart with goods', 'i.authorized'),
//            ],
//        ];
    }

    /**
     * @return array
     */
    protected function getRemovePermissions()
    {
        return [];
//        example
//        return [
//            'frontend.private.access' => 'Can enter to private area',
//        ];
    }

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->upRules();
        $this->upRoles();
        $this->upPermissions();

        if ($this->upInheritance()) {
            echo '    > Permissions have been successfully updated.' . PHP_EOL;
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if ($this->downInheritance()) {
            $this->downPermissions();
            $this->downRoles();
            $this->downRules();

            echo '    > Permissions have been successfully updated.' . PHP_EOL;
        }
    }

    private function upRoles()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getNewRoles() as $role => $description) {
            $Role = $description instanceof \yii\rbac\Role
                ? $description
                : RbacFactory::Role($role, $description);

            $AuthManager->add($Role);
            echo "    > new role `{$Role->name}` added." . PHP_EOL;
        }

        foreach ($this->getRenamedRoles() as $role => $stack) {
            $AuthManager->update($stack['old']->name, $stack['new']);
            echo "    > role `{$stack['old']->name}` renamed to `{$stack['new']->name}`." . PHP_EOL;
        }

        foreach ($this->getRemoveRoles() as $role => $description) {
            $AuthManager->remove(RbacFactory::Role($role));
            echo "    > role `$role` removed." . PHP_EOL;
        }
    }

    private function downRoles()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getRemoveRoles() as $role => $description) {
            $Role = $description instanceof \yii\rbac\Role
                ? $description
                : RbacFactory::Role($role, $description);

            $AuthManager->add($Role);
            echo "    > old role `$role` restored." . PHP_EOL;
        }

        foreach ($this->getRenamedRoles() as $role => $stack) {
            $AuthManager->update($stack['new']->name, $stack['old']);
            echo "    > role `{$stack['new']->name}` renamed to `{$stack['old']->name}`." . PHP_EOL;
        }

        foreach ($this->getNewRoles() as $role => $description) {
            $Role = $description instanceof \yii\rbac\Role
                ? $description
                : RbacFactory::Role($role, $description);

            $AuthManager->remove($Role);
            echo "    > new role `$role` removed." . PHP_EOL;
        }
    }

    private function upRules()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getNewRules() as $rule) {
            $Rule = $rule instanceof \yii\rbac\Rule
                ? $rule
                : RbacFactory::Rule($rule);

            $AuthManager->add($Rule);
            echo "    > new rule `$rule` added." . PHP_EOL;
        }

        foreach ($this->getRenamedRules() as $rule => $stack) {
            $OldRule = $stack['old'] instanceof \yii\rbac\Rule
                ? $stack['old']
                : RbacFactory::Rule($stack['old']);

            $NewRule = $stack['new'] instanceof \yii\rbac\Rule
                ? $stack['new']
                : RbacFactory::Rule($stack['new']);

            $AuthManager->update($OldRule->name, $NewRule);
            echo "    > rule `{$OldRule->name}` renamed to `{$NewRule->name}`." . PHP_EOL;
        }

        foreach ($this->getRemoveRules() as $rule) {
            $Rule = $rule instanceof \yii\rbac\Rule
                ? $rule
                : RbacFactory::Rule($rule);

            $AuthManager->remove($Rule);
            echo "    > rule `$rule` removed." . PHP_EOL;
        }
    }

    private function downRules()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getRemoveRules() as $rule) {
            $Rule = $rule instanceof \yii\rbac\Rule
                ? $rule
                : RbacFactory::Rule($rule);

            $AuthManager->add($Rule);
            echo "    > old rule `{$Rule->name}` restored." . PHP_EOL;
        }

        foreach ($this->getRenamedRules() as $rule => $stack) {
            $OldRule = $stack['old'] instanceof \yii\rbac\Rule
                ? $stack['old']
                : RbacFactory::Rule($stack['old']);

            $NewRule = $stack['new'] instanceof \yii\rbac\Rule
                ? $stack['new']
                : RbacFactory::Rule($stack['new']);

            $AuthManager->update($NewRule->name, $OldRule);
            echo "    > rule `{$NewRule->name}` renamed to `{$OldRule->name}`." . PHP_EOL;
        }

        foreach ($this->getNewRules() as $rule) {
            $Rule = $rule instanceof \yii\rbac\Rule
                ? $rule
                : RbacFactory::Rule($rule);

            $AuthManager->remove($Rule);
            echo "    > new rule `{$Rule->name}` removed." . PHP_EOL;
        }
    }

    private function upPermissions()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getNewPermissions() as $permission => $description) {
            $Permission = $description instanceof \yii\rbac\Permission
                ? $description
                : RbacFactory::Permission($permission, $description);

            $AuthManager->add($Permission);
            echo "    > new permission `{$Permission->name}` added." . PHP_EOL;
        }

        foreach ($this->getRenamedPermissions() as $permission => $stack) {
            $AuthManager->update($stack['old']->name, $stack['new']);
            echo "    > permission `{$stack['old']->name}` renamed to `{$stack['new']->name}`." . PHP_EOL;
        }

        foreach ($this->getRemovePermissions() as $permission => $description) {
            $AuthManager->remove(RbacFactory::Permission($permission));
            echo "    > permission `{$permission}` removed." . PHP_EOL;
        }
    }

    private function downPermissions()
    {
        $AuthManager = $this->getAuthManager();

        foreach ($this->getRemovePermissions() as $permission => $description) {
            $Permission = $description instanceof \yii\rbac\Permission
                ? $description
                : RbacFactory::Permission($permission, $description);

            $AuthManager->add($Permission);
            echo "    > old permission `$permission` restored." . PHP_EOL;
        }

        foreach ($this->getRenamedPermissions() as $permission => $stack) {
            $AuthManager->update($stack['new']->name, $stack['old']);
            echo "    > permission `{$stack['new']->name}` renamed to `{$stack['old']->name}`." . PHP_EOL;
        }

        foreach ($this->getNewPermissions() as $permission => $description) {
            $Permission = $description instanceof \yii\rbac\Permission
                ? $description
                : RbacFactory::Permission($permission, $description);

            $AuthManager->remove($Permission);
            echo "    > new permission `{$permission}` removed." . PHP_EOL;
        }
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function upInheritance()
    {
        $AuthManager = $this->getAuthManager();

        $inheritance = $this->getInheritance();
        foreach ($inheritance as $parent => $children) {
            $Parent = $this->detectRbacItem($parent);
            if (empty($Parent)) {
                echo "    > ERROR. Item `$parent` not found." . PHP_EOL;
                return false;
            }

            foreach ($children as $child) {
                $Child = $this->detectRbacItem($child);
                if (empty($Child)) {
                    echo "    > ERROR. Item `$child` not found." . PHP_EOL;
                    return false;
                }

                $AuthManager->addChild($Parent['item'], $Child['item']);

                echo "    > {$Parent['type']} `$parent` successfully inherited {$Child['type']} `$child`." . PHP_EOL;
            }
        }

        return true;
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function downInheritance()
    {
        $AuthManager = $this->getAuthManager();

        $inheritance = $this->getInheritance();
        foreach ($inheritance as $parent => $children) {
            $Parent = $this->detectRbacItem($parent);
            if (empty($Parent)) {
                echo "    > ERROR. Item `$parent` not found." . PHP_EOL;
                return false;
            }

            foreach ($children as $child) {
                $Child = $this->detectRbacItem($child);
                if (empty($Child)) {
                    echo "    > ERROR. Item `$child` not found." . PHP_EOL;
                    return false;
                }

                $AuthManager->removeChild($Parent['item'], $Child['item']);

                echo "    > {$Child['type']} `$child` successfully detached from {$Parent['type']} `$parent`." . PHP_EOL;
            }
        }

        return true;
    }

    private $Roles = [];
    private $Permissions = [];

    /**
     * @param string $name
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    private function detectRbacItem($name)
    {
        $AuthManager = $this->getAuthManager();

        if (empty($this->Roles) && empty($this->Permissions)) {
            $this->Roles = $AuthManager->getRoles();
            $this->Permissions = $AuthManager->getPermissions();
        }

        $result = [];

        if (isset($this->Permissions[$name])) {
            $result['item'] = $this->Permissions[$name];
            $result['type'] = 'permission';
        }

        if (isset($this->Roles[$name])) {
            $result['item'] = $this->Roles[$name];
            $result['type'] = 'role';
        }

        return $result;
    }
}