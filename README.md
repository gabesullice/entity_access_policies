# Entity Access Policies

Entity Access Policies let you express fine-grained access control rules for any Drupal 8 entity type.

The concept is simple. You put your entities under lock and key.

**Warning**: This is _alpha_ software. In fact, the concepts here are under [active discussion on Drupal.org](https://www.drupal.org/node/777578) and some really insightful ideas are being proposed by lots of people. This is an experimental implementation that is subject to change.

## Overview

Inspired by the `hook_node_grants` and `hook_node_access_records` system, Entity Access Policies are incredibly flexible while being much easier to understand and applicable beyond just nodes.

Entity access policies are plugins, just like blocks in Drupal 8.

You can define your own custom access policy with just two files:

1. A module `info.yml`
1. And a file at `src/Plugin/entity_access_policies/Policy/YourCustomPolicy.php`.

The module directory tree would look like this:

```
custom_module/
  custom_module.info.yml
  src/
    Plugin/
      entity_access_policies/
        Policy/
          YourCustomPolicy.php
```

## The Policy File

The policy file is pretty simple, a policy is just a class that has three methods: `getLocks()`, `getKeys()`, and `applies()`.

You also have to tell Drupal about your policy class with a simple annotation.

Here's an example:

```php
<?php

namespace Drupal\custom_module\Plugin\entity_access_policies\Policy;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

// This just helps get you started. But you can make your own if you want.
use Drupal\entity_access_policies\Lock\DefaultLock; 

// Ditto!
use Drupal\entity_access_policies\Policy\PolicyBase; 

/**
 * @Policy(
 *   id = "your_custom_policy_name",
 *   label = @Translation("Your Human-readable Policy Name"),
 * )
 */
class YourCustomPolicy extends PolicyBase {

  /**
   * You can use this to limit your policy to a particular entity type or
   * bundle. This is here in case calculating your locks is really time
   * consuming.
   */
  public function applies(EntityInterface $entity) {
    return TRUE; // Apply this policy to all the things!
  }

  /**
   * Locks secure the entity actions you want to be controlled. When you give a
   * user a corresponding key (ID), that user will be able to "unlock" all the
   * operations you've specified from here. You can return as 
   */
  public function getLocks(EntityInterface $entity) {
    $lock = DefaultLock::create(
      999, // ID. This can be dynamic, it just has to be an integer.
      ['view', 'update', 'delete'], // Operations. You can do any or all of these.
      $entity->language(), // Language. We just want this to apply to the default langauge.
    );
    
    // Always return an array, other than that, you can return 0 or as many
    // locks as your heart desires.
    return [$lock];
  }

  /**
   * Keys "unlock" the operations you've allowed by creating locks above. Just
   * return a list of integer IDs that correspond to the locks you want to
   * "open" from above.
   */
  public function getKeys(AccountInterface $account) {
    if ($account->hasPermission('not_the_number_of_the_beast')) {
      return [999]; // Now, this user can open up the "lock" from above.
    }

    // Welp, this user didn't have the right permission, so they can't open
    // anything. They don't get _any_ keys.
    return [];
  }

}
```

## "That's not as simple as I had hoped" :(

Hold on, there's more! You don't have to do _any_ of that if you don't want to.
Other modules can make these plugins for you. One such example is the [Attribute-based Access Policies](https://github.com/gabesullice/attribute_access_policies) module. It let's you build your own policies in YAML like this:

```yaml
id: 'first_letter_is_a'
entity_types: ['node']
operations: ['view', 'delete']
entity_condition:
  members:
  - type: condition
    property: 'title.0.value'
    operator: 'STARTS_WITH'
    comparison: 'B'
user_condition:
  members:
  - type: condition
    property: 'name.0.value'
    operator: 'STARTS_WITH'
    comparison: 'a'
```

That would let any user whose username starts with `a`, _view_ or _delete_ any node whose title starts with `B`. I don't know why you'd want to do that, but the point is _you can_.

Entity Access Policies are a new thing&trade; and there isn't a big ecosystem of other pre-made policy plugins yet. So, stake your territory. Build your own and make it configurable. Then let me know about it ;)
