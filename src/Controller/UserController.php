<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\HttpFoundation\Request;




class UserController extends AbstractController
{
    
	/**
     * @Route("/adduser", name="add_user")
     */
	
	public function adduser(Request $request)
    {
        // creates a task and gives it some dummy data for this example
		$msg = '';
        $user = new User();
        //$task->setFirstName('First Name');
		//$task->setLastName('Last Name');

        $form = $this->createFormBuilder($user)
            ->add('firstName', TextType::class, array('label' => 'First Name'))
			->add('lastName', TextType::class, array('label' => 'Last Name'))
            ->add('save', SubmitType::class, array('label' => 'Create User')) // I usually code this into where the form is rendered so it can be translated correctly
            ->getForm();
	    // Does same now $form = $this->createForm(UserType::class, $user);
		
			$form->handleRequest($request);
		  
			if ($form->isSubmitted() && $form->isValid())
			{
				// $form->getData() holds the submitted values
				// but, the original `$task` variable has also been updated
				$user = $form->getData();

				// ... perform some action, such as saving the task to the database
				// for example, if Task is a Doctrine entity, save it!
				 $entityManager = $this->getDoctrine()->getManager();
				 $entityManager->persist($user);
				 $entityManager->flush();

				$this->addFlash('success', 'User successfully added!'); // this "bag" holds messages of types until they are read. Usually on the next page render.
				return $this->redirectToRoute('show_user');
				
				$msg = 'User successfully added'; // This never happens, but there is a better way of sending messages through sessions, called flashes. See above.
			}
	
        return $this->render('user/adduser.html.twig', array(
            'form' => $form->createView(),'msg' => $msg
        ));
		
		
    }
	
	/**
     * @Route("/edituser/{id}", name="edit_user")
     */
	public function edituser(Request $request,$id = 1)
	{
		$msg = ''; // Must init $msg if it will be sent in all cases.
		$entityManager = $this->getDoctrine()->getManager();
		$user = $entityManager->getRepository(User::class)->find($id);
		
		if (!$user) {
			throw $this->createNotFoundException(
				'No user found for id '.$id
			);
		}
		 $form = $this->createFormBuilder($user)
			->add('firstName', TextType::class, array('label' => 'First Name'))
			->add('lastName', TextType::class, array('label' => 'Last Name'))
            ->add('save', SubmitType::class, array('label' => 'Create User'))
            ->getForm();
		// Does same now $form = $this->createForm(UserType::class, $user);

			$form->handleRequest($request);
			if ($form->isSubmitted() && $form->isValid())
			{
				// $form->getData() holds the submitted values
				// but, the original `$task` variable has also been updated
				$user = $form->getData();
				
				// ... perform some action, such as saving the task to the database
				// for example, if Task is a Doctrine entity, save it!
				$entityManager = $this->getDoctrine()->getManager();
				 $entityManager->persist($user);
				 $entityManager->flush();
				
				//return $this->redirectToRoute('user_success');
				
				$msg = 'User successfully added';
				
				return $this->redirectToRoute('show_user', [
					'id' => $user->getId()
				]);
			} 
			

		
		return $this->render('user/edituser.html.twig', array(
            'form' => $form->createView(),'msg' => $msg
        ));
		
	
		
	}
	
	/**
     * @Route("/deleteuser/{id}", name="delete_user")
     */
	public function deleteuser(Request $request,$id)
	{
		$entityManager = $this->getDoctrine()->getManager();
		$user = $entityManager->getRepository(User::class)->find($id);

		// Confirm $user exists for some error handling. You can even handle this with a simple RESTful DELETE method
		// with csrf protection. To see how, I recommend running 'php bin/console make:crud' on your Task entity to see the generated code.
		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($user);
		$entityManager->flush();
				 
		return $this->redirectToRoute('show_user', ['id' => $user->getId()]); // Show user does not expect a parameter for ID. Plus, That user was removed.
	}	
	
	
	
	/**
     * @Route("/showuser", name="show_user")
     */
	public function showuser()
	{
		$users = $this->getDoctrine()
			->getRepository(User::class)
			->findAll();

		if (!$users) {
			return $this->redirectToRoute('add_user'); // perhaps better to redirect if none exist.
			throw $this->createNotFoundException(
				'No user found'
			);
		}

		return $this->render('user/showuser.html.twig', ['users' => $users]);
	}
	
	
}