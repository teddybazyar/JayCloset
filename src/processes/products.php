<?php

require_once __DIR__ . '/../includes/db_cred.php';
require_once __DIR__ . '/../includes/jayclosetdb.php';

class Product {
    private int $itemID;
    private string $title;
    private string $color;
    private string $sku;
    private string $gender;
    private string $description;
    private string $size;
    private bool $reserved; 


    public function __construct(
        int $itemID,
        string $title,
        string $color,
        string $sku,
        string $gender,
        string $description,
        string $size,
        bool $reserved
    ) {
        $this->itemID = $itemID;
        $this->title = $title;
        $this->color = $color;
        $this->sku = $sku;
        $this->gender = $gender;
        $this->description = $description;
        $this->size = $size;
        $this->reserved = $reserved;
    }

    public function getItemID(): int {
        return $this->itemID;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function getSKU(): string {
        return $this->sku;
    }

    public function getGender(): string {
        return $this->gender;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getSize(): string {
        return $this->size;
    }

    public function getIsReserved(): bool {
        return $this->reserved;
    }

    public function equals(Product $other): bool {
        return $this->itemID === $other->getItemID();
    }
}
?>