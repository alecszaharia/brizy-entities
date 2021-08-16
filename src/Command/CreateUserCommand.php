<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Command;

use Brizy\Bundle\EntitiesBundle\Entity\Application;
use Brizy\Bundle\EntitiesBundle\Entity\Data;
use Brizy\Bundle\EntitiesBundle\Entity\DataUserRole;
use Brizy\Bundle\EntitiesBundle\Entity\Node;
use Brizy\Bundle\EntitiesBundle\Entity\Role;
use Brizy\Bundle\EntitiesBundle\Entity\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-dev-user';

    protected $entityManager = null;

    protected function configure()
    {
        $this->setDescription('Create users for development')->setHidden(true);
        $this->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager = $this->getEntityManager($input->getOption('em'));
        $io = new SymfonyStyle($input, $output);

        $user = new User();
        $user->setApplication($this->createApplication());
        $user->setUserRemoteId(rand(1, 999));
        $user->setNode(
            $this->createNode(['slug' => 'user', 'name' => 'User', 'class' => User::class])
        );
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setLocked(false);
        $user->setApproved(true);

        $this->entityManager->persist($user);

        $project = $this->createProject($user);

        $this->entityManager->flush();

        $io->success('The user has been successfully created.');

        $userData = [];
        $userData['user_id'] = $user->getId();
        $userData['project_id'] = $project->getId();
        $userData['cms_client_id'] = $user->getCmsApiClient()->getIdentifier();
        $userData['cms_client_secret'] = $user->getCmsApiClient()->getSecret();

        foreach ($user->getCmsApiClient()->getGrants() as $grant) {
            $userData['cms_client_grant_types'][] = (string) $grant;
        }
        foreach ($user->getCmsApiClient()->getScopes() as $scope) {
            $userData['cms_client_scopes'][] = (string) $scope;
        }

        $io->writeln(json_encode($userData, JSON_PRETTY_PRINT));

        return Command::SUCCESS;
    }

    /**
     * @param $params
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createNode($params)
    {
        $nodeRepo = $this->entityManager->getRepository(Node::class);

        if ($node = $nodeRepo->findOneBySlug($params['slug'])) {
            return $node;
        }

        $node = new Node();
        $node->setName($params['name']);
        $node->setSlug($params['slug']);
        $node->setEntityClass($params['class']);
        $node->setIsFile(false);
        $node->setCreatedAt(new \DateTime());
        $node->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($node);

        return $node;
    }

    /**
     * @param $params
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createApplication()
    {
        $application = new Application();
        $application->setName('AppName'.rand(1, 1000));
        $application->setScope('user');
        $application->setApiKey('asdasdasdasd');
        $application->setSecret('asdasdasdasd');
        $application->setClientId('asdasdasdasd');
        $application->setCreatedAt(new \DateTime());
        $application->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($application);

        return $application;
    }

    protected function createProject($user)
    {
        $data = new Data();
        $data->setHashId(md5(random_bytes(16)));
        $data->setUid(md5(random_bytes(16)));
        $data->setNode($this->createNode(['slug' => 'project', 'name' => 'Project', 'class' => Data::class]));
        $data->setAuthorId($user->getId());
        $data->setCreatedAt(new \DateTime());
        $data->setUpdatedAt(new \DateTime());

        $container = new Data();
        $container->setHashId(md5(random_bytes(16)));
        $container->setUid(md5(random_bytes(16)));
        $container->setNode($this->createNode(['slug' => 'container', 'name' => 'Container', 'class' => Data::class]));
        $container->setAuthorId($user->getId());
        $container->setCreatedAt(new \DateTime());
        $container->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($container);

        $data->setParent($container);
        $this->entityManager->persist($data);

        $user_role = new DataUserRole();
        $user_role->setData($container);
        $user_role->setUser($user);
        $user_role->setRoleUid($this->createRole()->getUid());
        $user_role->setStatus(DataUserRole::STATUS_APPROVED);


        $this->entityManager->persist($user_role);

        return $data;
    }

    /**
     * @param $params
     *
     * @throws \Doctrine\ORM\ORMException
     */
    protected function createRole()
    {
        $roleRepo = $this->entityManager->getRepository(Role::class);

        if ($role = $roleRepo->findOneByName('Admin')) {
            return $role;
        }

        $role = new Role();
        $role->setName('Admin');
        $role->setUid(md5(random_bytes(16)));
        $role->setCreateAction(true);
        $role->setUpdateAction(true);
        $role->setDeleteAction(true);
        $role->setReadAction(true);
        $role->setNode($this->createNode(['slug' => 'role', 'name' => 'Role', 'class' => Role::class]));
        $role->setCreatedAt(new \DateTime());
        $role->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($role);

        return $role;
    }

    protected function getEntityManager(string $em) {
        return $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getManager($em);
    }


}