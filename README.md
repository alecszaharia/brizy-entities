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
3. Add in config/bundles.php: 
`
Brizy\Bundle\EntitiesBundle\EntitiesBundle::class => ['all' => true]` 
