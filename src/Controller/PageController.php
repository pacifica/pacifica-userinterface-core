<?php

namespace Drupal\pacifica\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pacifica\Model\Project;

/**
 * Class PageController.
 *
 * Responsible for rendering the static pages associated
 * with the DataHub module.
 *
 * @package Drupal\pacifica\Controller
 */
class PageController extends ControllerBase
{

  /**
   * Project theme definition.
   *
   * @param int $projectId
   *   The project's id.
   *
   * @return array
   *   Theme definition array.
   *
   * @SuppressWarnings(PHPMD.StaticAccess)
   */
  public function project(int $projectId): array {
    /** @var \Drupal\pacifica\Model\Project $project */
    $project = Project::get($projectId);

    return [
      '#theme' => 'project',
      '#project_title' => $project->getTitle(),
      '#project_logo' => $project->getLogoHtml(),
      '#overview_text' => $project->getDescription(),
      '#data_description' => $project->getDataDescription(),
      '#author_names' => $project->getAuthorNames(),
      '#institution_names' => $project->getInstitutionNames(),
      '#dois' => $project->getDOIs(),
      '#citation_text' => $project->getCitation(),
      '#file_urls' => $project->getFileUrls(),
    ];
  }

  /**
   * Route to render the About page.
   *
   * @return array
   *   Theme definition array.
   */
  public function about(): array {
    return [
      '#theme' => 'about',
    ];
  }

}
