<?php

namespace Drupal\pacifica\Model;

use Drupal\user\Entity\User as UserEntity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class User.
 *
 * Model class that is a convenience wrapper for interacting with user entities.
 *
 * @package Drupal\pacifica\Model
 */
class User {

  /**
   * Drupal user entity.
   *
   * @var \Drupal\user\Entity\User
   */
  private $entity;

  /**
   * User constructor.
   *
   * @param \Drupal\user\Entity\User $entity
   *   Drupal user entity.
   */
  private function __construct(UserEntity $entity) {
    $this->entity = $entity;
  }

  /**
   * Factory method to generate an instance based on the Drupal user id.
   *
   * @param int $entityId
   *   Drupal user id.
   *
   * @return self
   *   New instance.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function get(int $entityId): self {
    $entity = UserEntity::load($entityId);

    if (NULL === $entity) {
      throw new NotFoundHttpException("No user with ID $entityId exists");
    }

    return new static($entity);
  }

  /**
   * Get the full name of this user.
   *
   * @return string
   *   User's full name.
   */
  public function getFullName(): string {
    return $this->entity->realname ?: $this->entity->getAccountName();
  }

}
