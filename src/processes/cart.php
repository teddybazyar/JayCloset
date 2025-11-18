<?php

require_once "products.php";

class ProductNotFoundException extends Exception {}

class ShoppingCart {
    private array $items;

    public function __construct() {
        $this->items = [];
    }

    public function addItem(Product $item): void {
        $this->items[] = $item;
    }

    public function removeItem(Product $item): void {
        $found = false;

        foreach ($this->items as $index => $cartItem) {
            if ($cartItem->equals($item)) {
                unset($this->items[$index]);
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new ProductNotFoundException("Product not found in cart");
        }

        $this->items = array_values($this->items);
    }

    public function getItemCount(): int {
        return count($this->items);
    }

    public function empty(): void {
        $this->items = [];
    }

    public function getItems(): array {
        return $this->items;
    }

}
?>