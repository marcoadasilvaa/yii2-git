<?php

namespace markmarco16\git;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'markmarco16\git\controllers';

    public $gitDir;

    public $datetimeFormat = '%Y-%m-%d %H:%M:%S';

	public $subjectMaxLength = 80;
	
	/** @var array The rules to be used in URL management. */
    //public $urlRules = [
    //    '<id:\d+>'                    => 'document/view',
        /*
        '<action:(login|logout)>'     => 'security/<action>',
        '<action:(register|resend)>'  => 'registration/<action>',
        'confirm/<id:\d+>/<code:\w+>' => 'registration/confirm',
        'forgot'                      => 'recovery/request',
        'recover/<id:\d+>/<code:\w+>' => 'recovery/reset',
        'settings/<action:\w+>'       => 'settings/<action>'
         */
    //];

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
