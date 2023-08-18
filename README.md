# Swiftly - Template

[![PHP Version](https://img.shields.io/badge/php->=7.4-blue)](https://www.php.net/supported-versions)
[![CircleCI](https://circleci.com/gh/SwiftlyPHP/template/tree/main.svg?style=shield)](https://circleci.com/gh/SwiftlyPHP/template/tree/main)
[![Coverage Status](https://coveralls.io/repos/github/SwiftlyPHP/template/badge.svg?branch=main)](https://coveralls.io/github/SwiftlyPHP/template?branch=main)

Manage and render PHP templates.

Designed primarily to be used for [SwiftlyPHP](https://github.com/SwiftlyPHP)
projects but can also be used on its own, the template component provides a
straightforward utility for loading PHP files and capturing any output they
generate.

The library also provides several helpers to make working in mixed HTML/PHP
contexts easier, including the ability to display JSON, escape HTML character
entities and render sub-components.

## Installation

To install the library use [Composer](https://getcomposer.org/):

```sh
composer install swiftly/template
```

## Usage
### Basic Templates

Using the template renderer in its most basic form only requires a few steps of
setup.

To demonstrate, lets say we have a simple PHP template called `hi.php` that
contains the following:

```php
<?php // hi.php ?>
Hello world!
```

Normally, including a file like this would cause its content to be sent
directly to the user.

To render the content and capture it in a variable we must instantiate a fresh
instance of the [Engine](./src/Engine.php) class. The Engine class has only one
method `render()`, which we pass the name of the file we wish to load.

For our Engine to first find the templates however, we'll need to construct a
[FileFinder](./src/FileFinder.php) which is responsible for locating templates
on the filesystem.

```php
<?php

use Swiftly\Template\FileFinder;
use Swiftly\Template\Engine;

$templates = new FileFinder("path/to/template/dir");
$renderer = new Engine($templates);

// $output -> "Hello world!"
$output = $renderer->render("hi.php");
```

The variable `$output` now contains the string `Hello world!` - the templating
system in it's simplist form!

### Going deeper

While the above example may be trivial it shows several key concepts including
how templates are found and how to render them. But what if we need to pass data
into our templates?

Luckily, the `render()` method takes an optional second argument which allows
you to do just that.

Let's say we have a template file like the following:

```php
<?php // template.php ?>
My name is <?php echo $name; ?> and I am <?php echo $age; ?> years old.
```

As we can see, this template expects 2 variables: `$name` and `$age` to exist
and have values. To pass them to the template we provide an array of values to
the `render()` method like so:

```php
<?php

use Swiftly\Template\FileFinder;
use Swiftly\Template\Engine;

$templates = new FileFinder("path/to/template/dir");
$renderer = new Engine($templates);

// $output -> "My name is Douglas and I am 42 years old."
$output = $renderer->render("template.php", [
    "name" => "Douglas",
    "age" => 42
]);
```

Here we can see a one-to-one mapping. The keys of the given array become the
names of the variables - `$name` and `$age` respectively - and the provided
values are made available inside the template.

Hopefully you can start to see where this might become useful. Providing
variables to a template in this manner allows you to separate your files into
dedicated areas of concern, letting you perform your logic in one place while
display/markup is handled in dedicated template files.

