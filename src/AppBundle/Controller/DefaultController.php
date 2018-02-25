<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount'   => '10.42',
            'currency' => 'EUR',
        ]);


        return $this->render('@App/Payment/payment_paypal.html.twig', array(
            "order" => 10.42,
            "form" => $form->createView()

        ));
    }


    /**
     * @Route("/user/{user_id}/articles", name="user_list")
     */

    public function userListAction(Request $request, $user_id){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user_id);

        //var_dump($user->getArticles()[1]->getName());

        $articles = [];
        foreach ($user->getArticles() as $article){
            $articles[] = [
                "name" => $article->getName(),
                "price" => $article->getPrice(),
                "description" => $article->getDescription(),
                "date_added" => $article->getDateAdded()
            ];
        }

        if($user){
            return new JsonResponse(array(
                'error' => false,
                'user' => [
                    "name" => $user->getName(),
                    "firstname" => $user->getFirstName(),
                    "email" => $user->getEmail(),
                    "articles" => $articles
                    ]
            ));
        }else{
            return new JsonResponse(array(
                'error' => false,
                'user' => " User not found"
            ));
        }




    }


}
