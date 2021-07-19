<?php

namespace Alura\Cursos\Controller;

class Deslogar implements ControladorRequisicao
{
    public function processaRequisicao(): void
    {
        session_destroy();
        header('Location: /login');
    }
}
