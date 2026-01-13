<?php
/**
 * Used by the HelperContext::testThrowsOnMissingTemplate test to verify that
 * attempting to include non-existant templates will cause an exception
 *
 * @package Swiftly\Template\Tests
 *
 * @var Swiftly\Template\Context\HelperContext $this
 */
?>
<?php echo $this->include('./no-template.php');
