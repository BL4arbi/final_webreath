<?php

namespace App\Controller;

use App\Entity\Module;
use App\Repository\ModuleDataRepository;
use App\Repository\ModuleRepository;
use App\Services\FactoryModuleData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApplicationController extends AbstractController
{
    #[Route('/', name: 'app_application')]
    public function index(ModuleRepository $repository): Response
    {
        $listModule = $repository->findAll();
        $data = random_int(20, 38);
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController',
            'data' => $data,
            'listModule' => $listModule
        ]);
    }

    #[Route('/form', name: 'form')]
    public function formNewModule(): Response
    {
        return $this->render("application/form.html.twig");
    }

    #[Route('/newModule', name: 'creationmodule', methods: ["POST"])]
    public function newModule(ModuleRepository $repository, Request $request)
    {

        $tab = $request->request;
        $module = new Module();
        $module->setName($tab->get("nameModule"));
        $module->setDetail($tab->get("detailModule"));
        $repository->save($module, true);
        return $this->redirect("/");

    }

    #[Route('/detail/{id}', name: 'detailModule')]
    public function detailModule(ModuleRepository $repository, string $id, ModuleDataRepository $dataRepository, FactoryModuleData $factoryModuleData)
    {

        $detail = $repository->find($id);

        $factoryModuleData->create(10, $detail, $dataRepository, "Température");

        return $this->render("application/detail.html.twig", ['detail' => $detail]);
    }


    #[Route("/accueil", name: "app_accueil")]
    public function accueil(): Response
    {
        return $this->render('accueil.html.twig');
    }

    #[Route("/generate-data/{id}", name: "generatedata", methods: ["POST"])]
    public function generateData($id, ModuleDataRepository $moduleDataRepository, ModuleRepository $moduleRepository)
    {
        $module = $moduleRepository->find($id);

        if (!$module) {
            return new Response('Module not found', Response::HTTP_BAD_REQUEST);
        }

        $service = new FactoryModuleData();
        // Créez les trois types de ModuleData ici

        $data[] = $service->create(random_int(1,5), $module, $moduleDataRepository, 'Température');
        $data[] = $service->create(random_int(1,5), $module, $moduleDataRepository, 'Vitesse');
        $data[] = $service->create(random_int(1,5), $module, $moduleDataRepository, 'Passagers');


        return new JsonResponse(json_encode($data), Response::HTTP_OK, [], true);
    }
    #[Route("/stopp/{id}", name: "stopp", methods: ["POST"])]
    public function stopp($id, ModuleRepository $moduleRepository)
    {
        $module = $moduleRepository->find($id);

        if (!$module) {
            return new JsonResponse(['message' => 'Module not found'], Response::HTTP_BAD_REQUEST);
        }

        $module->setStatut(false);
        $moduleRepository->save($module, true);

        return new JsonResponse(['message' => 'Module status updated']);
    }

    #[Route("/marche/{id}", name: "marche", methods: ["POST"])]
    public function marche($id, ModuleRepository $moduleRepository)
    {
        $module = $moduleRepository->find($id);

        if (!$module) {
            return new JsonResponse(['message' => 'Module not found'], Response::HTTP_BAD_REQUEST);
        }

        $module->setStatut(true);
        $moduleRepository->save($module, true);

        return new JsonResponse(['message' => 'Module status updated to ON']);
    }



    #[Route("/module/delete/{id}", name:"deleteModule")]
    public function deleteModule($id, ModuleRepository $moduleRepository): Response
    {
        $module = $moduleRepository->find($id);

        if (!$module) {
            throw $this->createNotFoundException('Aucun module trouvé pour l\'id ' . $id);
        }
        $moduleRepository->delete($module,true);

        return $this->redirectToRoute('app_application');
    }


}

