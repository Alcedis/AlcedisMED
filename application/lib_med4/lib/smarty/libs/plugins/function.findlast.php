<?php
function smarty_function_findlast($args, &$smarty) {

	if (empty($args["var"]) || empty($args["from"]) || empty($args["field"])) {
		return;
	}

	$data = $args["from"];
   $field = $args["field"];
   $result = '';
   
	foreach ($data AS $date)
	{
      if ($date[$field]['value'] )
      {
      	$result = $date[$field];
      }
	}
	$smarty->assign($args["var"], $result);
}

?>