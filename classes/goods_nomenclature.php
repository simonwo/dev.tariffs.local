<?php
class goods_nomenclature
{
	// Class properties and methods go here
	public $goods_nomenclature_item_id	= "";
	public $productline_suffix			= "";

	public function set_properties($goods_nomenclature_item_id, $productline_suffix, $description, $number_indents) {
		$this->goods_nomenclature_item_id	= $goods_nomenclature_item_id;
		$this->productline_suffix			= $productline_suffix;
		$this->number_indents				= $number_indents;
		$this->description					= $description;

		if ($number_indents == "") {
			$this->get_hierarchy();
		}
	}

	public function get_hierarchy() {
		global $conn;
		$stem = substr($this->goods_nomenclature_item_id, 0, 2);
		$sql = "SELECT goods_nomenclature_item_id, producline_suffix as productline_suffix, number_indents, description FROM ml.goods_nomenclature_export('" . $stem . "%') ORDER BY goods_nomenclature_item_id, producline_suffix";
		$result = pg_query($conn, $sql);
		if  ($result) {
			$ar_goods_nomenclatures[]	= new goods_nomenclature;
			$ar_hierarchies[]			= new goods_nomenclature;

			while ($row = pg_fetch_array($result)) {
				$goods_nomenclature_item_id = $row['goods_nomenclature_item_id'];
				$productline_suffix         = $row['productline_suffix'];
				$number_indents             = $row['number_indents'];
				$description             	= $row['description'];
				$gn = new goods_nomenclature;
				$gn->set_properties($goods_nomenclature_item_id, $productline_suffix, $description, $number_indents);
				$gn->deal_with_double_zeroes();
				array_push($ar_goods_nomenclatures, $gn);
			}
			$record_count = sizeof($ar_goods_nomenclatures);
			for($i = 0; $i < $record_count; $i++) {
				if (($ar_goods_nomenclatures[$i]->goods_nomenclature_item_id == $this->goods_nomenclature_item_id) &&
				($ar_goods_nomenclatures[$i]->productline_suffix == $this->productline_suffix)) {
					$my_index	= $i;
					$my_indent	= $ar_goods_nomenclatures[$i]->number_indents;
					break;
				}
			}
			// Kludge to deal with the chapter level records, which have a "0" indent, the same as their children
			if ($my_indent == 0) {
				if (substr($this->goods_nomenclature_item_id, 2, 10) == "00000000") {
					$my_indent = -1;
				}
			}
			// Search UP the tree from my_index to find parent codes
			$temp_indent = $my_indent;
			for($i = $my_index; $i > 0; $i--) {
				$t = $ar_goods_nomenclatures[$i];
				if (($t->number_indents < $temp_indent) || (($t->goods_nomenclature_item_id == $this->goods_nomenclature_item_id) &&
				($t->productline_suffix == $this->productline_suffix))) {
					array_push($ar_hierarchies, $t);
					$temp_indent = $t->number_indents;
				}
			}
			// Reverse the hierarchy, so that the 'current' hierarchical item sits at the bottom
			$ar_hierarchies = array_reverse($ar_hierarchies);
			$hier_count = sizeof($ar_hierarchies);
			if ($hier_count > 0) {
				array_pop($ar_hierarchies);
			}
			// Remove the empty item accidentally created when the array was initialised
			$hier_count = sizeof($ar_hierarchies);


			// Search DOWN the tree from my_index to find child codes
			$temp_indent = $my_indent;
			for($i = $my_index + 1; $i < $record_count; $i++) {
				$t = $ar_goods_nomenclatures[$i];
				if ($t->number_indents <= $my_indent) {
					break;
				} else {
					array_push($ar_hierarchies, $t);
				}
			}
			$this->ar_hierarchies = $ar_hierarchies;
		}
	}
	
	private function deal_with_double_zeroes() {
		if ($this->number_indents == 0) {
			if (substr($this->goods_nomenclature_item_id, 2, 10) == "00000000") {
				$this->number_indents = -1;
			}
		}
	}
}
?>