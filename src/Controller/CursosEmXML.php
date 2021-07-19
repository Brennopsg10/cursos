<?php

namespace Alura\Cursos\Controller;

use SimpleXMLElement;
use Nyholm\Psr7\Response;
use Alura\Cursos\Entity\Curso;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CursosEMXML implements RequestHandlerInterface
{
    private ObjectRepository $repositorioDeCursos;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioDeCursos = $entityManager->getRepository(Curso::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Curso[] $cursos*/
        $cursos = $this->repositorioDeCursos->findAll();

        $cursosEmXml = new SimpleXMLElement('<cursos/>');

        foreach ($cursos as $curso) {
            $cursoEmXml = $cursosEmXml->addChild('curso');
            $cursoEmXml->addChild('id', $curso->getId());
            $cursoEmXml->addChild('descricao', $curso->getDescricao());
        }

        //buscar dados do banco
        $cursos = $this->repositorioDeCursos->findAll();


        return new Response(200, ['Content-Type' => 'application/xml'], $cursosEmXml->asXML());
    }
}
