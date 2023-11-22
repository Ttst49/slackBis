<?php

namespace App\Controller;

use App\Entity\PrivateConversation;
use App\Entity\PrivateMessage;
use App\Repository\ImageRepository;
use App\Service\ImagePostProcessing;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/api/private/message')]
class PrivateMessageController extends AbstractController
{
    #[Route('/send/{id}', name: 'app_private_message')]
    public function sendPrivateMessage(PrivateConversation $privateConversation,
                                       SerializerInterface $serializer,
                                       Request $request,
                                       EntityManagerInterface $manager,
                                       ImagePostProcessing $postProcessing,
                                       UploaderHelper $uploaderHelper,
                                       CacheManager $cacheManager,
                                       ImageRepository $repository
    ): Response
    {
        if ($this->getUser() === $privateConversation->getRelatedToProfileB()->getRelatedTo()
            or $this->getUser() === $privateConversation->getRelatedToProfileA()->getRelatedTo()){
            $privateMessage = $serializer->deserialize($request->getContent(),PrivateMessage::class,"json");

            $imageIdsArray = $privateMessage->getAssociatedImages();
            if ($imageIdsArray){
                $newImages = $postProcessing->getImagesFromIds($imageIdsArray);
                foreach ($newImages as $image){
                    $privateMessage->addImage($image);
                }
            }

            $privateMessage->setAuthor($this->getUser()->getProfile());
            $privateMessage->setAssociatedToConversation($privateConversation);
            $manager->persist($privateMessage);
            $manager->flush();

            return $this->json($privateMessage,200,[],["groups"=>"forPrivateConversation"]);
        }

        return $this->json("Vous ne pouvez pas faire cela",200);
    }

    #[Route('/remove/{id}',methods: "DELETE")]
    public function removePrivateMessage(PrivateMessage $privateMessage,EntityManagerInterface $manager):Response{

        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()){
            $manager->remove($privateMessage);
            $manager->flush();
            return $this->json("Votre message a bien été supprimé",200);
        }
        return $this->json("Vous n'êtes pas l'auteur de ce message",200);
    }


    #[Route('/edit/{id}',methods: 'PUT')]
    public function editPrivateMessage(SerializerInterface $serializer, PrivateMessage $privateMessage,EntityManagerInterface $manager, Request $request):Response{

        if ($privateMessage->getAuthor() == $this->getUser()->getProfile()){
            $privateMessage->setDate(new \DateTime());
            $privateMessageDeserialized = $serializer->deserialize($request->getContent(),PrivateMessage::class,"json",array("object_to_populate"=>$privateMessage));
            $manager->persist($privateMessageDeserialized);
            $manager->flush();
            return $this->json($privateMessageDeserialized,200);
        }
        return $this->json("Vous ne pouvez pas modifier un message dont vous n'êtes pas l'auteur",200);
    }


    #[Route('/show/{id}',methods: "GET")]
    public function showPrivateMessage(PrivateMessage $privateMessage, ImagePostProcessing $postProcessing):Response{

            $postProcessing->getImagesUrlFromImages($privateMessage);



        return $this->json($privateMessage,200,[],["groups"=>"forShowingPrivateMessage"]);
    }
}
