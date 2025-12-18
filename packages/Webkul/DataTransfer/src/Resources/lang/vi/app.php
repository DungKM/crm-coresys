<?php

return [
  'importers' => 
  [
    'persons' => 
    [
      'title' => 'Người',
      'validation' => 
      [
        'errors' => 
        [
          'duplicate-email' => 'Email : \'%s\' được tìm thấy nhiều lần trong tệp nhập.',
          'duplicate-phone' => 'Số điện thoại : \'%s\' được tìm thấy nhiều lần trong tệp nhập.',
          'email-not-found' => 'Email: \'%s\' không tìm thấy trong hệ thống.',
        ],
      ],
    ],
    'products' => 
    [
      'title' => 'Các sản phẩm',
      'validation' => 
      [
        'errors' => 
        [
          'sku-not-found' => 'Không tìm thấy sản phẩm có SKU được chỉ định',
        ],
      ],
    ],
    'leads' => 
    [
      'title' => 'Khách hàng tiềm năng',
      'validation' => 
      [
        'errors' => 
        [
          'id-not-found' => 'ID: \'%s\' không tìm thấy trong hệ thống.',
        ],
      ],
    ],
  ],
  'validation' => 
  [
    'errors' => 
    [
      'column-empty-headers' => 'Số cột "%s" có tiêu đề trống.',
      'column-name-invalid' => 'Tên cột không hợp lệ: "%s".',
      'column-not-found' => 'Không tìm thấy cột bắt buộc: %s.',
      'column-numbers' => 'Số cột không tương ứng với số hàng trong tiêu đề.',
      'invalid-attribute' => 'Tiêu đề chứa (các) thuộc tính không hợp lệ: "%s".',
      'system' => 'Đã xảy ra lỗi hệ thống không mong muốn.',
      'wrong-quotes' => 'Dấu ngoặc kép được sử dụng thay cho dấu ngoặc kép thẳng.',
      'already-exists' => ':thuộc tính đã tồn tại.',
    ],
  ],
];
