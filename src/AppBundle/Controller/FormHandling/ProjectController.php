<?php


namespace AppBundle\Controller\FormHandling;


use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/add_project", name="add_project", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addProject(Request $request)
    {
        $projectRepository = $this->get('app.project.repository');
        $validator = $this->get('validator');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $project = new Project();
        $project->setUser($user);
        $addProjectForm = $this->createForm(ProjectType::class, $project);
        $addProjectForm->remove('id');

        $addProjectForm->handleRequest($request);
        if ($addProjectForm->isSubmitted()) {
            if($addProjectForm->isValid()) {
                try {
                    $projectRepository->add($project);
                    $projectRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to create project!'
                    );
                }
                $this->addFlash(
                    'success',
                    'Project successfully added!'
                );
                return $this->redirectToRoute('projects');
            }
            else {
                $errors = $validator->validate($project);
                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to add project: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('projects');
    }

    /**
     * @Route("/edit_project", name="edit_project", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editProject(Request $request)
    {
        $projectRepository = $this->get('app.project.repository');
        $validator = $this->get('validator');
        
        $formData = $request->request->get('project');
        try {
            /** @var \AppBundle\Entity\TodoList $list */
            $project = $projectRepository->get($formData['id']);
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to edit project!'
            );
            return $this->redirectToRoute('projects', array('projectId' => $formData['id']));
        }


        $editProjectForm = $this->createForm(ProjectType::class, $project);

        $editProjectForm->handleRequest($request);
        if ($editProjectForm->isSubmitted()) {
            if($editProjectForm->isValid()) {
                try {
                    $projectRepository->add($project);
                    $projectRepository->save();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to edit project!'
                    );
                    return $this->redirectToRoute('projects', array('projectId' => $formData['id']));
                }
                $this->addFlash(
                    'success',
                    'Project successfully edited!'
                );
            }
            else {
                $errors = $validator->validate($project);
                foreach ($errors as $error){
                    $this->addFlash(
                        'error',
                        'Unable to add project: ' . $error->getMessage()
                    );
                }
            }
        }

        return $this->redirectToRoute('projects', array('projectId' => $formData['id']));
    }

    /**
     * @Route("/delete_project/{projectId}", name="delete_project", options = { "expose" = true })
     * @param integer $projectId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteList($projectId)
    {
        /** @var \AppBundle\Repository\TodoListRepository $todoListRepository */
        $projectRepository = $this->get('app.project.repository');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        try {
            /** @var \AppBundle\Entity\TodoList $list */
            $project = $projectRepository->get($projectId);
            if($project->getUser() === $user) {
                $projectRepository->remove($project);
                $projectRepository->save();
            }
            else{
                throw new \Exception();
            }
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                'Unable to delete project!'
            );
            return $this->redirectToRoute('projects');
        }
        $this->addFlash(
            'success',
            'Project successfully deleted!'
        );

        return $this->redirectToRoute('projects');
    }
}