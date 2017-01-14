<?php


namespace AppBundle\Controller;


use AppBundle\Form\EventCategoryType;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TimeLogController extends DefaultController
{
    /**
     * @Route("/time_log", name="time_log")
     */
    public function timeLogAction(Request $request)
    {

        $weatherService = $this->get('app.weather.api.service');

        $weather = $weatherService->getByIp($this->get('request_stack')->getMasterRequest()->getClientIp());

//        dump($weather); die;
        

        $eventCategoryRepository = $this->get('app.event_category.repository');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $categories = $eventCategoryRepository->allForUser($user);

        $daterangeForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('time_log'))
            ->add('daterange', HiddenType::class, array(
                'attr' => array('id' => 'daterange_picker', 'class' => 'form-control')
                )
            )
            ->getForm();

        $editCategoryForm = $this->createForm(EventCategoryType::class, array(), array(
            'action' => $this->generateUrl('edit_category')
        ));

        $daterangeForm->handleRequest($request);

        if ($daterangeForm->isSubmitted() && $daterangeForm->isValid()) {
            $data = $daterangeForm->getData();

//            dump($data); die;

            $dates = explode("-", $data["daterange"]);
            $startDate = date_time_set(date_create_from_format('d/m/Y' ,trim($dates[0])), 0, 0, 0);
            $endDate = date_time_set(date_create_from_format('d/m/Y' ,trim($dates[1])), 23, 59 ,59);

//            dump($endDate);die;

            $spendTime = $eventCategoryRepository->getSpendTimeByCategoriesForUser($user->getId(), $startDate, $endDate);
        }
        else{
            $spendTime = $eventCategoryRepository->getSpendTimeByCategoriesForUser($user->getId());
        }

        return $this->render('default/timeLog.html.twig', array(
            'category_form' => $editCategoryForm->createView(),
            'daterange_form' => $daterangeForm->createView(),
            'categories' => $categories,
            'spend_time' => $spendTime,
            'weather' => $weather,
        ));
    }
}