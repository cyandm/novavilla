<?php

namespace Cyan\Theme\Helpers\ACF;

class AcfGroupFields extends AcfGroup {

	/**
	 * Add a gallery field
	 * @param string $name The name of the field
	 * @param string $label The label of the field
	 * @param array $additionalAttributes Additional attributes for the field except for the image field
	 * @param int $count The number of images to add
	 * @param string $id The ID of the field
	 * @return void
	 */
	public function addGallery( $name, $label, $additionalAttributes = [], $count = 6, $id = '' ) {
		$this->contentFields->addGallery( $name, $label, $additionalAttributes, $count, $id );
	}

}

