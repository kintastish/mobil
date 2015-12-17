<?php

namespace app\components;

use Yii;
use yii\web\UrlRule;
use yii\base\InlineAction;
use app\controllers\MController;
use app\models\Categories;
use app\models\Resources;
use yii\helpers\VarDumper;
use yii\web\NotFoundHttpException;


class MUrlRule extends UrlRule
{
    const CATEGORY_PART = 1;
    const RESOURCE_PART = 2;
    const PARAM_PART    = 3;

    private $_cat = false;
    private $_res = false;
    private $_params = [];
    private $_predefined = ['feedback', 'captcha'];

    public function init()
    {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    private function getResourcePath($id)
    {
        if ( ($res = Resources::findOne($id)) !== null ) {
            $path = $res->alias;
            $path = $this->getCategoryPath($res->category_id).$path;
        }
        else {
            $path = '#wrs-'.$id;
        }
        return $path;
    }

    private function getCategoryPath($id)
    {
        $parents = Categories::getHierarchy($id);
        if (!count($parents)) {
            $path = '#wct-'.$id;
        }
        $path = '';
        foreach ($parents as $p) {
            $path = $p->alias.'/'.$path;
        }
        return $path;
    }

    public function createUrl($manager, $route, $params)
    {
        if (array_search($route, $this->_predefined) !== false) {
            return $route;
        }
        $parts = explode('/', $route);

        $handler = $parts[0];
        $action = 'index';
        $h_type = $this->getHandlerType($handler);

        if (isset($parts[1])) {
            $action = $parts[1];
        }
Yii::trace('route -> '.$route)        ;
        // else {
        //     if ($this->getHandlerType($handler) == MController::HANDLE_CAT) {
        //         $action = ($handler == 'cat' ? 'view' : 'index');
        //     }
        // }
        $id = 0;
        if (isset($params['id'])) {
            $id = $params['id'];
        }
        else {
            return '#'.$route;
        }
        
        $path = '';
        if ($action == 'view' && $handler != MController::HANDLE_CAT) {
            $path = $this->getResourcePath($id);
        }
        if ($h_type == MController::HANDLE_CAT || $action == 'index') {
            $path = $this->getCategoryPath($id);
        }
        Yii::trace($path);
        return $path;
    }

    public function parseRequest($manager, $request)
    {
        $route = '';
        $params = [];

        $pathInfo = $request->getPathInfo();
        if ($pathInfo == '') {
            return false;
        }

        $parts = explode('/', $pathInfo);
        $next = self::CATEGORY_PART;   //вначале всегда идет раздел
        $parent_id = 0;

        foreach ($parts as $p) {
            if ($p != '') {
                switch ($next) {
                    case self::CATEGORY_PART:
                        $this->_cat = Categories::findByAlias($p, $parent_id);
                        if ( $this->_cat === null ) {
                            throw new NotFoundHttpException('Страница не найдена');
                        }
                        
                        if ( $this->getHandlerType($this->_cat->handler) == MController::HANDLE_CAT ) {
                            $next = self::CATEGORY_PART;
                            $parent_id = $this->_cat->id;
                        }
                        else {
                            $next = self::RESOURCE_PART;
                        }
                        break;
                    case self::RESOURCE_PART:
                        $this->_res = Resources::findByAlias($p, $this->_cat->id);
                        if ($this->_res === null) {
                            throw new NotFoundHttpException('Страница не найдена');
                        }
                        $next = self::PARAM_PART;
                        break;
                    case self::PARAM_PART:
                        $this->_params[] = $p;
                        break; 
                }
            }
        }
        $route = $this->_cat->handler;

        // Если раздел содержит всего один ресурс/подраздел и $show_single == 1, тогда покажем дочерний элемент
        // Если дочерний элемент подраздел и $show_single == 1 идем ниже и т.д.
        if ($this->_res == false) {
            $action = 'index';  
            $params = ['id' => $this->_cat->id];

            if ($this->_cat->show_single) {
                while ((count($this->_cat->subcategories) == 1) &&
                        $this->_cat->show_single == 1 ) {
                    $this->_cat = $this->_cat->subcategories[0];
                }
                $route = $this->_cat->handler;
                $params = ['id' => $this->_cat->id];

                if ((count($this->_cat->resources) == 1) && $this->_cat->show_single) {
                    $action = 'view';
                    $params = ['id' => $this->_cat->resources[0]->id];
                }
            }
        }
        else {
            $action = 'view';
            $params = ['id' => $this->_res->id];
        }

        $route .= '/'.$action;

        return [$route, $params];
    }


    private function getHandlerType($handler)
    {
        $contr = Yii::$app->controllerNamespace.'\\'.ucfirst($handler).'Controller';
        return $contr::$handlerType;
    }



//     private function getMControllerSuccessors()
//     {
//         $path = Yii::$app->controllerPath;
//         $files = scandir($path);
//         $m = [];
//         foreach ($files as $f) {
//             if (strpos($f, 'Controller.php') !== false) {
//                 $controllerName = strtolower( str_replace('Controller.php', '', $f) );
//             }
// //            if ($controllerName != 'admin') {
//             $c = Yii::$app->createController(strtolower($controllerName));
//             if ($c !== false) {
//                 if ($c[0] instanceof MController) {
//                     $m[] = $controllerName;
//                 }
//             }
//             unset $c;
// //            }
//         }
//         return $m;
//     }
    // public function bindActionParams($action, $params)
    // {
    //     if ($action instanceof InlineAction) {
    //         $method = new \ReflectionMethod($this, $action->actionMethod);
    //     } else {
    //         $method = new \ReflectionMethod($action, 'run');
    //     }

    //     $args = [];
    //     $missing = [];
    //     $actionParams = [];
    //     foreach ($method->getParameters() as $param) {
    //         $name = $param->getName();
    //         if (array_key_exists($name, $params)) {
    //             if ($param->isArray()) {
    //                 $args[] = $actionParams[$name] = is_array($params[$name]) ? $params[$name] : [$params[$name]];
    //             } elseif (!is_array($params[$name])) {
    //                 $args[] = $actionParams[$name] = $params[$name];
    //             } else {
    //                 throw new BadRequestHttpException(Yii::t('yii', 'Invalid data received for parameter "{param}".', [
    //                     'param' => $name,
    //                 ]));
    //             }
    //             unset($params[$name]);
    //         } elseif ($param->isDefaultValueAvailable()) {
    //             $args[] = $actionParams[$name] = $param->getDefaultValue();
    //         } else {
    //             $missing[] = $name;
    //         }
    //     }

    //     if (!empty($missing)) {
    //         throw new BadRequestHttpException(Yii::t('yii', 'Missing required parameters: {params}', [
    //             'params' => implode(', ', $missing),
    //         ]));
    //     }

    //     $this->actionParams = $actionParams;

    //     return $args;



    //            if ($id === '') {
    //         $id = $this->defaultAction;
    //     }

    //     $actionMap = $this->actions();
    //     if (isset($actionMap[$id])) {
    //         return Yii::createObject($actionMap[$id], [$id, $this]);
    //     } elseif (preg_match('/^[a-z0-9\\-_]+$/', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
    //         $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
    //         if (method_exists($this, $methodName)) {
    //             $method = new \ReflectionMethod($this, $methodName);
    //             if ($method->isPublic() && $method->getName() === $methodName) {
    //                 return new InlineAction($id, $this, $methodName);
    //             }
    //         }
    //     }
    // }



}