# iomywiab-php-enums

This library provides an easy handling of enumerations.

Please note: PHP 8.1 probably will introduce enumerations on language level

Basic structure:

```php
use iomywiab\iomywiab_php_enums\EnumValue;

class Color extends EnumValue {
   public const RED = 1;
   public const GREEN = 2;
   public const BLUE = 3;
}
foreach (Color::definition() as $color) {
    echo $color->getName(), ' ';
} 
$red = Color::RED();
$green = new Color(Color::GREEN);
$blue = new Color('GREEN');
echo "\n", $red->getName(), ' ', $green->getOrdinal(), ' ', $blue->getName();
```

```
RED GREEN BLUE
RED 2 BLUE
```

You can define additional values (like string) that get ignore:

```php
use iomywiab\iomywiab_php_enums\EnumValue;

class Color extends EnumValue {
   public const RED = 1;
   public const GREEN = 2;
   public const BLUE = 3;
   public const DEFAULT_COLOR = 'dark-red';
} 
foreach (Color::definition() as $color) {
    echo $color->getName(), ' ';
} 
echo "\n", Color::DEFAULT_COLOR;
```

```
RED GREEN BLUE
dark-red
```

You can exclude any constant from being parsed for enumeration functionality:

```php
use iomywiab\iomywiab_php_enums\EnumValue;

class Color extends EnumValue {
   public const ENUM_IGNORE = ['DEFAULT_COLOR'];
   public const RED = 1;
   public const GREEN = 2;
   public const BLUE = 3;
   public const DEFAULT_COLOR = self::RED;
} 
foreach (Color::definition() as $color) {
    echo $color->getName(), ' ';
} 
echo "\n", Color::DEFAULT_COLOR;
```

```
RED GREEN BLUE
1
```

You can define any number of value specific additional attributes:

```php
use iomywiab\iomywiab_php_enums\EnumValue;

class Color extends EnumValue {

   public const RED = 1;
   public const GREEN = 2;
   public const BLUE = 3;

   public const RGB = [
      self::RED => '#ff0000',     
      self::GREEN => '#00ff00',    
      self::BLUE => '#0000ff'     
   ];
}
foreach (Color::definition() as $color) {
    echo $color->getName(), '=', $color->getRGB(), '   ';
} 
```

```
RED=#ff0000   GREEN=#00ff00   BLUE=#0000ff
```

You can define the formatting of the names:

```php
use iomywiab\iomywiab_php_enums\EnumValue;

class Color extends EnumValue {

   public const RED = 1;
   public const GREEN = 2;
   public const DARK_BLUE = 3;
 
   public static function getFormattedName(string $name): string
   {
      return ('GREEN' == $name)
         ? 'very-green'
         : str_replace('_', '-', strtolower($name));
   }
}
foreach (Color::definition() as $color) {
    echo $color->getName(), ' ';
} 
```

```
red very-green dark-blue
```

