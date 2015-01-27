yii2-git
========

Módulo que permitirá visualizar repositorios GIT utilizando Yii Framework.

En desarrollo...

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/build.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/build-status/master)

## Instalación

### Composer

La mejor forma de instalar esta extensión es a través de [Composer](http://getcomposer.org/).

Ejecuta

```
php composer.phar require markmarco16/yii2-git "*"
```

o agrega

```
"markmarco16/yii2-git": "*"
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
