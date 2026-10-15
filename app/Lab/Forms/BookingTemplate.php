<?php

namespace App\Lab\Forms;

final class BookingTemplate
{
    public static function definition(): array
    {
        $fields = [
            ['customer_name', 'ชื่อผู้จอง', 'text', true],
            ['phone', 'เบอร์โทร', 'text', true],
            ['requested_at', 'วันเวลาที่ต้องการ', 'datetime', true],
            ['party_size', 'จำนวนคน', 'integer', true],
            ['notes', 'หมายเหตุ', 'textarea', false],
        ];

        return ['template' => 'table_booking', 'fields' => array_map(static fn ($field) => [
            'id' => $field[0], 'label' => $field[1], 'type' => $field[2],
            'required' => $field[3], 'archived' => false,
        ], $fields)];
    }
}
