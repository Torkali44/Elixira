<?php
try {
    $item = App\Models\Item::first();
    echo "Item rating: " . $item->average_rating . "\n";
    
    $brand = App\Models\Brand::first();
    if ($brand) {
        echo "Brand rating: " . $brand->average_rating . "\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
