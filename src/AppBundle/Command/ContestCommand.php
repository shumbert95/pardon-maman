<?php

namespace AppBundle\Command;

use Facebook\FacebookApp;
use Facebook\FacebookRequest;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ContestCommand extends ContainerAwareCommand
{


    protected function configure()
    {
        $this->setName('pm:contest:close')
            ->setDescription('Close contest');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();

        $fb = $this->getContainer()->get('facebook_service');
        $app = $fb->getApp();
        $admins = $fb->getAdmins();
        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneBy(['status' => 1]);
////        if ($contest->getDateEnd()->format('Y-m-d') == date('Y-m-d')) {
            $winner = $doctrine->getRepository('AppBundle:ContestParticipant')->getContestWinner($contest);
//
            $winner = $doctrine->getRepository('AppBundle:ContestParticipant')->find($winner[0]->getId());
//            $contest->setWinner($winner);
//            $em->flush();
//            $photo = $winner->getPhoto();
//        $fbApp = new FacebookApp($this->getContainer()->getParameter('appId'), $this->getContainer()->getParameter('appSecret'));
//            foreach ($contest->getContestParticipants() as $contestParticipant) {
//                $album_details = array(
//                    'message'=> 'Le concours auquel j\'ai participé est terminé, voici la photo du gagnant !',
//                    'link' => $this->getContainer()->get('router')->generate('photo_display', array('facebookId' =>$photo->getFacebookId()), 0),
//                    'object_attachment' => $photo->getFacebookId(),
//                    'access_token' => $app->getApp()->getAccessToken()->getValue()
//                );
////                $post_message = $app->post('/'.$contestParticipant->getParticipant()->getUser()->getFacebookId().'/feed', $album_details);
//                try{
//
//                }catch (\Exception $e) {
//                    var_dump($e);
//                }
//                $post_message = $app->post('/117131608805366/feed', $album_details);
//
//            }

            $this->sendMail($admins, $winner, $contest);
//        }

    }

    /**
     * @param $user
     * @param $exception
     */
    private function sendMail($admins, $winner, $contest)
    {
        foreach ($admins as $admin) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Concours terminé')
                ->setFrom('shumbert@kernix.com')
                ->setTo($admin->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/contest_end.html.twig',
                        array('contestName' => $contest->getName(), 'participantFirstName' => $winner->getFirstName(), 'participantLastName' => $winner->getLastName())
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);
        }
    }
}