<?php
assert($this instanceof \Qiq\Template);
$this->setLayout('layout/base');
?>
<h2>Exception</h2>
<p>{{h $e['code'] }}</p>
<p>{{h $e['class'] }} ({{h $e['message'] }})</p>
