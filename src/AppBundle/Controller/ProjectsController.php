<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Project;
use AppBundle\Form\CalendarEventType;
use AppBundle\Form\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjectsController extends DefaultController
{
    /**
     * @Route("/projects/{projectId}", name="projects", defaults={"projectId" = 0})
     * @param $projectId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function projectsAction($projectId)
    {
        /** @var \AppBundle\Repository\EventCategoryRepository $eventCategoryRepository */
        $eventCategoryRepository = $this->get('app.event_category.repository');

        /** @var \AppBundle\Entity\User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();


        /** @var \AppBundle\Entity\Project[] $projects */
        $projects = $user->getProjects();

        $selectedProject = null;
        $spendTime = null;
        $totalSpendTime = 0;

        dump($projectId);

        if(!empty($projects)){
            if($projectId == 0) {
                $selectedProject = $projects[0];
            }
            else{
                foreach ($projects as $project){
                    if ($project->getId() == $projectId){
                        $selectedProject = $project;
                    }
                }
            }
        }

        dump($selectedProject);

        if($selectedProject != null) {
            $spendTime = $eventCategoryRepository->getSpendTimeByCategoriesForUserAndProject($user->getId(), $selectedProject->getId() );
            foreach($spendTime as $category){
                $totalSpendTime += $category['time'];
            }
        }

        $addProjectForm = $this->createForm(ProjectType::class, new Project(), array(
            'action' => $this->generateUrl('add_project')
        ));
        $addProjectForm->remove('id');
        $addProjectForm->remove('calendarEvents');

        $editProjectForm = $this->createForm(ProjectType::class, $selectedProject, array(
            'action' => $this->generateUrl('edit_project')
        ));

        $calendarEventForm = $this->createForm(CalendarEventType::class, array(), array(
            'action' => $this->generateUrl('add_event'),
            'user' => $user
        ));

        $editCalendarEventForm = $this->createForm(CalendarEventType::class, array(), array(
            'action' => $this->generateUrl('edit_event'),
            'user' => $user
        ));

        return $this->render('default/projects.html.twig', array(
            'add_project_form' => $addProjectForm->createView(),
            'edit_project_form' => $editProjectForm->createView(),
            'calendar_event_form' => $calendarEventForm->createView(),
            'edit_calendar_event_form' => $editCalendarEventForm->createView(),
            'projects' => $projects,
            'selected_project' => $selectedProject,
            'spend_time' => $spendTime,
            'total_spend_time' => $totalSpendTime,
        ));
    }
}