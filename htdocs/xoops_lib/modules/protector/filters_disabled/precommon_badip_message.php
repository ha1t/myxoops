<?php

class protector_precommon_badip_message extends ProtectorFilterAbstract {

	function execute()
	{
		echo _MD_PROTECTOR_YOUAREBADIP ;
		exit ;
	}

}

?>