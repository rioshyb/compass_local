<?php
class comn extends spModel
{
	public function tag_replace($str){ 
		 $str = strip_tags($str);
		 $str = str_replace('/', "", $str);
		 $str = str_replace("\\", "", $str);
		 $str = str_replace(">", "", $str);
		 $str = str_replace("<", "", $str);
		 $str = str_replace("<SCRIPT>", "", $str);
		 $str = str_replace("</SCRIPT>", "", $str);
		 $str = str_replace("<script>", "", $str);
		 $str = str_replace("</script>", "", $str);
		 $str=str_replace("select","select",$str);
		 $str=str_replace("join","join",$str);
		 $str=str_replace("union","union",$str);
		 $str=str_replace("where","where",$str);
		 $str=str_replace("insert","insert",$str);
		 $str=str_replace("delete","delete",$str);
		 $str=str_replace("update","update",$str);
		 $str=str_replace("like","like",$str);
		 $str=str_replace("drop","drop",$str);
		 $str=str_replace("create","create",$str);
		 $str=str_replace("modify","modify",$str);
		 $str=str_replace("rename","rename",$str);
		 $str=str_replace("alter","alter",$str);
		 $str=str_replace("cas","cast",$str);
		 $str=str_replace("&","",$str);
		 $str=str_replace(">",">",$str);
		 $str=str_replace("<","<",$str);
		 $str=str_replace("&",chr(34),$str);
		 $str=str_replace("'",chr(39),$str);
		 $str = str_replace("''","'",$str);
		 $str = str_replace("css","'",$str);
		 $str = str_replace("CSS","'",$str); 
		 return $str; 
	}
}