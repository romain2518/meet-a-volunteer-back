<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

    /**
	 * @var UserRepository
	 */
	private $userRepo;

	/**
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack, UserRepository $userRepo)
	{
		$this->requestStack = $requestStack;
		$this->userRepo = $userRepo;
	}

	/**
	 * @param JWTCreatedEvent $event
	 *
	 * @return void
	 */
	public function onJWTCreated(JWTCreatedEvent $event)
	{
        $user = $event->getUser();
        $userByEntity = $this->userRepo->findOneBy(['pseudo' => $user->getUserIdentifier()]);
        

		$payload = $event->getData();
        $payload['id'] = $userByEntity->getId();
        $payload['profilePicture'] = $userByEntity->getProfilePicture();

		$event->setData($payload);

		$header = $event->getHeader();
		$header['cty'] = 'JWT';

		$event->setHeader($header);
	}
}