<?php 
	function log_queries() {
		$CI =& get_instance();
		$times = $CI->db->query_times;
		$no =1;
		foreach ($CI->db->queries as $key=>$query) {
			log_message('debug', "Query: #".$no." | ".$times[$key]);
			$no++;
		}
	}

?>