<?php

namespace Pages\PagesBundle\Form\Handler;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Form;

abstract class BaseHandler
{
    protected $request;
    protected $security;
    protected $container;
    protected $type;
    protected $formDelete;

    /**
     * calls: - [setRequest, ['@service_container']]
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container){
        $this->security = $container->get('security.token_storage');
        $this->request = $container->get('request_stack')->getCurrentRequest();
        $this->container = $container;

    }

    /**
     * @return bool
     */
    private function process(){
        $this->form->handleRequest($this->request);
        if($this->request->isMethod('post') && $this->form->isValid()){
            $this->onSuccess();
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @param $entity
     * @return bool
     */
    public function isEdit($entity){
        $this->createForm($entity);
        $this->form->add('submit', SubmitType::class, array('label' => 'Update'));
        return $this->process();
    }

    /**
     * @return bool
     */
    public function isCreated(){
        $this->form->add('submit', SubmitType::class, array('label' => 'Add'));
        return $this->process();
    }

    public function isDelete($entity){
        if(is_object($entity)){
            $this->onDelete($entity);
            return true;
        }
        else return false;
    }

    /**
     * @return mixed
     */
    public function getFrom(){
        return $this->form;
    }

    /**
     * @return mixed
     */
    public function createView(){
        return $this->form->createView();
    }

    /**
     * @param $entity
     */
    private function createForm($entity){
        $this->form = $this->container->get('form.factory')->create($this->type, $entity);
    }

    public function createDeleteForm($action = null){
        $formConfig =  $this->container->get('form.factory')->createBuilder(FormType::class)
            ->setAction($action)
            ->setMethod('DELETE')
            ->getFormConfig()
            ;
        $this->formDelete =  new Form($formConfig);
        return $this->formDelete->add('submit', SubmitType::class, array('label' => 'Delete'));
    }

    public function createDeleteView(){
        return $this->formDelete->createView();
    }

    public function onDelete($entity){
        $this->pageManager->doRemove($entity);
    }
    /**
     * @return mixed
     */
    public abstract function onSuccess();

}