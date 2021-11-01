<?php

declare(strict_types=1);

namespace Brizy\Bundle\EntitiesBundle\Command;

use Brizy\Bundle\EntitiesBundle\Entity\Application;
use Brizy\Bundle\EntitiesBundle\Entity\Data;
use Brizy\Bundle\EntitiesBundle\Entity\DataUserRole;
use Brizy\Bundle\EntitiesBundle\Entity\Node;
use Brizy\Bundle\EntitiesBundle\Entity\ProjectAccessTokenMap;
use Brizy\Bundle\EntitiesBundle\Entity\Role;
use Brizy\Bundle\EntitiesBundle\Entity\User;
use Brizy\Bundle\EntitiesBundle\Factory\AccessTokenFactory;
use Brizy\Bundle\EntitiesBundle\Utils\Random;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Trikoder\Bundle\OAuth2Bundle\Model\AccessToken;
use Trikoder\Bundle\OAuth2Bundle\Model\Client;
use Trikoder\Bundle\OAuth2Bundle\Model\Grant;
use Trikoder\Bundle\OAuth2Bundle\Model\RedirectUri;
use Trikoder\Bundle\OAuth2Bundle\Model\Scope;
use Trikoder\Bundle\OAuth2Bundle\OAuth2Grants;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-dev-user';

    protected $entityManager = null;

    protected function configure()
    {
        $this->setDescription('Create users for development')->setHidden(true);
        $this->addOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command');
        $this->addOption('key', null, InputOption::VALUE_REQUIRED, 'The private key path use tot generate the jwt tokens');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->entityManager = $this->getEntityManager($input->getOption('em'));
        $io = new SymfonyStyle($input, $output);

        $user = new User();
        $user->setApplication($this->createApplication());
        $user->setUserRemoteId(rand(1, 999));
        $user->setNode($this->createNode(['slug' => 'user', 'name' => 'User', 'class' => User::class]));
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setLocked(false);
        $user->setApproved(true);
        $this->entityManager->persist($user);

        // generate user access token
        $accessToken = new AccessToken(
            md5(random_bytes(32)),
            (new \DateTimeImmutable())->modify('+3 months'),
            $user->getCmsApiClient(),
            (string) $user->getId(),
            ['user']
        );

        $this->entityManager->persist($accessToken);

        list($project, $projectAccessToken) = $this->createProject($user);

        $this->entityManager->flush();

        $userData = [];
        $userData['user_id'] = $user->getId();
        $userData['project_id'] = $project->getId();
        $userData['cms_client_id'] = $user->getCmsApiClient()->getIdentifier();
        $userData['cms_client_secret'] = $user->getCmsApiClient()->getSecret();
        $userData['user_oauth2_token'] = [
            [
                'scopes' => ['user'],
                'token' => AccessTokenFactory::generateJwtToken($accessToken, $input->getOption('key')),
                'user_id' => $user->getId(),
                'project_id' => $project->getId(),
            ],
        ];
        $userData['project_oauth2_token'] = [
            [
                'scopes' => ['project'],
                'token' => AccessTokenFactory::generateJwtToken($projectAccessToken, $input->getOption('key')),
                'user_id' => $user->getId(),
                'project_id' => $project->getId(),
            ],
        ];

        foreach ($user->getCmsApiClient()->getGrants() as $grant) {
            $userData['cms_client_grant_types'][] = (string) $grant;
        }
        foreach ($user->getCmsApiClient()->getScopes() as $scope) {
            $userData['cms_client_scopes'][] = (string) $scope;
        }

        $io->success('The user has been successfully created.');
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

        // create project client
        $client = new Client(Random::generateOauthClientIdentifier(), Random::generateOauthClientSecret());
        $client->setScopes(new Scope('project'));
        $client->setGrants(
            new Grant('app_client_credentials'),
            new Grant(OAuth2Grants::REFRESH_TOKEN)
        );

        $client->setRedirectUris(new RedirectUri('https://random.url'));
        $client->setActive(true);

        $this->entityManager->persist($client);

        // assign oauth client to project
        // this is a bit of a hack as the token for the project must be assigned
        // by installing a cms_application to this project

        // generate user access token
        $accessToken = new AccessToken(
            md5(random_bytes(32)),
            (new \DateTimeImmutable())->modify('+1 day'),
            $user->getCmsApiClient(),
            (string) $user->getId(),
            ['project']
        );

        $this->entityManager->persist($accessToken);
        $tokenModel = $this->entityManager->getRepository(AccessToken::class)->find($accessToken->getIdentifier());
        $map = new ProjectAccessTokenMap($data, $tokenModel);
        $this->entityManager->persist($map);

        return [$data, $accessToken];
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

    protected function getEntityManager(string $emName)
    {
        return $this->getApplication()->getKernel()->getContainer()->get('doctrine')->getManager($emName);
    }
}
