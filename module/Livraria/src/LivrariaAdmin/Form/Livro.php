<?php

namespace LivrariaAdmin\Form;

use Zend\Form\Form,
    Zend\Form\Element\Select;

class Livro extends Form
{

    protected $categorias;

    public function __construct($name = null, array $categorias = null)
    {
        $this->categorias = $categorias;

        parent::__construct('livro');
        $this->setAttribute('method', 'post');
        #$this->setInputFilter(new CategoriaFilter());

        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden'
            )
        ));

        $this->add(array(
            'name' => 'nome',
            'options' => array(
                'type' => 'text',
                'label' => 'Nome',
            ),
            'attributes' => array(
                'id' => 'nome',
                'placeholder' => 'Informe o nome'
            )
        ));

        $categoria = new Select();
        $categoria->setLabel('Categoria')
                ->setName('categoria')
                ->setOptions(array('value_options' => $this->categorias));

        $this->add($categoria);

        $this->add(array(
            'name' => 'isbn',
            'options' => array(
                'type' => 'text',
                'label' => 'ISBN',
            ),
            'attributes' => array(
                'id' => 'nome',
                'placeholder' => 'Informe o ISNB'
            )
        ));

        $this->add(array(
            'name' => 'autor',
            'options' => array(
                'type' => 'text',
                'label' => 'Autor',
            ),
            'attributes' => array(
                'id' => 'autor',
                'placeholder' => 'Informe o autor'
            )
        ));

        $this->add(array(
            'name' => 'valor',
            'options' => array(
                'type' => 'text',
                'label' => 'Valor',
            ),
            'attributes' => array(
                'id' => 'valor',
                'placeholder' => 'Informe o valor'
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Salvar',
                'class' => 'btn-success'
            )
        ));
    }

}
