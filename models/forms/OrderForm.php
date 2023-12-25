<?php

namespace app\models\forms;

use app\models\Order;
use app\models\OrderProducts;
use app\models\Product;
use app\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class OrderForm extends Model
{
    public ?string $client_id = null;

    public ?string $name = null;

    public ?string $phone = null;

    public ?string $address = null;

    public ?string $house = null;

    public ?string $apartament = null;

    public ?string $front_door = null;

    public ?string $floor = null;

    public ?string $exact_time = null;

    public ?string $expected_payment = null;

    public ?string $comment = null;

    public int $delivery_type = 0;

    public array $products = [];

    public function rules(): array
    {
        return [
            [['client_id', 'delivery_type'], 'integer'],
            [['name', 'phone', 'address', 'house', 'apartament', 'front_door', 'floor', 'exact_time', 'expected_payment', 'comment'], 'string'],
            [['products'], 'safe'],
            [['name', 'phone', 'products'], 'required'],
            [['products'], 'validateProducts'],
            [['client_id'], 'exist', 'targetClass' => User::class],
        ];
    }

    /**
     * validate $products
     *
     * @param string $attribute
     * @param array $params
     * @return void
     */
    public function validateProducts($attribute, $params): void
    {
        $productIDs = [];

        foreach ($this->products as ['id' => $id, 'count' => $count]) {
            if ((int)$count <= 0) {
                $this->addError('products', "Count for product with ID {$id} should be greater than 0!");
            }

            $productIDs[] = $id;
        }

        $existedProducts = Product::find()
            ->select(['id'])
            ->where(['in', 'id', $productIDs])
            ->asArray()
            ->column();

        $nonExistedProducts = array_diff($productIDs, $existedProducts);

        if ($nonExistedProducts !== []) {
            $productIDs = implode(', ', $nonExistedProducts);

            $this->addError('products', "Products with IDs {$productIDs} not available for order!");
        }
    }

    /**
     * save order
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save(): bool
    {
        if ($this->validate() === false) {
            return false;
        }

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $this->doSave();
        } catch (\Throwable $e) {
            \Yii::debug($e);

            $transaction->rollBack();

            return false;
        }

        $transaction->commit();
        return true;
    }

    /**
     * save order entities to database
     *
     * @return void
     * @throws \Exception
     */
    private function doSave(): void
    {
        $order = new Order();
        $order->setAttributes($this->getAttributes());
        $order->price = $this->countPrice();

        if (empty($this->client_id) === false) {
            $order->client_id = $this->client_id;
        }

        if ($order->save() === false) {
            throw new \Exception('Order not saved!');
        }

        foreach ($this->products as ['id' => $id, 'count' => $count]) {
            $status = (new OrderProducts([
                'order_id' => $order->id,
                'product_id' => $id,
                'product_count' => $count
            ]))->save();

            if ($status === false) {
                throw new \Exception('OrderProduct not saved');
            }
        }
    }


    /**
     * @return int
     */
    private function countPrice(): int
    {
        $price = 0;
        $products = Product::find()->where(['in', 'id', array_column($this->products, 'id')])->all();
        $products = ArrayHelper::map($products, 'id', 'price');

        foreach ($this->products as ['id' => $id, 'count' => $count]) {
            $price += $products[$id] * $count;
        }
        return $price;
    }

}