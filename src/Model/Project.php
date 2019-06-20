<?php

namespace Drupal\pacifica\Model;

use Drupal\node\Entity\Node;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Project.
 *
 * Model class that is a convenience wrapper for interacting
 * with pacifica_project content nodes.
 *
 * @package Drupal\pacifica\Model
 */
class Project {

  /**
   * Node type to use.
   *
   * @const string
   */
  public const NODE_TYPE = 'pacifica_project';

  /**
   * Drupal node entity to use.
   *
   * @var \Drupal\node\Entity\Node
   */
  private $node;

  /**
   * Project constructor.
   *
   * @param \Drupal\node\Entity\Node $node
   *   Drupal node entity.
   */
  private function __construct(Node $node) {
    if ($node->getType() !== self::NODE_TYPE) {
      throw new \InvalidArgumentException(
        "An instance of " . self::class
               . " may only be constructed using a node of type '" . self::NODE_TYPE . "'"
      );
    }

    $this->node = $node;
  }

  /**
   * Factory method to generate an instance based on the Drupal node id.
   *
   * @param int $nodeId
   *   Drupal node id.
   *
   * @return self
   *   New instance.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public static function get(int $nodeId): self {
    $node = Node::load($nodeId);

    if (NULL === $node || $node->getType() !== static::NODE_TYPE) {
      throw new NotFoundHttpException("No project with ID $nodeId exists");
    }

    return new static($node);
  }

  /**
   * Ge the project's full title.
   *
   * @return string
   *   The full (long) title of the project.
   */
  public function getTitle(): string {
    return $this->node->title->value;
  }

  /**
   * Get the project description.
   *
   * A description text that provides a brief overview of the
   * project and its purpose.
   *
   * @return string
   *   The description.
   */
  public function getDescription(): ?string {
    return $this->node->field_pac_proj_description->value;
  }

  /**
   * Get a description of the project's data.
   *
   * A description text that provides a brief overview of the project's
   * data; its format, origin, provenance, etc..
   *
   * @return string
   *   The description.
   */
  public function getDataDescription(): string {
    return $this->node->field_pac_proj_data_description->value;
  }

  /**
   * Get the full names of all authors associated with the project.
   *
   * @return string[]
   *   An array of author names.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function getAuthorNames(): array {
    $names = [];
    foreach ($this->node->field_pac_proj_data_handling_aut->referencedEntities() as $authParagraph) {
      foreach ($authParagraph->field_pac_proj_project_member->getValue() as $userRef) {
        $user = User::get($userRef['target_id']);
        $names[] = $user->getFullName();
      }
    }

    return $names;
  }

  /**
   * Get the names of all institutions participating in the project.
   *
   * @return string[]
   *   An array of names.
   */
  public function getInstitutionNames(): array {
    $institutionNames = [];
    foreach ($this->node->field_pac_proj_institutions->referencedEntities() as $institution) {
      $institutionNames[] = $institution->getTitle();
    }
    return $institutionNames;
  }

  /**
   * Get any DOIs associated with this project.
   *
   * @return string[]
   *   An array of DOI's.
   *
   * phpcs:disable Drupal.NamingConventions.ValidFunctionName
   */
  public function getDOIs(): array {
    $dois = [];
    foreach ($this->node->field_pac_proj_dois as $doi) {
      $dois[] = $doi->value;
    }
    return $dois;
  }

  /**
   * Get the citation text to use when citing this project.
   *
   * @return string
   *   Project's citation text.
   *
   * phpcs:enable Drupal.NamingConventions.ValidFunctionName
   */
  public function getCitation(): ?string {
    return $this->node->field_pac_proj_citation->value;
  }

  /**
   * Get the <img> tag markup for the logo representing this project.
   *
   * @return string|null
   *   The logo in HTML format, or NULL if no logo has been defined.
   */
  public function getLogoHtml(): ?string {
    $imgEntity = $this->node->field_pac_proj_logo[0]->entity;
    if (!$imgEntity) {
      return NULL;
    }

    $imgUrl = $this->node->field_pac_proj_logo[0]->entity->url();
    $title = $this->getTitle();
    return "<img class='datahub-project-logo' src='$imgUrl' alt='$title'>";
  }

  /**
   * Get the URLs of data files associated with this project.
   *
   * @return string[]
   *   The URLs of data files associated with this project.
   *   Array format is like [<link_title> => <url>, ...]
   */
  public function getFileUrls(): array {
    $urls = [];
    foreach ($this->node->field_pac_proj_files as $file) {
      $urls[$file->title] = $file->uri;
    }
    return $urls;
  }

}
