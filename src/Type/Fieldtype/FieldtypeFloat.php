<?php namespace ProcessWire\GraphQL\Type\Fieldtype;

use GraphQL\Type\Definition\Type;
use ProcessWire\GraphQL\Type\CacheTrait;

class FieldtypeFloat
{ 
  use CacheTrait;
  public static function type()
  {
    return Type::float();
  }

  public static function field($options)
  {
    return self::cache('field-' . $options['name'], function () use ($options) {
      return array_merge($options, [
        'type' => self::type(),
      ]);
    });
  }
}
