<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Product extends Model
{
    use HasFactory;

    /**
     *  PRODUCT ATTRIBUTES
     *  $this->attributes['id'] - int - contains the product primary key (id)
     *  $this->attributes['name'] - string - contains the product name
     *  $this->attributes['description'] - text - contains the product description
     *  $this->attributes['image'] - string - contains the product image (url)
     *  $this->attributes['price'] - int - contains the product price
     *  $this->attributes['created_at'] - timestamp - contains the product creation date
     *  $this->attributes['updated_at'] - timestamp - contains the product update date
     * $this->items - Item[] - contains the associated items
     */

    public static function validate($request)
    {
        $request->validate([
            "name" => "required|max:255",
            "description" => "required",
            "price" => "required|numeric|gt:0",
            'image' => 'image',
        ]);
    }

    public static function sumPricesByQuantities($products, $productsInSession)
    {
        $total = 0;
        foreach ($products as $product) {
            $total += ($product->getPrice() * $productsInSession[$product->getId()]);
        }

        return $total;
    }

    public function getId()
    {
        return $this->attributes['id'];
    }

    public function getName()
    {
        return $this->attributes['name'];
    }

    public function getDescription()
    {
        return $this->attributes['description'];
    }

    public function getImage()
    {
        return $this->attributes['image'];
    }

    public function getPrice()
    {
        return $this->attributes['price'];
    }

    public function getCreatedAt()
    {
        return $this->attributes['created_at'];
    }

    public function getUpdatedAt()
    {
        return $this->attributes['updated_at'];
    }

    public function setId($id)
    {
        $this->attributes['id'] = $id;
    }

    public function setName($name)
    {
        $this->attributes['name'] = $name;
    }

    public function setDescription($description)
    {
        $this->attributes['description'] = $description;
    }

    public function setImage($image)
    {
        $this->attributes['image'] = $image;
    }

    public function setPrice($price)
    {
        $this->attributes['price'] = $price;
    }

    public function setCreatedAt($created_at)
    {
        $this->attributes['created_at'] = $created_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->attributes['updated_at'] = $updated_at;
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
    }
}