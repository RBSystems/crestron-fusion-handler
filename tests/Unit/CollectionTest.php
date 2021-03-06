<?php

namespace Tests\Unit;

use DateTime;
use Tests\BasicTest;
use Brueggern\CrestronFusionHandler\CFCollection;
use Brueggern\CrestronFusionHandler\Entities\CFRoom;
use Brueggern\CrestronFusionHandler\Exceptions\CollectionException;

class CollectionTest extends BasicTest
{
    /**
     * @group offline
     */
    public function testAddItem()
    {
        $collection = $this->createCollection();
        $collection->addItem('Foo', 'foo');

        $this->expectException(CollectionException::class);
        $collection->addItem('Foo', 'foo');
    }

    /**
     * @group offline
     */
    public function testGetItem()
    {
        $collection = $this->createCollection();
        $collection->addItem('Foo');

        $this->assertSame('Room 1', $collection->getItem('room1')->name);
        $this->assertSame('Room 2', $collection->getItem(1)->name);
        $this->assertSame('Foo', $collection->getItem(2));
        $this->expectException(CollectionException::class);
        $collection->getItem('bar');
    }

    /**
     * @group offline
     */
    public function testDeleteItemString()
    {
        $collection = $this->createCollection();
        $this->assertSame('Room 2', $collection->getItem(1)->name);
        $collection->deleteItem('room2');

        $this->expectException(CollectionException::class);
        $collection->getItem('room2');
    }

    /**
     * @group offline
     */
    public function testDeleteItemInteger()
    {
        $collection = $this->createCollection();
        $this->assertSame('Room 2', $collection->getItem(1)->name);
        $collection->deleteItem(1);

        $this->expectException(CollectionException::class);
        $collection->getItem(1);
    }

    /**
     * @group offline
     */
    public function testIteration()
    {
        $collection = $this->createCollection();
        foreach ($collection->get() as $item) {
            $this->assertIsString($item->name);
        }
    }

    /**
     * @group offline
     */
    public function testAppendCollection()
    {
        $collection1 = $this->createCollection();
        $collection2 = $this->createCollection();

        $this->assertSame(4, $collection1->append($collection2)->length());
    }

    /**
     * Create a collection object
     *
     * @return void
     */
    private function createCollection()
    {
        $dateTime = new DateTime();

        $data = [
            'id' => '1',
            'name' => 'Room 1',
            'description' => 'this is a string.',
            'lastModifiedAt' => $dateTime,
        ];
        $room1 = new CFRoom($data);

        $data = [
            'id' => '2',
            'name' => 'Room 2',
            'description' => 'this is a string.',
            'lastModifiedAt' => $dateTime,
        ];
        $room2 = new CFRoom($data);

        $collection = new CFCollection();
        $collection->addItem($room1, 'room1');
        $collection->addItem($room2, 'room2');

        return $collection;
    }
}
