<?php

namespace ProcessWire\GraphQL\Test\Field\Page\Fieldtype;

use \ProcessWire\GraphQL\Utils;
use \ProcessWire\GraphQL\Test\GraphQLTestCase;

class PageNameFieldTest extends GraphQLTestCase {

  const settings = [
    'login' => 'admin',
    'legalTemplates' => ['skyscraper'],
    'legalPageFields' => ['name'],
  ];

	
  public function testValue()
  {
  	$skyscraper = Utils::pages()->get("template=skyscraper");
  	$query = "{
  		skyscraper (s: \"id=$skyscraper->id\") {
  			list {
  				name
  			}
  		}
  	}";
  	$res = self::execute($query);
		assertEquals($skyscraper->name, $res->data->skyscraper->list[0]->name, 'Retrieves `name` field of the page.');
		assertObjectNotHasAttribute('errors', $res, 'There are errors.');
  }

}