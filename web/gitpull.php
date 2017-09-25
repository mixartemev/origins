<pre>
<?php
exec('whoami && git pull', $o, $r);
print_r($o);
print $r;
?>
</pre>