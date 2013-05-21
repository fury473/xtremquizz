<?php

namespace Metinet\XtremQUIZZBundle\Manager;

use Doctrine\ORM\EntityManager;

class FbUserManager
{
    protected $em;
    protected $entityName;
    protected $repository;
    protected $facebook;

    public function __construct(EntityManager $em, $facebook)
    {
        $this->em       = $em;
        $this->facebook = $facebook;
    }
    
    public function getRepository($entity)
    {
        return $this->em->getRepository('MetinetXtremQUIZZBundle:'.$entity);
    }

    public function createUserFromUid($fbId)
    {
        $now = date('Y-m-d H:i:s');

        $dbal = $this->em->getConnection();

        $myNull = null;
         $user = array(
            'id'            => null,
            'fb_uid'         => $fbId,
            'firstname'     => null,
            'lastname'      => null,
            'picture'       => "https://graph.facebook.com/".$fbId."/picture",
            'created_at'    => $now
        );

        $query = 'INSERT INTO user (fb_uid, picture, firstname, lastname, created_at) VALUES (:fbUid, :picture, :firstname, :lastname, :created_at);';
        $std = $dbal->prepare($query);

        $std->bindParam(':fbUid', $user['fb_uid'], \PDO::PARAM_STR);
        $std->bindParam(':created_at', $user['created_at'], \PDO::PARAM_STR);
        $std->bindParam(':picture', $user['picture'], \PDO::PARAM_STR);
        $std->bindParam(':firstname', $myNull, \PDO::PARAM_NULL);
        $std->bindParam(':lastname', $myNull, \PDO::PARAM_NULL);


        $fbDatas = $this->facebook->api('/me');
        
        if (!empty($fbDatas)) {

            if (isset($fbDatas['first_name'])) {
                $user['firstname'] = $fbDatas['first_name'];
                $std->bindParam(':firstname', $fbDatas['first_name'], \PDO::PARAM_STR);
            }

            if (isset($fbDatas['last_name'])) {
                $user['lastname'] = $fbDatas['last_name'];
                $std->bindParam(':lastname', $fbDatas['last_name'], \PDO::PARAM_STR);
            }
        }

        $std = $std->execute();

        $user['id'] = $dbal->lastInsertId();

        return $user;
    }

    public function findUserByFbId($fbId)
    {
        $user = null;

        // Cache

        $dbal = $this->em->getConnection();

        $query = sprintf('SELECT u.* FROM user u WHERE u.fb_uid = %s', $dbal->quote($fbId, \PDO::PARAM_STR));

        $std = $dbal->query($query);

        $user = $std->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            $user = $this->createUserFromUid($fbId);
        }

        // Cache
        return $user;
    }
    
    public function getMe()
    {
        try {
            $user = $this->facebook->api('/me');
            return $user;
        }
        catch (Exception $e) {
            echo "Erreur API FB ".$e;
            return null;
        }
    }
    
    public function getMyId()
    {
        $user = $this->findUserByFbId($this->getMyFbId());
        return $user['id'];
    }
    
    public function getMyFbId()
    {
        try {
            $user = $this->facebook->api('/me?fields=id');
            return $user['id'];
        }
        catch (Exception $e) {
            echo "Erreur API FB ".$e;
            return null;
        }
    }
    
    public function getFbFriends($fbId)
    {
        try {
            $lstFriends = $this->facebook->api('/'.$fbId."/friends");
            return $lstFriends;
        }
        catch (Exception $e) {
            echo "Erreur API FB ".$e;
            return null;
        }
    }
    
    public function getFbFriendUsers($fbId)
    {
        try {
            $lstFriends = $this->facebook->api('/'.$fbId."/friends?fields=installed");
            $lstFbUserFriends = array();
            foreach ($lstFriends['data'] as $friend) {
                if(isset($friend["installed"]) && $friend["installed"]) {
                    array_push($lstFbUserFriends, $friend);
                }
            }
            
            if(empty($lstFbUserFriends)) return null;
            return $lstFbUserFriends;
        }
        catch (Exception $e) {
            echo "Erreur API FB ".$e;
            return null;
        }
    }
    
    public function getFriendUsers($fbId)
    {
        $lstFbUserFriends = $this->getFbFriendUsers($fbId);
        if(is_null($lstFbUserFriends)) {
            return null;
        }
        
        $lstUserFriends = array();
        foreach ($lstFbUserFriends as $fbFriend) {
            $friend = $this->findUserByFbId($fbFriend['id']);
            array_push($lstUserFriends, $friend);
        }
        return $lstUserFriends;
    }
    
    public function getFriendUsersWhoCompletedQuizz($fbId, $quizzId) {
        $lstUserFriends = $this->getFriendUsers($fbId);
        $lstUserFriendsWhoCompletedQuizz = array();
        foreach($lstUserFriends as $userFriend) {
            $hasCompletedQuizz = $this->getRepository("QuizzResult")->findByUserIdAndQuizzId($userFriend['id'], $quizzId);
            if(!is_null($hasCompletedQuizz)) {
                array_push($lstUserFriendsWhoCompletedQuizz, $userFriend);
            }
        }
        if(count($lstUserFriendsWhoCompletedQuizz) > 0) {
            return $lstUserFriendsWhoCompletedQuizz;
        }
        
        return null;
    }
    

}