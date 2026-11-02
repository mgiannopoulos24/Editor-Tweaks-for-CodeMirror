<?php
/**
 * Sample PHP file for the editor preview.
 */

namespace Shop;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Discountable {
	public function apply_discount( $percent );
}

class Product implements Discountable {

	const CURRENCY = 'EUR';

	private static $count = 0;

	private $name;
	private $price;
	private $tags;

	public function __construct( $name, $price, array $tags = [] ) {
		$this->name  = $name;
		$this->price = $price;
		$this->tags  = $tags;
		self::$count++;
	}

	public function apply_discount( $percent ) {
		if ( $percent <= 0 || $percent > 100 ) {
			throw new \InvalidArgumentException( 'Discount must be between 0 and 100.' );
		}

		$this->price = round( $this->price * ( 1 - $percent / 100 ), 2 );
	}

	public function label() {
		return sprintf( '%s — %.2f %s', $this->name, $this->price, self::CURRENCY );
	}

	public function has_tag( $tag ) {
		return in_array( strtolower( $tag ), $this->tags, true );
	}

	public static function count() {
		return self::$count;
	}
}

function discounted_labels( array $products, $tag, $percent ) {
	$labels = [];

	foreach ( $products as $product ) {
		if ( $product->has_tag( $tag ) ) {
			$product->apply_discount( $percent );
		}

		$labels[] = $product->label();
	}

	return $labels;
}

discounted_labels(
	[
		new Product( 'Notebook', 4.50, [ 'paper', 'school' ] ),
		new Product( 'Fountain pen', 24.00, [ 'writing' ] ),
		new Product( 'Desk lamp', 39.90 ),
	],
	'school',
	10
);
