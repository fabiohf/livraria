<?php

namespace LivrariaAdmin\Controller;

class CategoriasController extends CrudController
{

    public function __construct()
    {

        $this->service = 'Livraria\Service\Categoria';
        $this->entity = 'Livraria\Entity\Categoria';
        $this->form = 'LivrariaAdmin\Form\Categoria';
        $this->route = 'livraria-admin';
        $this->controller = 'categorias';
    }

}
