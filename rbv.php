<?php
 header("Content-type: text/xml; charset=utf-8"); 
  echo "<?xml version=\"1.0\"?>";
  echo "<rbagency_version>";
  echo get_option("rb_agency_version");
  echo "</rbagency_version>";
?>