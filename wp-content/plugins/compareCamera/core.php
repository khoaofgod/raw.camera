<?php

namespace compareCamera;
use phpKb\Kb;

class core {
	var $post_id = 0;
	public function setPostId($post_id) {
		$this->post_id = $post_id;
	}

	public function getPostId() {
		return $this->post_id;
	}

	public function compare_camera($post_content) {
		$post_id = $this->getPostId();
		$camera1 = $this->getCustomField("kb_item_1");
		$camera2 = $this->getCustomField("kb_item_2");
		if($post_id > 0) {
			 echo $this->getThemes("compare_camera", array(

			));

		}

	}

	public function getCustomField($key) {
		$x = get_post_custom_values($key,$this->getPostId());
		if(!is_null($x)) {
			return $x[0];
		} else {
			return "";
		}
	}

	public function getThemes($filename, $tags_data) {
		return Kb::replaceTemplate(file_get_contents(__DIR__."/themes/".$filename.".html"), $tags_data);

	}
}