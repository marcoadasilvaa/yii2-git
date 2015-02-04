yii2-git
========

Módulo que permitirá visualizar repositorios GIT utilizando Yii Framework.

En desarrollo...

[![Latest Stable Version](https://poser.pugx.org/markmarco16/yii2-git/v/stable.svg)](https://packagist.org/packages/markmarco16/yii2-git) 
[![Total Downloads](https://poser.pugx.org/markmarco16/yii2-git/downloads.svg)](https://packagist.org/packages/markmarco16/yii2-git) 
[![Latest Unstable Version](https://poser.pugx.org/markmarco16/yii2-git/v/unstable.svg)](https://packagist.org/packages/markmarco16/yii2-git) 
[![License](https://poser.pugx.org/markmarco16/yii2-git/license.svg)](https://packagist.org/packages/markmarco16/yii2-git)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/markmarco16/yii2-git/badges/build.png?b=master)](https://scrutinizer-ci.com/g/markmarco16/yii2-git/build-status/master)
[![Dependency Status](https://www.versioneye.com/user/projects/54cfb7793ca0840b19000002/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54cfb7793ca0840b19000002)
[![Code Climate](https://codeclimate.com/github/markmarco16/yii2-git/badges/gpa.svg)](https://codeclimate.com/github/markmarco16/yii2-git)


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
            'class' => 'markmarco16\git\Module',
        ],
    ],
    ......
```


## Requerimientos

* Verificar datetime local de los Commits.
* Iconos en el view _file y el titulo de la tabla.
* Verificar de los hash commit y files antes de se empleados en el component.
* Iconos en el view para graficas, estadisticas y configuraciones.
* Exportar style para diff view.
* Estables como parametros el directorio raiz de los repositorios.
* Posible migración de table a GridView de _diff view.
* Optimizar ArrayDataProvider de getRevList.
* Visualización de archivos binarios como PNG.
* Modificar Enlaces Yii::t, Html::a