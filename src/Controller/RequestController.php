<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("api/")]
class RequestController extends AbstractController
{
    #[Route('request/{id}', name: 'app_request')]
    public function sendFriendRequest(Profile $profile, EntityManagerInterface $manager): Response
    {
        $request = new Request();
        $request->setSender($this->getUser()->getProfile());

        $request->setRecipient($profile);
        $recipent = $request->getRecipient();
        $sender = $request->getSender();
        if ($recipent === $sender){
            return $this->json("Vous ne pouvez pas envoyer une demande d'ami à vous même");
        }elseif($recipent->getFriends() != null and $sender->getFriends()!= null) {
            foreach ($recipent->getFriends() as $friend){
                foreach ($sender->getFriends() as $friend2){
                    if ($friend == $friend2){
                        return $this->json("Vous avez déjà cet ami",200);
                    }else{
                        $manager->persist($request);
                        $manager->flush();
                        return $this->json("Votre requête a bien été envoyé",200);
                    }
                }
            }
        }
        $manager->persist($request);
        $manager->flush();
        return $this->json("Votre requête a bien été envoyé à ".$request->getRecipient()->getRelatedTo()->getUsername(),200);
    }

    #[Route("request/get/{id}")]
    public function getRequestInfo(Request $request):Response{

        if ($request->getRecipient()->getRelatedTo() != $this->getUser()){
            return $this->json('Pas de requête accessible avec cet id',200);
        }

        return $this->json($request,200,[],['groups'=>"forRequest"]);
    }


    #[Route('request/accept/{id}')]
    public function acceptRequest(Request $request,EntityManagerInterface $manager):Response{

       $request->getRecipient()->addFriend($request->getSender());
       $manager->remove($request);
       $manager->flush();

       return $this->json("Vous avez ajouté ".$request->getSender()->getRelatedTo()->getUsername(),200);
    }

    #[Route('request/deny/{id}')]
    public function denyRequest(Request $request,EntityManagerInterface $manager):Response{

        $manager->remove($request);
        $manager->flush();

        return $this->json("Vous avez refusé la demande de ".$request->getSender()->getRelatedTo()->getUsername());
    }

    #[Route('request/cancel/{id}')]
    public function cancelRequest(Request $request, EntityManagerInterface $manager):Response{

        if ($request->getSender()->getRelatedTo() != $this->getUser()){
            return $this->json('Pas de requête accessible avec cet id',200);
        }
        $manager->remove($request);
        $manager->flush();

        return $this->json("La requete a bien été supprimée",200);

    }
}
