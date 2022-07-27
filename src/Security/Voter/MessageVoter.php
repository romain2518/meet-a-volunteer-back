<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageVoter extends Voter
{
    public const EDIT = 'MESSAGE_EDIT';
    public const VIEW = 'MESSAGE_VIEW';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && (
                $subject instanceof \App\Entity\Message && $attribute === self::EDIT
                || $subject instanceof \App\Entity\User && $attribute === self::VIEW
            );
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                /** @var Message $subject */
                return $user === $subject->getUserSender(); //return true if the sender of the requested message is the one logged in

                break;
            case self::VIEW:
                /** @var User $subject */
                return $user === $subject; //return true if the requested user is the one logged in

                break;
        }

        return false;
    }
}
