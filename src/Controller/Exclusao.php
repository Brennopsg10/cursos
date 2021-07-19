<?php

namespace Alura\Cursos\Controller;

use Alura\Cursos\Entity\Curso;
use Alura\Cursos\Helper\FlashMessageTrait;
use Alura\Cursos\Infra\EntityManagerCreator;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Exclusao implements RequestHandlerInterface
{
    use FlashMessageTrait;

    /**
     * Undocumented variable
     *
     * @var \Doctrine\ORM\EntityManagerIterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $queryString = $request->getQueryParams();
        $id = filter_var($queryString['id'], FILTER_VALIDATE_INT);

        $resposta = new Response(302, ['Location' => '/listar-cursos']);
        
        if (is_null($id) || $id === false) {
            $this->defineMensagem('danger', 'Curso inexistente');

            return  $resposta;
        }

        $curso = $this->entityManager->getReference(Curso::class, $id);
        $this->entityManager->remove($curso);
        $this->entityManager->flush();
        $this->defineMensagem('danger', 'Curso excluido com sucesso');

        return $resposta;
    }
}
