<?php
$string = '<p>I have some texts here</p> and also links such as <a href="http://www.youtube.com">http://www.youtube.com</a> , www.haha.com and lol@example.com. They are ready to be replaced.';

echo ju_target_blank(make_clickable($string));
?>