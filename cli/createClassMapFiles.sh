#!/bin/sh


BASEDIR=$(dirname $0)
APP_DIR=$BASEDIR/..


CLASSMAP_GENERATOR=${APP_DIR}/vendor/zendframework/zendframework/bin/classmap_generator.php

php ${CLASSMAP_GENERATOR} --library ${APP_DIR}/module/Resources/src/Resources --overwrite --output ${APP_DIR}/module/Resources/src/autoload_classmap.php
php ${CLASSMAP_GENERATOR} --library ${APP_DIR}/vendor/zendframework/zendframework/library/Zend --overwrite --output ${APP_DIR}/vendor/zendframework/zendframework/library/Zend/autoload_classmap.php


