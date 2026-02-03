<?php

namespace Acme\Bundle\CustomerNotesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Acme\Bundle\CustomerNotesBundle\Entity\CustomerNote;
use Acme\Bundle\CustomerNotesBundle\Form\Type\CustomerNoteType;
use Doctrine\Persistence\ManagerRegistry;
use Oro\Bundle\SecurityBundle\Attribute\AclAncestor;
#[Route('/customer-note')]
class CustomerNoteController extends AbstractController
{
    #[Route('/', name: 'acme_customer_note_index')]
    #[AclAncestor('acme_customer_note_view')]
    public function index()
    {
        return $this->render('@AcmeCustomerNotes/CustomerNote/index.html.twig');
    }

    #[Route('/create', name: 'acme_customer_note_create')]
    #[AclAncestor('acme_customer_note_create')]
    public function create(Request $request, ManagerRegistry $doctrine)
    {
        return $this->update(new CustomerNote(), $request, $doctrine);
    }

    #[Route('/update/{id}', name: 'acme_customer_note_update', requirements: ['id' => '\d+'])]
    #[AclAncestor('acme_customer_note_update')]
    public function update(CustomerNote $entity, Request $request, ManagerRegistry $doctrine)
    {
        $form = $this->createForm(CustomerNoteType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('acme_customer_note_index');
        }

        return $this->render('@AcmeCustomerNotes/CustomerNote/update.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/view/{id}', name: 'acme_customer_note_view', requirements: ['id' => '\d+'])]
    #[AclAncestor('acme_customer_note_view')]
    public function view(CustomerNote $entity)
    {
        return $this->render('@AcmeCustomerNotes/CustomerNote/view.html.twig', [
            'entity' => $entity,
        ]);
    }

    #[Route('/delete/{id}', name: 'acme_customer_note_delete', requirements: ['id' => '\d+'], methods: ['DELETE', 'POST'])]
    #[AclAncestor('acme_customer_note_delete')]
    public function delete(CustomerNote $entity, Request $request, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();
        $em->remove($entity);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['success' => true]);
        }

        return $this->redirectToRoute('acme_customer_note_index');
    }
}
