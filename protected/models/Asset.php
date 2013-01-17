<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * This is the model class for table "asset".
 *
 * The followings are the available columns in table 'asset':
 * @property string $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $mimetype
 * @property integer $filesize
 */
class Asset extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Firm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'asset';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	public function getFilename() {
		return $this->id.'.'.$this->extension;
	}

	public function getPath() {
		return Yii::app()->basePath."/assets/$this->id.$this->extension";
	}

	public function getPreview() {
		return Yii::app()->basePath."/assets/preview/$this->id.jpg";
	}

	public function getThumbnail() {
		return Yii::app()->basePath."/assets/thumbnail/$this->id.jpg";
	}

	public function getFileModifiedDate() {
		if (!file_exists($this->path)) {
			throw new Exception("Asset not found: $this->path");
		}

		$stat = stat($this->path);

		return $stat['mtime'];
	}

	public function wrap() {
		$data = Yii::app()->db->createCommand()->select("*")->from("asset")->where("id = $this->id")->queryRow();
		unset($data['id']);
		$data['_data'] = base64_encode(file_get_contents($this->path));
		$data['_preview'] = base64_encode(file_get_contents($this->preview));
		$data['_thumbnail'] = base64_encode(file_get_contents($this->thumbnail));
		return $data;
	}
}