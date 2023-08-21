<?php
/**
 * Used by the HelperContext::testCanIncludeTemplateInSameDirectory test to
 * verify that we can include templates located in the same directory
 *
 * @package Swiftly\Template\Tests
 *
 * @var Swiftly\Template\Context\HelperContext $this
 * @var string $name
 */
?>
<?php echo $this->include('./simple.php', ['name' => $name]);
