<?php namespace ProcessWire\GraphQL\Test\Permissions;

use ProcessWire\GraphQL\Test\GraphqlTestCase;
use ProcessWire\GraphQL\Utils;


class SuperuserMoveTemplateParentTemplatesTest extends GraphqlTestCase {

  /**
   * + For superuser.
   * + The target template is legal.
   * + The new parent template is legal.
   * - The new parent template does not match the target template's parentTemplates property.
   */
  public static function getSettings()
  {
    $architects = Utils::templates()->get("name=architects");
    return [
      'login' => 'admin',
      'legalTemplates' => ['city', 'skyscraper'],
      'access' => [
        'templates' => [
          [
            'name' => 'skyscraper',
            'parentTemplates' => [$architects->id]
          ],
        ]
      ]
    ];
  }

  public function testPermission() {
    $skyscraper = Utils::pages()->get("template=skyscraper, sort=random");
    $newParent = Utils::pages()->get("template=city, id!={$skyscraper->parentID}, sort=random");
    
    $query = 'mutation movePage($page: SkyscraperUpdateInput!){
      updateSkyscraper(page: $page) {
        id
        name
      }
    }';


    $variables = [
      'page' => [
        'id' => $skyscraper->id,
        'parent' => $newParent->id,
      ]
    ];

    assertNotEquals($newParent->id, $skyscraper->parentID);
    $res = self::execute($query, $variables);
    assertEquals(1, count($res->errors), 'Does not allow to move if new parent template is not legal.');
    assertStringContainsString('parent', $res->errors[0]->message);
  }
}