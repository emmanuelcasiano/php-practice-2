<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| DISCOUNT STRATEGY (Behavior Layer)
|--------------------------------------------------------------------------
| Decision:
| - We use Strategy Pattern to avoid if/else for discounts
| - Each discount is interchangeable (polymorphism)
*/

interface DiscountStrategy
{
    public function apply(float $price, int $qty): float;
    public function label(): string;
}

class NoDiscount implements DiscountStrategy
{
    public function apply(float $price, int $qty): float
    {
        return $price * $qty;
    }
    public function label(): string
    {
        return "No discount";
    }
}

class PercentDiscount implements DiscountStrategy
{
    public function __construct(private float $percent) {}

    public function apply(float $price, int $qty): float
    {
        return ($price * $qty) * (1 - $this->percent / 100);
    }

    public function label(): string
    {
        return "{$this->percent}% off";
    }
}

/*
|--------------------------------------------------------------------------
| DOMAIN ENTITY (Data only)
|--------------------------------------------------------------------------
| Decision:
| - Product is "readonly" → cannot change after creation
| - Represents real-world object
*/

class Product
{
    public function __construct(
        public readonly string $sku,
        public readonly string $name,
        public readonly float $price
    ) {}
}

/*
|--------------------------------------------------------------------------
| CART ITEM (Composition)
|--------------------------------------------------------------------------
| Decision:
| - Combines Product + Quantity + Discount
| - Uses composition (HAS-A relationship)
*/

class CartItem
{
    public function __construct(
        public Product $product,
        public int $qty,
        public DiscountStrategy $discount
    ) {}

    public function total(): float
    {
        // Delegation to strategy (no if/else)
        return $this->discount->apply($this->product->price, $this->qty);
    }
}

/*
|--------------------------------------------------------------------------
| CART (Service Layer)
|--------------------------------------------------------------------------
| Decision:
| - Handles all business logic (add/remove/update)
| - Keeps storage separate from entities
*/

class Cart
{
    public array $items = [];

    public function add(CartItem $item): void
    {
        $sku = $item->product->sku;

        // If item exists → increase quantity
        if (isset($this->items[$sku])) {
            $this->items[$sku]->qty += $item->qty;
        } else {
            $this->items[$sku] = $item;
        }
    }

    public function remove(string $sku): void
    {
        unset($this->items[$sku]);
    }

    public function subtotal(): float
    {
        return array_sum(array_map(fn($i) => $i->total(), $this->items));
    }

    public function tax(): float
    {
        return $this->subtotal() * 0.12; // PH VAT
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax();
    }
}

session_start();

/*
|--------------------------------------------------------------------------
| SESSION (Persistence Layer)
|--------------------------------------------------------------------------
| Decision:
| - Using session as temporary storage (no DB yet)
*/

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new Cart();
}

$cart = $_SESSION['cart'];

/*
|--------------------------------------------------------------------------
| PRODUCT LIST (Simulated DB)
|--------------------------------------------------------------------------
*/

$products = [
    "KB" => new Product("KB", "Keyboard", 150),
    "MS" => new Product("MS", "Mouse", 75),
    "HD" => new Product("HD", "HDMI", 12),
];

/*
|--------------------------------------------------------------------------
| HANDLE POST REQUESTS
|--------------------------------------------------------------------------
| Decision:
| - Single entry point
| - Action-based handling (like controller)
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ADD ITEM
    if (isset($_POST['add'])) {

        $product = $products[$_POST['sku']];
        $qty = (int)$_POST['qty'];

        // Strategy selection (controlled input)
        $discount = match ($_POST['discount']) {
            'percent' => new PercentDiscount(10),
            default   => new NoDiscount(),
        };

        $cart->add(new CartItem($product, $qty, $discount));
    }

    // DELETE ITEM
    if (isset($_POST['delete'])) {
        $cart->remove($_POST['sku']);
    }

    // SAVE SESSION
    $_SESSION['cart'] = $cart;

    // PRG Pattern
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cart System</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h1>🛒 Cart System</h1>

    <!-- ================= ADD FORM ================= -->
    <form method="POST" class="form">
        <select name="sku">
            <?php foreach ($products as $p): ?>
                <option value="<?= $p->sku ?>">
                    <?= $p->name ?> ($<?= $p->price ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="qty" value="1" min="1">

        <select name="discount">
            <option value="none">No Discount</option>
            <option value="percent">10% Off</option>
        </select>

        <button name="add">Add</button>
    </form>

    <!-- ================= CART DISPLAY ================= -->
    <h2>Items</h2>

    <?php foreach ($cart->items as $item): ?>
        <div class="item">
            <strong><?= $item->product->name ?></strong>
            <p>Qty: <?= $item->qty ?></p>
            <p>Total: $<?= number_format($item->total(), 2) ?></p>
            <p><?= $item->discount->label() ?></p>

            <!-- DELETE -->
            <form method="POST">
                <input type="hidden" name="sku" value="<?= $item->product->sku ?>">
                <button name="delete">Remove</button>
            </form>
        </div>
    <?php endforeach; ?>

    <!-- ================= TOTAL ================= -->
    <h2>Summary</h2>
    <p>Subtotal: $<?= number_format($cart->subtotal(), 2) ?></p>
    <p>Tax (12%): $<?= number_format($cart->tax(), 2) ?></p>
    <p><strong>Total: $<?= number_format($cart->total(), 2) ?></strong></p>

</body>

</html>