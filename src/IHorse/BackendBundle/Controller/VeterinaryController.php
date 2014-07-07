<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\VeterinaryType;
use IHorse\BackendBundle\Form\Search\DentalSearchTimeType;
use Symfony\Component\HttpFoundation\Request;

class VeterinaryController extends IHorseController
{
    public function listVeterinariesAction()
    {
        $session = $this->getRequest()->getSession();
        $veterinaries = $this->get('rest.handler.model')->getList('veterinaries', 'veterinaries', $session->get('access_token'));
        $overAllDates = $this->countDentalsFromAllVeterinaries($veterinaries, null, null);

        return $this->render('BackendBundle:Veterinary:index.html.twig', array('veterinaries' => $veterinaries,
                                                                               'overAllDentals' => $overAllDates['dentalsPerDay'],
                                                                               'totalDentals' => $overAllDates['totalDentals'],
                                                                               'veterinariesWithDentals' => $overAllDates['veterinariesWithDentals'],
                                                                               'veterinariesDates' => $overAllDates['veterinariesDates']));
    }

    public function veterinaryShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->get('veterinaries/'.$id, 'veterinary', $session->get('access_token'));
        $countDentals = $this->countActualDentals($veterinary);

        return $this->render('BackendBundle:Veterinary:view.html.twig', array('veterinary' => $veterinary,
                                                                              'countDentals' => $countDentals['countDental'],
                                                                              'totalFromProduct' => $countDentals['totalDentalFromProduct']));
    }

    public function newVeterinaryAction()
    {
        $form = $form = $this->createForm(new VeterinaryType());

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView()));
    }

    public function editVeterinaryAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->get('veterinaries/'.$id, 'veterinary', $session->get('access_token'));
        $form = $form = $this->createForm(new VeterinaryType(), $veterinary);

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createVeterinaryAction()
    {
        $params = $this->getRequest()->request->get('veterinary');
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->post("veterinaries", array('veterinary' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function putVeterinaryAction($id)
    {
        $params = $this->getRequest()->request->get('veterinary');
        $session = $this->getRequest()->getSession();
        if ($params['password'] == "") {
            $veterinary = $this->get('rest.handler.model')->patch("veterinaries/".$id, array('veterinary' => $params), $session->get('access_token'));
        } else {
            $veterinary = $this->get('rest.handler.model')->put("veterinaries/".$id, array('veterinary' => $params), $session->get('access_token'));
        }

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function deleteVeterinaryAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->delete("veterinaries/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function graphicVeterinaryAction(Request $request)
    {
        $form = $this->createForm(new DentalSearchTimeType());
        $form->handleRequest($request);
        $date = $form->getData();
        $session = $this->getRequest()->getSession();
        if ($request->request->get('selectVeterinary')) {
            $id = $request->request->get('selectVeterinary');
            $veterinary = $this->get('rest.handler.model')->get('veterinaries/'.$id, 'veterinary', $session->get('access_token'));
            $generalData = $this->countDentals($veterinary, $date['from'], $date['to']);
        } else {
            $veterinary = null;
            $generalData['countDental'] = null;
            $generalData['arrayData'] = null;
            $generalData['dateFrom'] = null;
            $generalData['dateTo'] = null;
        }

        $veterinaries = $this->get('rest.handler.model')->getList('veterinaries', 'veterinaries', $session->get('access_token'));
        $overAllData = $this->countDentalsFromAllVeterinaries($veterinaries, $date['from'], $date['to']);

        return $this->render('BackendBundle:Veterinary:graphics.html.twig', array('veterinary' => $veterinary,
                                                                                  'generalGraphics' => $generalData['arrayData'],
                                                                                  'overAllData' => $overAllData,
                                                                                  'veterinaryDentals' => $generalData['countDental'],
                                                                                  'totalDentals' => $overAllData['totalDentals'],
                                                                                  'dateFrom' => $generalData['dateFrom'],
                                                                                  'dateTo' => $generalData['dateTo'],
                                                                                  'formulario' => $form->createView(),
                                                                                  'countTotalDentalsInDate' => $overAllData['countTotalDentalsInDate'],
                                                                                  'veterinaries' => $veterinaries));
    }

    public function graphicVeterinariesAction(Request $request, $id = null)
    {
        if ($request->request->get('selectVeterinary') != "")
        {
            $id = $request->request->get('selectVeterinary');
        }
        $form = $this->createForm(new DentalSearchTimeType());
        $form->handleRequest($request);

        return $this->redirect($this->generateUrl('show_dental_graphics', array('id' => $id, 'formulario' => $form->createView())));
    }

    private function countActualDentals($veterinary)
    {
        $count = 0;

        $products = $veterinary["clinic"]["products"];
        if (count($products) == 0) {
            return array('countDental' => null, 'totalDentalFromProduct' => null, 'productExpired' => null);
        }
        $product = $products[count($products)-1];
        $dateCreated = new \DateTime($product["created"]);
        $dateCreatedDay = $dateCreated->format('d');
        $today = new \DateTime('now');
        $strDate = $today->format('m').'/'.$dateCreatedDay.'/'.$today->format('Y');
        $firstDate = new \DateTime($strDate);
        if ($dateCreated->format('d') > $today->format('d')) {
            $firstDate->modify('-1 month');
        }
        $finalDate = new \Datetime($firstDate->format('m/d/Y'));
        $finalDate->modify('+1 month');
        $dentals = $veterinary["dentals"];
        foreach ($dentals as $dental) {
            $createDentalDate = new \DateTime($dental["created"]);
            if ($createDentalDate->format('Y/m/d') > $firstDate->format('Y/m/d') && $createDentalDate->format('Y/m/d') < $finalDate->format('Y/m/d')) {
                $count++;
            }
        }
        $expired = new \DateTime($product['expired']);

        return array('countDental' => $count, 'totalDentalFromProduct' => $product['product']['charts'], 'productExpired' => $expired->format('Y/m/d'), 'product' => $product['product']);
    }

    private function countDentals($veterinary, $dateFrom = null, $dateTo = null)
    {
        if ($dateFrom) {
            $createAccountDate = new \Datetime($dateFrom);
        } else {
            $createAccount = $veterinary['created'];
            $createAccountDate = new \DateTime($createAccount);
        }
        if ($dateTo) {
            $dateTo = new \DateTime($dateTo);

        } else {
            $dateTo = new \DateTime('now');
            $dateTo->modify('last day of this month');
        }
        $arrayInitial = array();
        $dentals = $veterinary["dentals"];
        $countDental = 0;
        foreach ($dentals as $dental) {
            $dateCreated = new \DateTime($dental["created"]);
            if ($dateCreated->format('Y/m/d') >= $createAccountDate->format('Y/m/d') && $dateCreated->format('Y/m/d') <= $dateTo->format('Y/m/d')) {
                $countDental++;
                if (array_key_exists($dateCreated->format('Y/m/01'), $arrayInitial)) {
                    $arrayInitial[$dateCreated->format('Y/m/01')] = $arrayInitial[$dateCreated->format('Y/m/01')]+1;
                } else {
                    $arrayInitial[$dateCreated->format('Y/m/01')] = 1;
                }
            }
        }
        $dateArray = new \DateTime($createAccountDate->format('m').'/01/'.$createAccountDate->format('Y'));
        $return[$dateArray->format('Y/m')] = 0;
        while ($dateArray->format('Y/m/d') <= $dateTo->format('Y/m/d')) {
            if (array_key_exists($dateArray->format('Y/m/d'), $arrayInitial)) {
                $lastDay = $dateArray;
                $lastDay->modify('last day of this  month');
                $return[$lastDay->format('Y/m')] = $arrayInitial[$dateArray->format('Y/m/01')];
            } else {
                $lastDay = $dateArray;
                $lastDay->modify('last day of this  month');
                $return[$lastDay->format('Y/m')] = 0;
            }
            $dateArray->modify('+1 day');
        }

        return array('arrayData' => $return, 'dateFrom' => $createAccountDate, 'dateTo' => $dateTo, 'countDental' => $countDental);
    }

    private function countDentalsFromAllVeterinaries($veterinaries, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $createAccountDate = new \Datetime($dateFrom);
        } else {
            $createAccount = $this->container->getParameter('initDateApp');
            $createAccountDate = new \DateTime($createAccount);
        }

        if ($dateTo) {
            $dateTo = new \DateTime($dateTo);

        } else {
            $dateTo = new \DateTime('now');
            $dateTo->modify('last day of this month');
        }
        $arrayInitial = array();
        $countTotalDentals = 0;
        $countTotalDentalsInDate = 0;
        $countVeterinariesWithDentals = 0;
        $veterinariesDates = array();
        foreach ($veterinaries as $veterinary) {
            $veterinariesDates[$veterinary['id']] = $this->countActualDentals($veterinary);
            $dentals = $veterinary['dentals'];
            $countTotalDentals += count($dentals);
            if (count($dentals) > 0) {
                $countVeterinariesWithDentals++;
            }
            foreach ($dentals as $dental) {
                $dateCreated = new \DateTime($dental["created"]);
                if ($dateCreated->format('Y/m/d') >= $createAccountDate->format('Y/m/d') && $dateCreated->format('Y/m/d') <= $dateTo->format('Y/m/d')) {
                    $countTotalDentalsInDate++;
                    if (array_key_exists($dateCreated->format('Y/m/01'), $arrayInitial)) {
                        $arrayInitial[$dateCreated->format('Y/m/01')] = $arrayInitial[$dateCreated->format('Y/m/01')]+1;
                    } else {
                        $arrayInitial[$dateCreated->format('Y/m/01')] = 1;
                    }
                }
            }
        }
        $dateArray = new \DateTime($createAccountDate->format('m').'/01/'.$createAccountDate->format('Y'));
        $dentalsPerDay[$dateArray->format('Y/m')] = 0;
        while ($dateArray->format('Y/m/d') <= $dateTo->format('Y/m/d')) {
            if (array_key_exists($dateArray->format('Y/m/d'), $arrayInitial)) {
                $lastDay = $dateArray;
                $lastDay->modify('last day of this  month');
                $dentalsPerDay[$lastDay->format('Y/m')] = $arrayInitial[$dateArray->format('Y/m/01')];
            } else {
                $lastDay = $dateArray;
                $lastDay->modify('last day of this  month');
                $dentalsPerDay[$lastDay->format('Y/m')] = 0;
            }
            $dateArray->modify('+1 day');
        }
        $return = array('dentalsPerDay' => $dentalsPerDay, 'totalDentals' => $countTotalDentals,
                        'veterinariesWithDentals' => $countVeterinariesWithDentals,
                        'veterinariesDates' => $veterinariesDates,
                        'countTotalDentalsInDate' => $countTotalDentalsInDate);

        return $return;
    }
}