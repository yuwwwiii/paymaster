<?php
require_once 'lib/swift_required.php';
// Create the message
$message = Swift_Message::newInstance()
// Give the message a subject
->setSubject('Your subject')
// Set the From address with an associative array
->setFrom(array('ishmaels@sagesoftinc.com' => 'IR Salvador'))
// Set the To addresses with an associative array
->setTo(array('ishmaels@sagesoftinc.com' => 'IR Salvador'))
// Give it a body
->setBody('Here is the message itself')
// And optionally an alternative body
->addPart('<q>Here is the message itself</q>', 'text/html');
    ?>