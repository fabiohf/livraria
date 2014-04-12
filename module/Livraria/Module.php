<?php

namespace Livraria;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Livraria\Service\Categoria as CategoriaService;
use Livraria\Service\Livro as LivroService;
use Livraria\Service\User as UserService;
use LivrariaAdmin\Form\Livro as LivroFrm;
use Livraria\Auth\Adapter as AuthAdapter;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . 'Admin' => __DIR__ . '/src/' . __NAMESPACE__ . 'Admin',
                ),
            ),
        );
    }

    public function onBootstrap($e)
    {
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config = $e->getApplication()->getServiceManager()->get('config');
            if (isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace]);
            }
        }, 98);
    }

    public function init(ModuleManager $moduleManager)
    {
        $sharedManager = $moduleManager->getEventManager()->getSharedManager();
        $sharedManager->attach('LivrariaAdmin', 'dispatch', function($e) {
            $auth = new AuthenticationService();
            $auth->setStorage(new SessionStorage('LivrariaAdmin'));

            $controller = $e->getTarget();
            $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

            if (!$auth->hasIdentity() and ( $matchedRoute == 'livraria-admin' or $matchedRoute == 'livraria-admin-interna')) {
                return $controller->redirect()->toRoute('livraria-admin-auth');
            }
        }, 99);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Livraria\Model\CategoriaService' => function($service) {
            $dbAdapter = $service->get('Zend\Db\Adapter\Adapter');
            $categoriaTable = new Model\CategoriaTable($dbAdapter);
            $categoriaService = new Model\CategoriaService($categoriaTable);
            return $categoriaService;
        },
                'Livraria\Service\Categoria' => function($service) {
            return new CategoriaService($service->get('Doctrine\ORM\EntityManager'));
        },
                'Livraria\Service\Livro' => function($service) {
            return new LivroService($service->get('Doctrine\ORM\EntityManager'));
        },
                'Livraria\Service\User' => function($service) {
            return new UserService($service->get('Doctrine\ORM\EntityManager'));
        },
                'LivrariaAdmin\Form\Livro' => function($service) {
            $em = $service->get('Doctrine\ORM\EntityManager');
            $repository = $em->getRepository('Livraria\Entity\Categoria');
            $categorias = $repository->fetchPairs();
            return new LivroFrm(null, $categorias);
        },
                'Livraria\Auth\Adapter' => function($service) {
            return new AuthAdapter($service->get('Doctrine\ORM\EntityManager'));
        },
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'UserIdentity' => new View\Helper\UserIdentity()
            )
        );
    }

}
