# Brizy Entities

This bundle will contain the following classes and repositories

* Application / ApplicationRepository
* Data / DataRepository
* DataUserRole / DataUserRoleRepository
* Metafield / MetafieldRepository
* MetafieldInt / MetafieldIntRepository
* MetafieldText / MetafieldTextRepository
* MetafieldVarchar / MetafieldVarcharRepository
* Node / NodeRepository
* ProjectAccessTokenMap / ProjectAccessTokenMapRepository
* Revision / RevisionRepository
* Role / RoleRepository
* User / UserRepository

This bundle will add separate entity manager in doctrine.

Problems:

- figure out how the associations between entities from different entity managers wil work.

## Install and Configure

1. Add repository in composer:

    ```yaml
    {
      "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/alecszaharia/brizy-entities.git"
        },
        {
          "type": "vcs",
          "url": "https://github.com/bagrinsergiu/base64-encoded-file.git"
        }
      ]
    }
    ```
2. Run `composer require bagrinsergiu/brizy-entities dev-main`
   
   If you are asked to install the TrikoderOAuth2Bundle recipe.. select NO.
   
   This bundle is already configured in BrizyEntitiesBundle.
   

3. Add in config/bundles.php: 
   ```php
   Trikoder\Bundle\OAuth2Bundle\TrikoderOAuth2Bundle::class => ['all' => true],
   Brizy\Bundle\EntitiesBundle\EntitiesBundle::class => ['all' => true]
   ```
4. Create bundle config: config/packeges/brizy_entities.yaml
    ```yaml
    brizy_entities:
      persistence:
        doctrine:
          entity_manager:
            name: {entity_manager_name|default}
    ```
5. Generate or set the public key
   ```shell
   mkdir -p var/oauth
   cd var/oauth
   openssl genrsa -out key.pem 1024
   openssl rsa -in key.pem -pubout > key.pub
   ```
6. Add in .env file the following env vars 
    ```dotenv
    OAUTH2_PRIVATE_KEY_PATH="var/oauth/key.pem"
    OAUTH2_PUBLIC_KEY_PATH="var/oauth/key.pub"
    OAUTH2_KEY_PASSPHRASE=""
    OAUTH2_ENCRYPTION_KEY=""
    OAUTH2_ACCESS_TOKEN_TTL=""
    OAUTH2_REFRESH_TOKEN_TTL=""
    ```

7. Configure a separate doctrine connection and entity manager to handle the brizy entitites.
   This way we make sure that all brizy entities are not going to be create in the service's database.
   ```yaml
    doctrine:
        dbal:
            default_connection: default
            connections:
                default:
                    # configure these for your database server
                    url: '%env(resolve:DATABASE_URL)%'
                    driver: 'pdo_mysql'
                    schema_filter: ~^(?!sessions)~
                    default_table_options:
                        charset: utf8
                        collate: utf8_unicode_ci
                authorize:
                    url: '%env(resolve:AUTHORIZE_DATABASE_URL)%'
                    driver: 'pdo_mysql'
                    default_table_options:
                        charset: utf8
                        collate: utf8_unicode_ci
        orm:
            default_entity_manager: default
            auto_generate_proxy_classes: true
            entity_managers:
                default:
                    connection: default
                    auto_mapping: false # WARNING: This will disable mapping
                    naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
                    mappings:
                        App:
                            is_bundle: false
                            type: annotation
                            dir: '%kernel.project_dir%/src/Entity'
                            prefix: 'App\Entity'
                            alias: App
                authorize:
                    connection: authorize
                    # make sure that you use the correct naming_strategy as the one used on the AUTHORIZE_DATABASE
                    naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
   ```
   
   WARNING: Notice the `auto_mapping: false` on the default entity manager. 
   
   Setting this to false will make the doctrine map only the mapping defined in this config.
   
   It will not automatically map any bundle that have entities mapped