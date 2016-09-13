<?php
function smarty_function_findlastrecord($args, &$smarty) {

   if (empty($args["var"]) || empty($args["from"]) ) {
      return;
   }

   $data = $args["from"];

   $condleft = isset($args["condleft"])?$args["condleft"]:null;
   $condright = isset($args["condright"])?$args["condright"]:null;
   $condop = isset($args["condop"])?$args["condop"]:null;

   $pos = strpos($condleft, '|');

   if ($pos)
   {
      $felder = substr($condleft, 0, $pos);
      $bed = substr($condleft,strpos($condleft, '|')+1);
   }
   else
   {
      $felder = $condleft;
      $bed = '';
   }

   $pipe = strlen($bed)?'|':'';

   $felder_arr = explode('.', $felder);

   include_once  (DIR_LIB . "/smarty/libs/plugins/function.eval.php");

   foreach ($data AS $date)
   {
      if ($condleft === null || $condright === null || $condop === null )
      {
         $result = $date;
      }
      else
      {
         $wert = $date;

         foreach ($felder_arr as $feld)
         {
            $wert = $wert["$feld"];
         }
         $smarty->assign('left', $wert);
         $smarty->assign('right', $condright);

         $param = array ( 'var' => '{{if "'.$wert.'"'.$pipe.$bed.' '.$condop.' "'.$condright.'"}}true{{else}}false{{/if}}'
                        );
         $smarty->left_delimiter = '{{';
         $smarty->right_delimiter = '}}';

         $cond_result  = smarty_function_eval($param, &$smarty);

         if ($cond_result === "true")
         {
            $result = $date;
         }
      }
   }
   $smarty->assign($args["var"], $result);
}

?>