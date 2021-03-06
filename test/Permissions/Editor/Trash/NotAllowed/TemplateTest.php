<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;


class EditorTrashNotAllowedTemplateTest extends GraphqlTestCase {

  /**
   * + For Superuser
   * + The targett template is not legal.
   */
  public static function getSettings()
  {
    return [
      'login' => 'editor',
      'legalTemplates' => ['city'], // <-- skyscraper template is not legal
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-delete']
            ]
          ],
          [
            'name' => 'city',
            'roles' => ['editor'],
            'editRoles' => ['editor'],
            'rolesPermissions' => [
              'editor' => ['page-delete']
            ]
          ]
        ],
      ]
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $query = 'mutation trashPage($id: ID!) {
      trash(id: $id) {
        id
        name
      }
    }';
    $variables = [
      'id' => $skyscraper->id,
    ];

    assertFalse($skyscraper->isTrash());
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Errors without trashing the page.');
    assertStringContainsString('trash', $res->errors[0]->message);
    assertFalse($skyscraper->isTrash(), 'Does not trashes the target page.');
  }
}