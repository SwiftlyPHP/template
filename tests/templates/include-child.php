<?php
/**
 * Used by the HelperContext::testCanIncludeTemplateInSubDirectory test to
 * verify that we can include templates located in child directories
 *
 * @package Swiftly\Template\Tests
 *
 * @var Swiftly\Template\Context\HelperContext $this
 * @var string $name
 */
?>
<?php echo $this->include('./example/simple.php', ['name' => $name]);
