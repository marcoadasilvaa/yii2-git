yii2-git
========

Módulo que permitirá visualizar repositorios GIT utilizando Yii Framework.

En desarrollo...


## Instalación

### Composer

La mejor forma de instalar esta extensión es a través de [Composer](http://getcomposer.org/).

Ejecuta

```
php composer.phar require markmarco16/yii2-git "dev-master"
```

o agrega

```
"markmarco16/yii2-git": "dev-master"
```

en la sección de requeridos en tu ```composer.json```


## Uso

Configura este módulo en la configuracion de tu aplicación Yii

```php
<?php
    ......
    'modules' => [
        'git' => [
            'class' => 'app\module\git\Module',
        ],
    ],
    ......
```
