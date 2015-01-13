Yii 2 extension for RBAC migrations
===============================

Installation
------------
Add in `composer.json`:
```
{
    "require": {
        "rmrevin/yii2-rbac-migration": "1.0.0"
    }
}
```

Usage
-----
Create new migration extends \rmrevin\yii\rbac\RbacMigration
and execute as normal migration
```php
<?
// ...

class m140217_201400_rbac extends \rmrevin\yii\rbac\RbacMigration
{

    protected function getNewRoles()
    {
        return [
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'customer' => 'Customer',
            'user' => 'User',
        ];
    }

    protected function getNewPermissions()
    {
        return [
            'catalog.view' => 'Can view catalog',
            'catalog.order' => 'Can order items from catalog',
            'catalog.favorite' => 'Can mark favorite items',
        ];
    }

    protected function getInheritance()
    {
        return [
            'admin' => [
                'manager', // inherit role manager and all permissions from role manager & user
            ],
            'manager' => [
                'user', // inherit role user and all permissions from role user
            ],
            'customer' => [
                'user', // inherit role user and all permissions from role user

                'catalog.order', // inherit permission catalog.order
                'catalog.favorite', // inherit permission catalog.favorite
            ],
            'user' => [
                'catalog.view', // inherit permission catalog.view
            ],
        ];
    }
}

```