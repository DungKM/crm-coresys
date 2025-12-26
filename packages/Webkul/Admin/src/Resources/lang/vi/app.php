<?php

return [
  'facebook' => [
    'index' => [
        'title' => 'Facebook',
    ],
  ],
  'social-message' => [
    'index' => [
        'title' => 'Chat Socials',
    ],
  ],
  'acl' => 
  [
    'leads' => 'Khách hàng tiềm năng',
    'facebook' => 'Facebook',
    'social-message' => 'Chat Messenger',
    'lead' => 'Chỉ huy',
    'quotes' => 'Báo giá',
    'mail' => 'Thư',
    'inbox' => 'Hộp thư đến',
    'draft' => 'Bản nháp',
    'outbox' => 'Hộp thư đi',
    'sent' => 'Đã gửi',
    'trash' => 'Rác',
    'activities' => 'Các hoạt động',
    'webhook' => 'Webhook',
    'contacts' => 'Danh bạ',
    'persons' => 'Người',
    'organizations' => 'Tổ chức',
    'products' => 'Các sản phẩm',
    'settings' => 'Cài đặt',
    'groups' => 'Nhóm',
    'roles' => 'Vai trò',
    'users' => 'Người dùng',
    'user' => 'người dùng',
    'automation' => 'Tự động hóa',
    'attributes' => 'Thuộc tính',
    'pipelines' => 'Đường ống',
    'sources' => 'Nguồn',
    'types' => 'Các loại',
    'email-templates' => 'Mẫu email',
    'workflows' => 'Quy trình làm việc',
    'other-settings' => 'Cài đặt khác',
    'tags' => 'Thẻ',
    'configuration' => 'Cấu hình',
    'create' => 'Tạo nên',
    'edit' => 'Biên tập',
    'view' => 'Xem',
    'print' => 'In',
    'delete' => 'Xóa bỏ',
    'export' => 'Xuất khẩu',
    'mass-delete' => 'Xóa hàng loạt',
    'data-transfer' => 'Truyền dữ liệu',
    'imports' => 'Nhập khẩu',
    'import' => 'Nhập khẩu',
    'event' => 'Sự kiện',
    'campaigns' => 'Chiến dịch',
  ],
  'users' => 
  [
    'activate-warning' => 'Tài khoản của bạn chưa được kích hoạt. Vui lòng liên hệ với quản trị viên.',
    'login-error' => 'Thông tin đăng nhập không khớp với hồ sơ của chúng tôi.',
    'not-permission' => 'Bạn không có quyền truy cập vào bảng quản trị.',
    'login' => 
    [
      'email' => 'Địa chỉ email',
      'forget-password-link' => 'Quên mật khẩu?',
      'password' => 'Mật khẩu',
      'submit-btn' => 'Đăng nhập',
      'title' => 'Đăng nhập',
    ],
    'forget-password' => 
    [
      'create' => 
      [
        'email' => 'Email đã đăng ký',
        'email-not-exist' => 'Email không tồn tại',
        'page-title' => 'Quên mật khẩu',
        'reset-link-sent' => 'Đã gửi liên kết Đặt lại mật khẩu',
        'sign-in-link' => 'Quay lại Đăng nhập?',
        'submit-btn' => 'Cài lại',
        'title' => 'Khôi phục mật khẩu',
      ],
    ],
    'reset-password' => 
    [
      'back-link-title' => 'Quay lại Đăng nhập?',
      'confirm-password' => 'Xác nhận mật khẩu',
      'email' => 'Email đã đăng ký',
      'password' => 'Mật khẩu',
      'submit-btn' => 'Đặt lại mật khẩu',
      'title' => 'Đặt lại mật khẩu',
    ],
  ],
  'account' => 
  [
    'edit' => 
    [
      'back-btn' => 'Mặt sau',
      'change-password' => 'Thay đổi mật khẩu',
      'confirm-password' => 'Xác nhận mật khẩu',
      'current-password' => 'Mật khẩu hiện tại',
      'email' => 'E-mail',
      'general' => 'Tổng quan',
      'invalid-password' => 'Mật khẩu hiện tại bạn nhập không chính xác.',
      'name' => 'Tên',
      'password' => 'Mật khẩu',
      'profile-image' => 'Hình ảnh hồ sơ',
      'save-btn' => 'Lưu tài khoản',
      'title' => 'Tài khoản của tôi',
      'update-success' => 'Đã cập nhật tài khoản thành công',
      'upload-image-info' => 'Tải lên Hình ảnh hồ sơ (110px X 110px) ở định dạng PNG hoặc JPG',
    ],
  ],
  'components' => 
  [
    'activities' => 
    [
      'actions' => 
      [
        'mail' => 
        [
          'btn' => 'Thư',
          'title' => 'Soạn thư',
          'to' => 'ĐẾN',
          'enter-emails' => 'Nhấn enter để thêm email',
          'cc' => 'CC',
          'bcc' => 'BCC',
          'subject' => 'Chủ thể',
          'send-btn' => 'Gửi',
          'message' => 'Tin nhắn',
        ],
        'file' => 
        [
          'btn' => 'Tài liệu',
          'title' => 'Thêm tập tin',
          'title-control' => 'Tiêu đề',
          'name' => 'Tên',
          'description' => 'Sự miêu tả',
          'file' => 'Tài liệu',
          'save-btn' => 'Lưu tập tin',
        ],
        'note' => 
        [
          'btn' => 'Ghi chú',
          'title' => 'Thêm ghi chú',
          'comment' => 'Bình luận',
          'save-btn' => 'Lưu ghi chú',
        ],
        'activity' => 
        [
          'btn' => 'Hoạt động',
          'title' => 'Thêm hoạt động',
          'title-control' => 'Tiêu đề',
          'description' => 'Sự miêu tả',
          'schedule-from' => 'Lên lịch từ',
          'schedule-to' => 'Lên lịch tới',
          'location' => 'Vị trí',
          'call' => 'Gọi',
          'meeting' => 'Cuộc họp',
          'lunch' => 'Bữa trưa',
          'save-btn' => 'Lưu hoạt động',
          'participants' => 
          [
            'title' => 'Người tham gia',
            'placeholder' => 'Nhập để tìm kiếm người tham gia',
            'users' => 'Người dùng',
            'persons' => 'Người',
            'no-results' => 'Không tìm thấy kết quả...',
          ],
        ],
      ],
      'index' => 
      [
        'all' => 'Tất cả',
        'bcc' => 'Bcc',
        'by-user' => 'Bởi: người dùng',
        'calls' => 'Cuộc gọi',
        'cc' => 'Cc',
        'change-log' => 'Nhật ký thay đổi',
        'delete' => 'Xóa bỏ',
        'edit' => 'Biên tập',
        'emails' => 'Email',
        'empty' => 'Trống',
        'files' => 'Tập tin',
        'from' => 'Từ',
        'location' => 'Vị trí',
        'lunches' => 'Bữa trưa',
        'mark-as-done' => 'Đánh dấu là xong',
        'meetings' => 'Cuộc họp',
        'notes' => 'Ghi chú',
        'participants' => 'Người tham gia',
        'planned' => 'Đã lên kế hoạch',
        'quotes' => 'Báo giá',
        'scheduled-on' => 'Đã lên lịch vào',
        'system' => 'Hệ thống',
        'to' => 'ĐẾN',
        'unlink' => 'Hủy liên kết',
        'view' => 'Xem',
        'empty-placeholders' => 
        [
          'all' => 
          [
            'title' => 'Không tìm thấy hoạt động nào',
            'description' => 'Không tìm thấy hoạt động nào cho việc này. Bạn có thể thêm hoạt động bằng cách nhấp vào nút Hoạt động trên bảng điều khiển bên trái.',
          ],
          'planned' => 
          [
            'title' => 'Không tìm thấy hoạt động theo kế hoạch nào',
            'description' => 'Không có hoạt động theo kế hoạch được tìm thấy cho việc này. Bạn có thể thêm các hoạt động đã lên kế hoạch bằng cách nhấp vào nút Hoạt động trên bảng điều khiển bên trái.',
          ],
          'notes' => 
          [
            'title' => 'Không tìm thấy ghi chú nào',
            'description' => 'Không tìm thấy ghi chú nào cho việc này. Bạn có thể thêm ghi chú bằng cách nhấp vào nút Ghi chú ở bảng bên trái.',
          ],
          'calls' => 
          [
            'title' => 'Không tìm thấy cuộc gọi nào',
            'description' => 'Không tìm thấy cuộc gọi nào cho việc này. Bạn có thể thêm cuộc gọi bằng cách nhấp vào nút Hoạt động trên bảng điều khiển bên trái và chọn Loại cuộc gọi.',
          ],
          'meetings' => 
          [
            'title' => 'Không tìm thấy cuộc họp nào',
            'description' => 'Không tìm thấy cuộc họp nào cho việc này. Bạn có thể thêm cuộc họp bằng cách nhấp vào nút Hoạt động trên bảng điều khiển bên trái và chọn loại Cuộc họp.',
          ],
          'lunches' => 
          [
            'title' => 'Không tìm thấy bữa trưa',
            'description' => 'Không tìm thấy bữa trưa cho việc này. Bạn có thể thêm bữa trưa bằng cách nhấp vào nút Hoạt động ở bảng bên trái và chọn loại Bữa trưa.',
          ],
          'files' => 
          [
            'title' => 'Không tìm thấy tệp nào',
            'description' => 'Không tìm thấy tập tin nào cho việc này. Bạn có thể thêm tệp bằng cách nhấp vào nút Tệp trên bảng điều khiển bên trái.',
          ],
          'emails' => 
          [
            'title' => 'Không tìm thấy email',
            'description' => 'Không tìm thấy email nào cho việc này. Bạn có thể thêm email bằng cách nhấp vào nút Thư trên bảng điều khiển bên trái.',
          ],
          'system' => 
          [
            'title' => 'Không tìm thấy nhật ký thay đổi',
            'description' => 'Không tìm thấy nhật ký thay đổi nào cho việc này.',
          ],
        ],
      ],
    ],
    'media' => 
    [
      'images' => 
      [
        'add-image-btn' => 'Thêm hình ảnh',
        'ai-add-image-btn' => 'AI ma thuật',
        'allowed-types' => 'png, jpeg, jpg',
        'not-allowed-error' => 'Chỉ cho phép các tệp hình ảnh (.jpeg, .jpg, .png, ..).',
        'placeholders' => 
        [
          'front' => 'Đằng trước',
          'next' => 'Kế tiếp',
          'size' => 'Kích cỡ',
          'use-cases' => 'Trường hợp sử dụng',
          'zoom' => 'Phóng',
        ],
      ],
      'videos' => 
      [
        'add-video-btn' => 'Thêm video',
        'allowed-types' => 'mp4, webm, mkv',
        'not-allowed-error' => 'Chỉ cho phép các tệp video (.mp4, .mov, .ogg ..).',
      ],
    ],
    'datagrid' => 
    [
      'index' => 
      [
        'no-records-selected' => 'Không có bản ghi nào được chọn.',
        'must-select-a-mass-action-option' => 'Bạn phải chọn tùy chọn của một hành động đại chúng.',
        'must-select-a-mass-action' => 'Bạn phải chọn một hành động đại chúng.',
      ],
      'toolbar' => 
      [
        'length-of' => ': chiều dài của',
        'of' => 'của',
        'per-page' => 'mỗi trang',
        'results' => ':tổng kết quả',
        'delete' => 'Xóa bỏ',
        'selected' => ':tổng số mục đã chọn',
        'mass-actions' => 
        [
          'submit' => 'Nộp',
          'select-option' => 'Chọn tùy chọn',
          'select-action' => 'Chọn hành động',
        ],
        'filter' => 
        [
          'apply-filters-btn' => 'Áp dụng bộ lọc',
          'back-btn' => 'Mặt sau',
          'create-new-filter' => 'Tạo bộ lọc mới',
          'custom-filters' => 'Bộ lọc tùy chỉnh',
          'delete-error' => 'Đã xảy ra lỗi khi xóa bộ lọc, vui lòng thử lại.',
          'delete-success' => 'Bộ lọc đã được xóa thành công.',
          'empty-description' => 'Không có bộ lọc nào được chọn để lưu. Vui lòng chọn bộ lọc để lưu.',
          'empty-title' => 'Thêm bộ lọc để lưu',
          'name' => 'Tên',
          'quick-filters' => 'Bộ lọc nhanh',
          'save-btn' => 'Cứu',
          'save-filter' => 'Lưu bộ lọc',
          'saved-success' => 'Bộ lọc đã được lưu thành công.',
          'selected-filters' => 'Bộ lọc đã chọn',
          'title' => 'Lọc',
          'update' => 'Cập nhật',
          'update-filter' => 'Cập nhật bộ lọc',
          'updated-success' => 'Bộ lọc đã được cập nhật thành công.',
        ],
        'search' => 
        [
          'title' => 'Tìm kiếm',
        ],
      ],
      'filters' => 
      [
        'select' => 'Lựa chọn',
        'title' => 'Bộ lọc',
        'dropdown' => 
        [
          'searchable' => 
          [
            'at-least-two-chars' => 'Nhập ít nhất 2 ký tự...',
            'no-results' => 'Không tìm thấy kết quả...',
          ],
        ],
        'custom-filters' => 
        [
          'clear-all' => 'Xóa tất cả',
          'title' => 'Bộ lọc tùy chỉnh',
        ],
        'boolean-options' => 
        [
          'false' => 'SAI',
          'true' => 'ĐÚNG VẬY',
        ],
        'date-options' => 
        [
          'last-month' => 'Tháng trước',
          'last-six-months' => '6 tháng qua',
          'last-three-months' => '3 tháng qua',
          'this-month' => 'Tháng này',
          'this-week' => 'Tuần này',
          'this-year' => 'Năm nay',
          'today' => 'Hôm nay',
          'yesterday' => 'Hôm qua',
        ],
      ],
      'table' => 
      [
        'actions' => 'hành động',
        'no-records-available' => 'Không có bản ghi nào.',
      ],
    ],
    'modal' => 
    [
      'confirm' => 
      [
        'agree-btn' => 'Đồng ý',
        'disagree-btn' => 'Không đồng ý',
        'message' => 'Bạn có chắc chắn muốn thực hiện hành động này không?',
        'title' => 'Bạn có chắc không?',
      ],
    ],
    'tags' => 
    [
      'index' => 
      [
        'title' => 'Thẻ',
        'added-tags' => 'Thẻ đã thêm',
        'save-btn' => 'Lưu thẻ',
        'placeholder' => 'Nhập để tìm kiếm thẻ',
        'add-tag' => 'Thêm \\":thuật ngữ\\"...',
        'aquarelle-red' => 'Aquarelle đỏ',
        'crushed-cashew' => 'Hạt điều nghiền',
        'beeswax' => 'Sáp ong',
        'lemon-chiffon' => 'Voan chanh',
        'snow-flurry' => 'Tuyết rơi',
        'honeydew' => 'mật ngọt',
      ],
    ],
    'layouts' => 
    [
      'powered-by' => 
      [
        'description' => 'Salehub - Hệ thống CRM mã nguồn mở mạnh mẽ dành cho doanh nghiệp của bạn',
      ],
      'header' => 
      [
        'mega-search' => 
        [
          'title' => 'Tìm kiếm lớn',
          'tabs' => 
          [
            'leads' => 'Khách hàng tiềm năng',
            'quotes' => 'Báo giá',
            'persons' => 'Người',
            'products' => 'Các sản phẩm',
          ],
          'explore-all-products' => 'Khám phá tất cả sản phẩm',
          'explore-all-leads' => 'Khám phá tất cả khách hàng tiềm năng',
          'explore-all-contacts' => 'Khám phá tất cả Danh bạ',
          'explore-all-quotes' => 'Khám phá tất cả các trích dẫn',
          'explore-all-matching-products' => 'Khám phá tất cả các sản phẩm phù hợp với ":query" (:count)',
          'explore-all-matching-leads' => 'Khám phá tất cả khách hàng tiềm năng phù hợp với ":query" (:count)',
          'explore-all-matching-contacts' => 'Khám phá tất cả các liên hệ khớp với ":query" (:count)',
          'explore-all-matching-quotes' => 'Khám phá tất cả các trích dẫn phù hợp với ":query" (:count)',
        ],
      ],
    ],
    'attributes' => 
    [
      'edit' => 
      [
        'delete' => 'Xóa bỏ',
      ],
      'lookup' => 
      [
        'click-to-add' => 'Bấm để thêm',
        'search' => 'Tìm kiếm...',
        'no-result-found' => 'Không tìm thấy kết quả',
      ],
    ],
    'lookup' => 
    [
      'click-to-add' => 'Bấm để thêm',
      'no-results' => 'Không tìm thấy kết quả',
      'add-as-new' => 'Thêm dưới dạng mới',
      'search' => 'Tìm kiếm...',
    ],
    'flash-group' => 
    [
      'success' => 'Thành công',
      'error' => 'Lỗi',
      'warning' => 'Cảnh báo',
      'info' => 'Thông tin',
    ],
    'tiny-mce' => 
    [
      'http-error' => 'Lỗi HTTP',
      'invalid-json' => 'Phản hồi JSON không hợp lệ từ máy chủ.',
      'upload-failed' => 'Tải tệp lên không thành công. Vui lòng thử lại.',
    ],
  ],
  'quotes' => 
  [
    'index' => 
    [
      'title' => 'Báo giá',
      'create-btn' => 'Tạo báo giá',
      'create-success' => 'Báo giá được tạo thành công.',
      'update-success' => 'Đã cập nhật báo giá thành công.',
      'delete-success' => 'Đã xóa báo giá thành công.',
      'delete-failed' => 'Trích dẫn không thể bị xóa.',
      'datagrid' => 
      [
        'subject' => 'Chủ thể',
        'sales-person' => 'nhân viên bán hàng',
        'expired-at' => 'Hết hạn vào lúc',
        'created-at' => 'Được tạo tại',
        'person' => 'Người',
        'subtotal' => 'Tổng phụ',
        'discount' => 'Giảm giá',
        'tax' => 'Thuế',
        'adjustment' => 'Điều chỉnh',
        'grand-total' => 'Tổng cộng',
        'edit' => 'Biên tập',
        'delete' => 'Xóa bỏ',
        'print' => 'In',
      ],
      'pdf' => 
      [
        'adjustment' => 'Điều chỉnh',
        'amount' => 'Số lượng',
        'billing-address' => 'Địa chỉ thanh toán',
        'date' => 'Ngày',
        'discount' => 'Giảm giá',
        'expired-at' => 'Hết hạn vào lúc',
        'grand-total' => 'Tổng cộng',
        'person' => 'Người',
        'price' => 'Giá',
        'product-name' => 'Tên sản phẩm',
        'quantity' => 'Số lượng',
        'quote-id' => 'ID báo giá',
        'sales-person' => 'nhân viên bán hàng',
        'shipping-address' => 'Địa chỉ giao hàng',
        'sku' => 'Mã hàng',
        'sub-total' => 'Tổng phụ',
        'subject' => 'Chủ thể',
        'tax' => 'Thuế',
        'title' => 'Trích dẫn',
      ],
    ],
    'create' => 
    [
      'title' => 'Tạo báo giá',
      'save-btn' => 'Lưu báo giá',
      'quote-info' => 'Thông tin báo giá',
      'quote-info-info' => 'Đưa thông tin cơ bản của báo giá.',
      'address-info' => 'Thông tin địa chỉ',
      'address-info-info' => 'Thông tin về địa chỉ liên quan đến báo giá.',
      'quote-items' => 'Mục trích dẫn',
      'search-products' => 'Tìm kiếm sản phẩm',
      'link-to-lead' => 'Liên kết để dẫn đầu',
      'quote-item-info' => 'Thêm yêu cầu sản phẩm cho báo giá này.',
      'quote-name' => 'Tên trích dẫn',
      'quantity' => 'Số lượng',
      'price' => 'Giá',
      'discount' => 'Giảm giá',
      'tax' => 'Thuế',
      'total' => 'Tổng cộng',
      'amount' => 'Số lượng',
      'add-item' => '+ Thêm mục',
      'sub-total' => 'Tổng phụ (:biểu tượng)',
      'total-discount' => 'Giảm giá (:biểu tượng)',
      'total-tax' => 'Thuế (:ký hiệu)',
      'total-adjustment' => 'Điều chỉnh (:ký hiệu)',
      'grand-total' => 'Tổng cộng (:biểu tượng)',
      'discount-amount' => 'Số tiền chiết khấu',
      'tax-amount' => 'Số tiền thuế',
      'adjustment-amount' => 'Số tiền điều chỉnh',
      'product-name' => 'Tên sản phẩm',
      'action' => 'Hoạt động',
    ],
    'edit' => 
    [
      'title' => 'Chỉnh sửa trích dẫn',
      'save-btn' => 'Lưu báo giá',
      'quote-info' => 'Thông tin báo giá',
      'quote-info-info' => 'Đưa thông tin cơ bản của báo giá.',
      'address-info' => 'Thông tin địa chỉ',
      'address-info-info' => 'Thông tin về địa chỉ liên quan đến báo giá.',
      'quote-items' => 'Mục trích dẫn',
      'link-to-lead' => 'Liên kết để dẫn đầu',
      'quote-item-info' => 'Thêm yêu cầu sản phẩm cho báo giá này.',
      'quote-name' => 'Tên trích dẫn',
      'quantity' => 'Số lượng',
      'price' => 'Giá',
      'search-products' => 'Tìm kiếm sản phẩm',
      'discount' => 'Giảm giá',
      'tax' => 'Thuế',
      'total' => 'Tổng cộng',
      'amount' => 'Số lượng',
      'add-item' => '+ Thêm mục',
      'sub-total' => 'Tổng phụ (:biểu tượng)',
      'total-discount' => 'Giảm giá (:biểu tượng)',
      'total-tax' => 'Thuế (:ký hiệu)',
      'total-adjustment' => 'Điều chỉnh (:ký hiệu)',
      'grand-total' => 'Tổng cộng (:biểu tượng)',
      'discount-amount' => 'Số tiền chiết khấu',
      'tax-amount' => 'Số tiền thuế',
      'adjustment-amount' => 'Số tiền điều chỉnh',
      'product-name' => 'Tên sản phẩm',
      'action' => 'Hoạt động',
    ],
  ],
  'contacts' => 
  [
    'persons' => 
    [
      'index' => 
      [
        'title' => 'Người',
        'create-btn' => 'Tạo người',
        'create-success' => 'Người được tạo thành công.',
        'update-success' => 'Người được cập nhật thành công.',
        'all-delete-success' => 'Tất cả những người được chọn đã bị xóa thành công.',
        'partial-delete-warning' => 'Một số người đã bị xóa thành công. Không thể xóa những người khác vì chúng được liên kết với khách hàng tiềm năng.',
        'none-delete-warning' => 'Không ai trong số những người được chọn có thể bị xóa vì họ được liên kết với khách hàng tiềm năng.',
        'no-selection' => 'Không có người nào được chọn để xóa.',
        'delete-failed' => 'Không thể xóa những người đã chọn.',
        'datagrid' => 
        [
          'contact-numbers' => 'Số liên lạc',
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'emails' => 'Email',
          'id' => 'NHẬN DẠNG',
          'view' => 'Xem',
          'name' => 'Tên',
          'organization-name' => 'Tên tổ chức',
        ],
      ],
      'view' => 
      [
        'title' => ':tên',
        'about-person' => 'Về người',
        'about-organization' => 'Giới thiệu về tổ chức',
        'activities' => 
        [
          'index' => 
          [
            'all' => 'Tất cả',
            'calls' => 'Cuộc gọi',
            'meetings' => 'Cuộc họp',
            'lunches' => 'Bữa trưa',
            'files' => 'Tập tin',
            'quotes' => 'Báo giá',
            'notes' => 'Ghi chú',
            'emails' => 'Email',
            'by-user' => 'Bởi: người dùng',
            'scheduled-on' => 'Đã lên lịch vào',
            'location' => 'Vị trí',
            'participants' => 'Người tham gia',
            'mark-as-done' => 'Đánh dấu là xong',
            'delete' => 'Xóa bỏ',
            'edit' => 'Biên tập',
          ],
          'actions' => 
          [
            'mail' => 
            [
              'btn' => 'Thư',
              'title' => 'Soạn thư',
              'to' => 'ĐẾN',
              'cc' => 'CC',
              'bcc' => 'BCC',
              'subject' => 'Chủ thể',
              'send-btn' => 'Gửi',
              'message' => 'Tin nhắn',
            ],
            'file' => 
            [
              'btn' => 'Tài liệu',
              'title' => 'Thêm tập tin',
              'title-control' => 'Tiêu đề',
              'name' => 'Tên tệp',
              'description' => 'Sự miêu tả',
              'file' => 'Tài liệu',
              'save-btn' => 'Lưu tập tin',
            ],
            'note' => 
            [
              'btn' => 'Ghi chú',
              'title' => 'Thêm ghi chú',
              'comment' => 'Bình luận',
              'save-btn' => 'Lưu ghi chú',
            ],
            'activity' => 
            [
              'btn' => 'Hoạt động',
              'title' => 'Thêm hoạt động',
              'title-control' => 'Tiêu đề',
              'description' => 'Sự miêu tả',
              'schedule-from' => 'Lên lịch từ',
              'schedule-to' => 'Lên lịch đến',
              'location' => 'Vị trí',
              'call' => 'Gọi',
              'meeting' => 'Cuộc họp',
              'lunch' => 'Bữa trưa',
              'save-btn' => 'Lưu hoạt động',
            ],
          ],
        ],
        'tags' => 
        [
          'create-success' => 'Đã tạo thẻ thành công.',
          'destroy-success' => 'Đã xóa thẻ thành công.',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo người',
        'save-btn' => 'Lưu người',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa người',
        'save-btn' => 'Lưu người',
      ],
    ],
    'organizations' => 
    [
      'index' => 
      [
        'title' => 'Tổ chức',
        'create-btn' => 'Tạo tổ chức',
        'create-success' => 'Tổ chức được tạo thành công.',
        'update-success' => 'Tổ chức được cập nhật thành công.',
        'delete-success' => 'Đã xóa tổ chức thành công.',
        'delete-failed' => 'Tổ chức không thể bị xóa.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
          'persons-count' => 'Số người',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo tổ chức',
        'save-btn' => 'Lưu tổ chức',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa tổ chức',
        'save-btn' => 'Lưu tổ chức',
      ],
    ],
  ],
  'products' => 
  [
    'index' => 
    [
      'title' => 'Các sản phẩm',
      'create-btn' => 'Tạo sản phẩm',
      'create-success' => 'Sản phẩm được tạo thành công.',
      'update-success' => 'Sản phẩm được cập nhật thành công.',
      'delete-success' => 'Sản phẩm đã được xóa thành công.',
      'delete-failed' => 'Sản phẩm không thể bị xóa.',
      'datagrid' => 
      [
        'allocated' => 'Đã phân bổ',
        'delete' => 'Xóa bỏ',
        'edit' => 'Biên tập',
        'id' => 'NHẬN DẠNG',
        'in-stock' => 'Còn hàng',
        'name' => 'Tên',
        'on-hand' => 'Trên tay',
        'tag-name' => 'Tên thẻ',
        'price' => 'Giá',
        'sku' => 'Mã hàng',
        'view' => 'Xem',
      ],
    ],
    'create' => 
    [
      'save-btn' => 'Lưu sản phẩm',
      'title' => 'Tạo sản phẩm',
      'general' => 'Tổng quan',
      'price' => 'Giá',
    ],
    'edit' => 
    [
      'title' => 'Chỉnh sửa sản phẩm',
      'save-btn' => 'Lưu sản phẩm',
      'general' => 'Tổng quan',
      'price' => 'Giá',
    ],
    'view' => 
    [
      'sku' => 'Mã hàng',
      'all' => 'Tất cả',
      'notes' => 'Ghi chú',
      'files' => 'Tập tin',
      'inventories' => 'Hàng tồn kho',
      'change-logs' => 'Nhật ký thay đổi',
      'attributes' => 
      [
        'about-product' => 'Về sản phẩm',
      ],
      'inventory' => 
      [
        'source' => 'Nguồn',
        'in-stock' => 'Còn hàng',
        'allocated' => 'Đã phân bổ',
        'on-hand' => 'Trên tay',
        'actions' => 'hành động',
        'assign' => 'Giao phó',
        'add-source' => 'Thêm nguồn',
        'location' => 'Vị trí',
        'add-more' => 'Thêm nhiều hơn nữa',
        'save' => 'Cứu',
      ],
    ],
  ],
  'settings' => 
  [
    'title' => 'Cài đặt',
    'groups' => 
    [
      'index' => 
      [
        'create-btn' => 'Tạo nhóm',
        'title' => 'Nhóm',
        'create-success' => 'Nhóm được tạo thành công.',
        'update-success' => 'Nhóm được cập nhật thành công.',
        'destroy-success' => 'Đã xóa nhóm thành công.',
        'delete-failed' => 'Nhóm không thể bị xóa.',
        'delete-failed-associated-users' => 'Không thể xóa nhóm vì nhóm này đang được người dùng sử dụng.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'description' => 'Sự miêu tả',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
        ],
        'edit' => 
        [
          'title' => 'Chỉnh sửa nhóm',
        ],
        'create' => 
        [
          'name' => 'Tên',
          'title' => 'Tạo nhóm',
          'description' => 'Sự miêu tả',
          'save-btn' => 'Lưu nhóm',
        ],
      ],
    ],
    'roles' => 
    [
      'index' => 
      [
        'being-used' => 'Không thể xóa vai trò vì vai trò này đang được sử dụng trong người dùng quản trị viên.',
        'create-btn' => 'Tạo vai trò',
        'create-success' => 'Vai trò được tạo thành công.',
        'current-role-delete-error' => 'Không thể xóa vai trò được gán cho người dùng hiện tại.',
        'delete-failed' => 'Không thể xóa vai trò.',
        'delete-success' => 'Đã xóa vai trò thành công.',
        'last-delete-error' => 'Cần có ít nhất một vai trò.',
        'settings' => 'Cài đặt',
        'title' => 'Vai trò',
        'update-success' => 'Đã cập nhật vai trò thành công.',
        'user-define-error' => 'Không thể xóa vai trò hệ thống.',
        'datagrid' => 
        [
          'all' => 'Tất cả',
          'custom' => 'Phong tục',
          'delete' => 'Xóa bỏ',
          'description' => 'Sự miêu tả',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
          'permission-type' => 'Loại quyền',
        ],
      ],
      'create' => 
      [
        'access-control' => 'Kiểm soát truy cập',
        'all' => 'Tất cả',
        'back-btn' => 'Mặt sau',
        'custom' => 'Phong tục',
        'description' => 'Sự miêu tả',
        'general' => 'Tổng quan',
        'name' => 'Tên',
        'permissions' => 'Quyền',
        'save-btn' => 'Lưu vai trò',
        'title' => 'Tạo vai trò',
      ],
      'edit' => 
      [
        'access-control' => 'Kiểm soát truy cập',
        'all' => 'Tất cả',
        'back-btn' => 'Mặt sau',
        'custom' => 'Phong tục',
        'description' => 'Sự miêu tả',
        'general' => 'Tổng quan',
        'name' => 'Tên',
        'permissions' => 'Quyền',
        'save-btn' => 'Lưu vai trò',
        'title' => 'Chỉnh sửa vai trò',
      ],
    ],
    'types' => 
    [
      'index' => 
      [
        'create-btn' => 'Tạo loại',
        'create-success' => 'Loại được tạo thành công.',
        'delete-failed' => 'Loại không thể xóa được.',
        'delete-success' => 'Loại đã xóa thành công.',
        'title' => 'Các loại',
        'update-success' => 'Loại được cập nhật thành công.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'description' => 'Sự miêu tả',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
        ],
        'create' => 
        [
          'name' => 'Tên',
          'save-btn' => 'Lưu loại',
          'title' => 'Tạo loại',
        ],
        'edit' => 
        [
          'title' => 'Chỉnh sửa loại',
        ],
      ],
    ],
    'sources' => 
    [
      'index' => 
      [
        'title' => 'Nguồn',
        'create-btn' => 'Tạo nguồn',
        'create-success' => 'Nguồn được tạo thành công.',
        'delete-failed' => 'Nguồn không thể xóa được.',
        'delete-success' => 'Đã xóa nguồn thành công.',
        'update-success' => 'Đã cập nhật nguồn thành công.',
        'delete-failed-associated-leads' => 'Không thể xóa nguồn vì nó được liên kết với khách hàng tiềm năng hiện có. Vui lòng tách hoặc cập nhật những khách hàng tiềm năng đó trước khi xóa.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
        ],
        'create' => 
        [
          'name' => 'Tên',
          'save-btn' => 'Lưu nguồn',
          'title' => 'Tạo nguồn',
        ],
        'edit' => 
        [
          'title' => 'Chỉnh sửa nguồn',
        ],
      ],
    ],
    'workflows' => 
    [
      'index' => 
      [
        'title' => 'Quy trình làm việc',
        'create-btn' => 'Tạo quy trình làm việc',
        'create-success' => 'Quy trình làm việc được tạo thành công.',
        'update-success' => 'Quy trình làm việc được cập nhật thành công.',
        'delete-success' => 'Đã xóa quy trình làm việc thành công.',
        'delete-failed' => 'Không thể xóa quy trình làm việc.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'description' => 'Sự miêu tả',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
        ],
      ],
      'helpers' => 
      [
        'update-related-leads' => 'Cập nhật khách hàng tiềm năng liên quan',
        'send-email-to-sales-owner' => 'Gửi email cho chủ sở hữu bán hàng',
        'send-email-to-participants' => 'Gửi email cho người tham gia',
        'add-webhook' => 'Thêm Webhook',
        'update-lead' => 'Cập nhật khách hàng tiềm năng',
        'update-person' => 'Cập nhật người',
        'send-email-to-person' => 'Gửi email cho người',
        'add-tag' => 'Thêm thẻ',
        'add-note-as-activity' => 'Thêm ghi chú làm hoạt động',
        'update-quote' => 'Cập nhật báo giá',
      ],
      'create' => 
      [
        'title' => 'Tạo quy trình làm việc',
        'event' => 'Sự kiện',
        'back-btn' => 'Mặt sau',
        'save-btn' => 'Lưu quy trình làm việc',
        'name' => 'Tên',
        'basic-details' => 'Chi tiết cơ bản',
        'description' => 'Sự miêu tả',
        'actions' => 'hành động',
        'basic-details-info' => 'Đưa thông tin cơ bản của quy trình làm việc.',
        'event-info' => 'Một sự kiện kích hoạt, kiểm tra, điều kiện và thực hiện các hành động được xác định trước.',
        'conditions' => 'Điều kiện',
        'conditions-info' => 'Điều kiện là các kịch bản kiểm tra quy tắc, được kích hoạt trong những trường hợp cụ thể.',
        'actions-info' => 'Một hành động không chỉ làm giảm khối lượng công việc mà còn giúp việc tự động hóa CRM trở nên dễ dàng hơn',
        'value' => 'Giá trị',
        'condition-type' => 'Loại tình trạng',
        'all-condition-are-true' => 'Tất cả điều kiện đều đúng',
        'any-condition-are-true' => 'Mọi điều kiện đều đúng',
        'add-condition' => 'Thêm điều kiện',
        'add-action' => 'Thêm hành động',
        'yes' => 'Đúng',
        'no' => 'KHÔNG',
        'email' => 'E-mail',
        'is-equal-to' => 'Bằng với',
        'is-not-equal-to' => 'Không bằng',
        'equals-or-greater-than' => 'Bằng hoặc lớn hơn',
        'equals-or-less-than' => 'Bằng hoặc nhỏ hơn',
        'greater-than' => 'Lớn hơn',
        'less-than' => 'Ít hơn',
        'type' => 'Kiểu',
        'contain' => 'Bao gồm',
        'contains' => 'Chứa',
        'does-not-contain' => 'Không chứa',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa quy trình làm việc',
        'event' => 'Sự kiện',
        'back-btn' => 'Mặt sau',
        'save-btn' => 'Lưu quy trình làm việc',
        'name' => 'Tên',
        'basic-details' => 'Chi tiết cơ bản',
        'description' => 'Sự miêu tả',
        'actions' => 'hành động',
        'type' => 'Kiểu',
        'basic-details-info' => 'Đưa thông tin cơ bản của quy trình làm việc.',
        'event-info' => 'Một sự kiện kích hoạt, kiểm tra, điều kiện và thực hiện các hành động được xác định trước.',
        'conditions' => 'Điều kiện',
        'conditions-info' => 'Điều kiện là các kịch bản kiểm tra quy tắc, được kích hoạt trong những trường hợp cụ thể.',
        'actions-info' => 'Một hành động không chỉ làm giảm khối lượng công việc mà còn giúp việc tự động hóa CRM trở nên dễ dàng hơn',
        'value' => 'Giá trị',
        'condition-type' => 'Loại tình trạng',
        'all-condition-are-true' => 'Tất cả điều kiện đều đúng',
        'any-condition-are-true' => 'Mọi điều kiện đều đúng',
        'add-condition' => 'Thêm điều kiện',
        'add-action' => 'Thêm hành động',
        'yes' => 'Đúng',
        'no' => 'KHÔNG',
        'email' => 'E-mail',
        'is-equal-to' => 'Bằng với',
        'is-not-equal-to' => 'Không bằng',
        'equals-or-greater-than' => 'Bằng hoặc lớn hơn',
        'equals-or-less-than' => 'Bằng hoặc nhỏ hơn',
        'greater-than' => 'Lớn hơn',
        'less-than' => 'Ít hơn',
        'contain' => 'Bao gồm',
        'contains' => 'Chứa',
        'does-not-contain' => 'Không chứa',
      ],
    ],
    'webforms' => 
    [
      'index' => 
      [
        'title' => 'Biểu mẫu web',
        'create-btn' => 'Tạo biểu mẫu web',
        'create-success' => 'Biểu mẫu web được tạo thành công.',
        'update-success' => 'Biểu mẫu web được cập nhật thành công.',
        'delete-success' => 'Biểu mẫu web đã được xóa thành công.',
        'delete-failed' => 'Không thể xóa biểu mẫu web.',
        'datagrid' => 
        [
          'id' => 'NHẬN DẠNG',
          'title' => 'Tiêu đề',
          'edit' => 'Biên tập',
          'delete' => 'Xóa bỏ',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo biểu mẫu web',
        'add-attribute-btn' => 'Nút Thêm thuộc tính',
        'attribute-label-color' => 'Màu nhãn thuộc tính',
        'attributes' => 'Thuộc tính',
        'attributes-info' => 'Thêm thuộc tính tùy chỉnh vào biểu mẫu.',
        'background-color' => 'Màu nền',
        'create-lead' => 'Tạo khách hàng tiềm năng',
        'customize-webform' => 'Tùy chỉnh biểu mẫu web',
        'customize-webform-info' => 'Tùy chỉnh biểu mẫu web của bạn với các màu thành phần bạn chọn.',
        'description' => 'Sự miêu tả',
        'display-custom-message' => 'Hiển thị tin nhắn tùy chỉnh',
        'form-background-color' => 'Màu nền biểu mẫu',
        'form-submit-btn-color' => 'Màu nút gửi biểu mẫu',
        'form-submit-button-color' => 'Màu nút gửi biểu mẫu',
        'form-title-color' => 'Màu tiêu đề biểu mẫu',
        'general' => 'Tổng quan',
        'leads' => 'Khách hàng tiềm năng',
        'person' => 'Người',
        'save-btn' => 'Lưu biểu mẫu web',
        'submit-button-label' => 'Gửi nhãn nút',
        'submit-success-action' => 'Gửi hành động thành công',
        'redirect-to-url' => 'Chuyển hướng đến Url',
        'choose-value' => 'Chọn giá trị',
        'select-file' => 'Chọn tệp',
        'select-image' => 'Chọn hình ảnh',
        'enter-value' => 'Nhập giá trị',
      ],
      'edit' => 
      [
        'add-attribute-btn' => 'Nút Thêm thuộc tính',
        'attribute-label-color' => 'Màu nhãn thuộc tính',
        'attributes' => 'Thuộc tính',
        'attributes-info' => 'Thêm thuộc tính tùy chỉnh vào biểu mẫu.',
        'background-color' => 'Màu nền',
        'choose-value' => 'Chọn giá trị',
        'code-snippet' => 'Đoạn mã',
        'copied' => 'Đã sao chép',
        'copy' => 'Sao chép',
        'create-lead' => 'Tạo khách hàng tiềm năng',
        'customize-webform' => 'Tùy chỉnh biểu mẫu web',
        'customize-webform-info' => 'Tùy chỉnh biểu mẫu web của bạn với các màu thành phần bạn chọn.',
        'description' => 'Sự miêu tả',
        'display-custom-message' => 'Hiển thị tin nhắn tùy chỉnh',
        'embed' => 'Nhúng',
        'enter-value' => 'Nhập giá trị',
        'form-background-color' => 'Màu nền biểu mẫu',
        'form-submit-btn-color' => 'Màu nút gửi biểu mẫu',
        'form-submit-button-color' => 'Màu nút gửi biểu mẫu',
        'form-title-color' => 'Màu tiêu đề biểu mẫu',
        'general' => 'Tổng quan',
        'leads' => 'Khách hàng tiềm năng',
        'person' => 'Người',
        'preview' => 'Xem trước',
        'public-url' => 'URL công khai',
        'redirect-to-url' => 'Chuyển hướng đến URL',
        'save-btn' => 'Lưu biểu mẫu web',
        'select-file' => 'Chọn tệp',
        'select-image' => 'Chọn hình ảnh',
        'submit-button-label' => 'Gửi nhãn nút',
        'submit-success-action' => 'Gửi hành động thành công',
        'title' => 'Chỉnh sửa biểu mẫu web',
      ],
    ],
    'email-template' => 
    [
      'index' => 
      [
        'create-btn' => 'Tạo mẫu email',
        'title' => 'Mẫu email',
        'create-success' => 'Mẫu email được tạo thành công.',
        'update-success' => 'Mẫu email được cập nhật thành công.',
        'delete-success' => 'Mẫu email đã được xóa thành công.',
        'delete-failed' => 'Mẫu email không thể xóa được.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
          'subject' => 'Chủ thể',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo mẫu email',
        'save-btn' => 'Lưu mẫu email',
        'email-template' => 'Mẫu email',
        'subject' => 'Chủ thể',
        'content' => 'Nội dung',
        'subject-placeholders' => 'Phần giữ chỗ chủ đề',
        'general' => 'Tổng quan',
        'name' => 'Tên',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa mẫu email',
        'save-btn' => 'Lưu mẫu email',
        'email-template' => 'Mẫu email',
        'subject' => 'Chủ thể',
        'content' => 'Nội dung',
        'subject-placeholders' => 'Phần giữ chỗ chủ đề',
        'general' => 'Tổng quan',
        'name' => 'Tên',
      ],
    ],
    'marketing' => 
    [
      'events' => 
      [
        'index' => 
        [
          'create-btn' => 'Tạo sự kiện',
          'title' => 'Sự kiện',
          'create-success' => 'Sự kiện được tạo thành công.',
          'update-success' => 'Sự kiện được cập nhật thành công.',
          'delete-success' => 'Đã xóa sự kiện thành công.',
          'delete-failed' => 'Sự kiện không thể bị xóa.',
          'mass-delete-success' => 'Đã xóa sự kiện thành công',
          'datagrid' => 
          [
            'delete' => 'Xóa bỏ',
            'edit' => 'Biên tập',
            'id' => 'NHẬN DẠNG',
            'name' => 'Tên',
            'description' => 'Sự miêu tả',
            'date' => 'Ngày',
          ],
          'create' => 
          [
            'title' => 'Tạo sự kiện',
            'name' => 'Tên',
            'date' => 'Ngày',
            'description' => 'Sự miêu tả',
            'save-btn' => 'Lưu sự kiện',
          ],
          'edit' => 
          [
            'title' => 'Chỉnh sửa sự kiện',
          ],
        ],
      ],
      'campaigns' => 
      [
        'index' => 
        [
          'create-btn' => 'Tạo chiến dịch',
          'title' => 'Chiến dịch',
          'create-success' => 'Chiến dịch được tạo thành công.',
          'update-success' => 'Chiến dịch được cập nhật thành công.',
          'delete-success' => 'Đã xóa chiến dịch thành công.',
          'delete-failed' => 'Chiến dịch không thể bị xóa.',
          'mass-delete-success' => 'Đã xóa chiến dịch thành công.',
          'datagrid' => 
          [
            'id' => 'NHẬN DẠNG',
            'name' => 'Tên',
            'subject' => 'Chủ thể',
            'status' => 'Trạng thái',
            'active' => 'Tích cực',
            'inactive' => 'Không hoạt động',
            'edit' => 'Biên tập',
            'delete' => 'Xóa bỏ',
          ],
          'create' => 
          [
            'title' => 'Tạo chiến dịch',
            'name' => 'Tên',
            'type' => 'Kiểu',
            'subject' => 'Chủ thể',
            'event' => 'Sự kiện',
            'email-template' => 'Mẫu email',
            'status' => 'Trạng thái',
          ],
          'edit' => 
          [
            'title' => 'Chỉnh sửa Chiến dịch',
          ],
        ],
      ],
    ],
    'tags' => 
    [
      'index' => 
      [
        'create-btn' => 'Tạo thẻ',
        'title' => 'Thẻ',
        'create-success' => 'Đã tạo thẻ thành công.',
        'update-success' => 'Đã cập nhật thẻ thành công.',
        'delete-success' => 'Đã xóa thẻ thành công.',
        'delete-failed' => 'Không thể xóa thẻ.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
          'users' => 'Người dùng',
          'created-at' => 'Được tạo tại',
        ],
        'create' => 
        [
          'name' => 'Tên',
          'save-btn' => 'Lưu thẻ',
          'title' => 'Tạo thẻ',
          'color' => 'Màu sắc',
        ],
        'edit' => 
        [
          'title' => 'Chỉnh sửa thẻ',
        ],
      ],
    ],
    'users' => 
    [
      'index' => 
      [
        'create-btn' => 'Tạo người dùng',
        'create-success' => 'Người dùng đã tạo thành công.',
        'delete-failed' => 'Người dùng không thể bị xóa.',
        'delete-success' => 'Người dùng đã xóa thành công.',
        'last-delete-error' => 'Cần có ít nhất một người dùng.',
        'mass-delete-failed' => 'Người dùng không thể bị xóa.',
        'mass-delete-success' => 'Người dùng đã xóa thành công.',
        'mass-update-failed' => 'Người dùng không thể được cập nhật.',
        'mass-update-success' => 'Người dùng đã cập nhật thành công.',
        'title' => 'Người dùng',
        'update-success' => 'Người dùng đã cập nhật thành công.',
        'user-define-error' => 'Không thể xóa người dùng hệ thống.',
        'active' => 'Tích cực',
        'inactive' => 'Không hoạt động',
        'datagrid' => 
        [
          'active' => 'Tích cực',
          'created-at' => 'Được tạo tại',
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'email' => 'E-mail',
          'id' => 'NHẬN DẠNG',
          'inactive' => 'Không hoạt động',
          'name' => 'Tên',
          'status' => 'Trạng thái',
          'update-status' => 'Cập nhật trạng thái',
          'users' => 'Người dùng',
        ],
        'create' => 
        [
          'confirm-password' => 'Xác nhận mật khẩu',
          'email' => 'E-mail',
          'general' => 'Tổng quan',
          'global' => 'Toàn cầu',
          'group' => 'Nhóm',
          'individual' => 'Cá nhân',
          'name' => 'Tên',
          'password' => 'Mật khẩu',
          'permission' => 'Sự cho phép',
          'role' => 'Vai trò',
          'save-btn' => 'Lưu người dùng',
          'status' => 'Trạng thái',
          'title' => 'Tạo người dùng',
          'view-permission' => 'Xem quyền',
          'select-at-lest-one-group' => 'Chọn ít nhất một nhóm',
        ],
        'edit' => 
        [
          'title' => 'Chỉnh sửa người dùng',
        ],
      ],
    ],
    'pipelines' => 
    [
      'index' => 
      [
        'title' => 'Đường ống',
        'create-btn' => 'Tạo đường ống',
        'create-success' => 'Đường ống được tạo thành công.',
        'update-success' => 'Đường ống được cập nhật thành công.',
        'default-required' => 'Cần có ít nhất một đường dẫn mặc định.',
        'delete-success' => 'Đã xóa đường ống thành công.',
        'delete-failed' => 'Đường ống không thể bị xóa.',
        'default-delete-error' => 'Đường ống mặc định không thể bị xóa.',
        'datagrid' => 
        [
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'id' => 'NHẬN DẠNG',
          'is-default' => 'Là mặc định',
          'name' => 'Tên',
          'no' => 'KHÔNG',
          'rotten-days' => 'Ngày Thối',
          'yes' => 'Đúng',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo đường ống',
        'save-btn' => 'Lưu đường dẫn',
        'name' => 'Tên',
        'rotten-days' => 'Ngày Thối',
        'mark-as-default' => 'Đánh dấu là mặc định',
        'general' => 'Tổng quan',
        'probability' => 'Xác suất(%)',
        'new-stage' => 'Mới',
        'won-stage' => 'Thắng',
        'lost-stage' => 'Mất',
        'stage-btn' => 'Thêm sân khấu',
        'stages' => 'Giai đoạn',
        'duplicate-name' => 'Trường "Tên" không thể trùng lặp',
        'delete-stage' => 'Xóa giai đoạn',
        'add-new-stages' => 'Thêm giai đoạn mới',
        'add-stage-info' => 'Thêm giai đoạn mới cho Đường ống của bạn',
        'newly-added' => 'Mới được thêm vào',
        'stage-delete-success' => 'Đã xóa giai đoạn thành công',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa quy trình',
        'save-btn' => 'Lưu đường dẫn',
        'name' => 'Tên',
        'rotten-days' => 'Ngày Thối',
        'mark-as-default' => 'Đánh dấu là mặc định',
        'general' => 'Tổng quan',
        'probability' => 'Xác suất(%)',
        'new-stage' => 'Mới',
        'won-stage' => 'Thắng',
        'lost-stage' => 'Mất',
        'stage-btn' => 'Thêm sân khấu',
        'stages' => 'Giai đoạn',
        'duplicate-name' => 'Trường "Tên" không thể trùng lặp',
        'delete-stage' => 'Xóa giai đoạn',
        'add-new-stages' => 'Thêm giai đoạn mới',
        'add-stage-info' => 'Thêm giai đoạn mới cho Đường ống của bạn',
        'stage-delete-success' => 'Đã xóa giai đoạn thành công',
      ],
    ],
    'webhooks' => 
    [
      'index' => 
      [
        'title' => 'Webhook',
        'create-btn' => 'Tạo Webhook',
        'create-success' => 'Webhook được tạo thành công.',
        'update-success' => 'Webhook được cập nhật thành công.',
        'delete-success' => 'Webhook đã được xóa thành công.',
        'delete-failed' => 'Không thể xóa webhook.',
        'datagrid' => 
        [
          'id' => 'NHẬN DẠNG',
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'name' => 'Tên',
          'entity-type' => 'Loại thực thể',
          'end-point' => 'Điểm cuối',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo Webhook',
        'save-btn' => 'Lưu Webhook',
        'info' => 'Nhập thông tin chi tiết của webhooks',
        'url-and-parameters' => 'URL và thông số',
        'method' => 'Phương pháp',
        'post' => 'Bưu kiện',
        'put' => 'Đặt',
        'url-endpoint' => 'Điểm cuối url',
        'parameters' => 'Thông số',
        'add-new-parameter' => 'Thêm thông số mới',
        'url-preview' => 'Xem trước url:',
        'headers' => 'Tiêu đề',
        'add-new-header' => 'Thêm tiêu đề mới',
        'body' => 'Thân hình',
        'default' => 'Mặc định',
        'x-www-form-urlencoded' => 'x-www-form-urlencoded',
        'key-and-value' => 'Khóa và Giá trị',
        'add-new-payload' => 'Thêm tải trọng mới',
        'raw' => 'thô',
        'general' => 'Tổng quan',
        'name' => 'Tên',
        'entity-type' => 'Loại thực thể',
        'insert-placeholder' => 'Chèn phần giữ chỗ',
        'description' => 'Sự miêu tả',
        'json' => 'JSON',
        'text' => 'Chữ',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa Webhook',
        'edit-btn' => 'Lưu Webhook',
        'save-btn' => 'Lưu Webhook',
        'info' => 'Nhập thông tin chi tiết của webhooks',
        'url-and-parameters' => 'URL và thông số',
        'method' => 'Phương pháp',
        'post' => 'Bưu kiện',
        'put' => 'Đặt',
        'url-endpoint' => 'Điểm cuối url',
        'parameters' => 'Thông số',
        'add-new-parameter' => 'Thêm thông số mới',
        'url-preview' => 'Xem trước url:',
        'headers' => 'Tiêu đề',
        'add-new-header' => 'Thêm tiêu đề mới',
        'body' => 'Thân hình',
        'default' => 'Mặc định',
        'x-www-form-urlencoded' => 'x-www-form-urlencoded',
        'key-and-value' => 'Khóa và Giá trị',
        'add-new-payload' => 'Thêm tải trọng mới',
        'raw' => 'thô',
        'general' => 'Tổng quan',
        'name' => 'Tên',
        'entity-type' => 'Loại thực thể',
        'insert-placeholder' => 'Chèn phần giữ chỗ',
        'description' => 'Sự miêu tả',
        'json' => 'JSON',
        'text' => 'Chữ',
      ],
    ],
    'warehouses' => 
    [
      'index' => 
      [
        'title' => 'Kho hàng',
        'create-btn' => 'Tạo kho',
        'create-success' => 'Kho được tạo thành công.',
        'name-exists' => 'Tên kho đã tồn tại.',
        'update-success' => 'Đã cập nhật kho thành công.',
        'delete-success' => 'Đã xóa kho thành công.',
        'delete-failed' => 'Kho không thể bị xóa.',
        'datagrid' => 
        [
          'id' => 'NHẬN DẠNG',
          'name' => 'Tên',
          'contact-name' => 'Tên liên hệ',
          'delete' => 'Xóa bỏ',
          'edit' => 'Biên tập',
          'view' => 'Xem',
          'created-at' => 'Được tạo tại',
          'products' => 'Các sản phẩm',
          'contact-emails' => 'Email liên hệ',
          'contact-numbers' => 'Số liên lạc',
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo kho',
        'save-btn' => 'Lưu kho',
        'contact-info' => 'Thông tin liên hệ',
      ],
      'edit' => 
      [
        'title' => 'Chỉnh sửa kho',
        'save-btn' => 'Lưu kho',
        'contact-info' => 'Thông tin liên hệ',
      ],
      'view' => 
      [
        'all' => 'Tất cả',
        'notes' => 'Ghi chú',
        'files' => 'Tập tin',
        'location' => 'Vị trí',
        'change-logs' => 'Nhật ký thay đổi',
        'locations' => 
        [
          'action' => 'Hoạt động',
          'add-location' => 'Thêm vị trí',
          'create-success' => 'Vị trí được tạo thành công.',
          'delete' => 'Xóa bỏ',
          'delete-failed' => 'Không thể xóa vị trí.',
          'delete-success' => 'Đã xóa vị trí thành công.',
          'name' => 'Tên',
          'save-btn' => 'Cứu',
        ],
        'general-information' => 
        [
          'title' => 'Thông tin chung',
        ],
        'contact-information' => 
        [
          'title' => 'Thông tin liên hệ',
        ],
      ],
    ],
    'attributes' => 
    [
      'index' => 
      [
        'title' => 'Thuộc tính',
        'create-btn' => 'Tạo thuộc tính',
        'create-success' => 'Thuộc tính được tạo thành công.',
        'update-success' => 'Thuộc tính được cập nhật thành công.',
        'delete-success' => 'Thuộc tính đã được xóa thành công.',
        'delete-failed' => 'Thuộc tính không thể bị xóa.',
        'user-define-error' => 'Không thể xóa thuộc tính hệ thống.',
        'mass-delete-failed' => 'Thuộc tính hệ thống không thể bị xóa.',
        'datagrid' => 
        [
          'yes' => 'Đúng',
          'no' => 'KHÔNG',
          'id' => 'NHẬN DẠNG',
          'code' => 'Mã số',
          'name' => 'Tên',
          'entity-type' => 'Loại thực thể',
          'type' => 'Kiểu',
          'is-default' => 'Là mặc định',
          'edit' => 'Biên tập',
          'delete' => 'Xóa bỏ',
          'entity-types' => 
          [
            'leads' => 'Khách hàng tiềm năng',
            'organizations' => 'Tổ chức',
            'persons' => 'Người',
            'products' => 'Các sản phẩm',
            'quotes' => 'Báo giá',
            'warehouses' => 'Kho hàng',
          ],
          'types' => 
          [
            'text' => 'Chữ',
            'textarea' => 'Vùng văn bản',
            'price' => 'Giá',
            'boolean' => 'Boolean',
            'select' => 'Lựa chọn',
            'multiselect' => 'Chọn nhiều lần',
            'checkbox' => 'Hộp kiểm',
            'email' => 'E-mail',
            'address' => 'Địa chỉ',
            'phone' => 'Điện thoại',
            'lookup' => 'Tra cứu',
            'datetime' => 'Ngày giờ',
            'date' => 'Ngày',
            'image' => 'Hình ảnh',
            'file' => 'Tài liệu',
          ],
        ],
      ],
      'create' => 
      [
        'title' => 'Tạo thuộc tính',
        'save-btn' => 'Lưu thuộc tính',
        'code' => 'Mã số',
        'name' => 'Tên',
        'entity-type' => 'Loại thực thể',
        'type' => 'Kiểu',
        'validations' => 'Xác thực',
        'is-required' => 'là bắt buộc',
        'input-validation' => 'Xác thực đầu vào',
        'is-unique' => 'là duy nhất',
        'labels' => 'Nhãn',
        'general' => 'Tổng quan',
        'numeric' => 'số',
        'decimal' => 'Số thập phân',
        'url' => 'Url',
        'options' => 'Tùy chọn',
        'option-type' => 'Loại tùy chọn',
        'lookup-type' => 'Loại tra cứu',
        'add-option' => 'Thêm tùy chọn',
        'save-option' => 'Lưu tùy chọn',
        'option-name' => 'Tên tùy chọn',
        'add-attribute-options' => 'Thêm tùy chọn thuộc tính',
        'text' => 'Chữ',
        'textarea' => 'Vùng văn bản',
        'price' => 'Giá',
        'boolean' => 'Boolean',
        'select' => 'Lựa chọn',
        'multiselect' => 'Chọn nhiều lần',
        'email' => 'E-mail',
        'address' => 'Địa chỉ',
        'phone' => 'Điện thoại',
        'datetime' => 'Ngày giờ',
        'date' => 'Ngày',
        'image' => 'Hình ảnh',
        'file' => 'Tài liệu',
        'lookup' => 'Tra cứu',
        'entity_type' => 'Loại thực thể',
        'checkbox' => 'Hộp kiểm',
        'is_required' => 'là bắt buộc',
        'is_unique' => 'là duy nhất',
        'actions' => 'hành động',
      ],
      'edit' => 
      [
        'actions' => 'hành động',
        'add-attribute-options' => 'Thêm tùy chọn thuộc tính',
        'add-option' => 'Thêm tùy chọn',
        'address' => 'Địa chỉ',
        'boolean' => 'Boolean',
        'checkbox' => 'Hộp kiểm',
        'code' => 'Mã số',
        'date' => 'Ngày',
        'datetime' => 'Ngày giờ',
        'decimal' => 'Số thập phân',
        'email' => 'E-mail',
        'entity-type' => 'Loại thực thể',
        'entity_type' => 'Loại thực thể',
        'file' => 'Tài liệu',
        'general' => 'Tổng quan',
        'image' => 'Hình ảnh',
        'input-validation' => 'Xác thực đầu vào',
        'is-required' => 'là bắt buộc',
        'is-unique' => 'là duy nhất',
        'is_required' => 'là bắt buộc',
        'is_unique' => 'là duy nhất',
        'labels' => 'Nhãn',
        'lookup' => 'Tra cứu',
        'lookup-type' => 'Loại tra cứu',
        'multiselect' => 'Chọn nhiều lần',
        'name' => 'Tên',
        'numeric' => 'số',
        'option-deleted' => 'Tùy chọn thuộc tính được xóa thành công',
        'option-name' => 'Tên tùy chọn',
        'option-type' => 'Loại tùy chọn',
        'options' => 'Tùy chọn',
        'phone' => 'Điện thoại',
        'price' => 'Giá',
        'save-btn' => 'Lưu thuộc tính',
        'save-option' => 'Lưu tùy chọn',
        'select' => 'Lựa chọn',
        'text' => 'Chữ',
        'textarea' => 'Vùng văn bản',
        'title' => 'Chỉnh sửa thuộc tính',
        'type' => 'Kiểu',
        'url' => 'Url',
        'validations' => 'Xác thực',
      ],
    ],
    'data-transfer' => 
    [
      'imports' => 
      [
        'create' => 
        [
          'action' => 'Hoạt động',
          'allowed-errors' => 'Lỗi được phép',
          'back-btn' => 'Mặt sau',
          'create-update' => 'Tạo/Cập nhật',
          'delete' => 'Xóa bỏ',
          'download-sample' => 'Tải xuống mẫu',
          'field-separator' => 'Dấu phân cách trường',
          'file' => 'Tài liệu',
          'general' => 'Tổng quan',
          'images-directory' => 'Đường dẫn thư mục hình ảnh',
          'process-in-queue' => 'Quá trình trong hàng đợi',
          'results' => 'Kết quả',
          'save-btn' => 'Lưu nhập',
          'settings' => 'Cài đặt',
          'skip-errors' => 'Bỏ qua lỗi',
          'stop-on-errors' => 'Dừng khi có lỗi',
          'title' => 'Tạo nhập',
          'type' => 'Kiểu',
          'validation-strategy' => 'Chiến lược xác thực',
        ],
        'edit' => 
        [
          'action' => 'Hoạt động',
          'allowed-errors' => 'Lỗi được phép',
          'back-btn' => 'Mặt sau',
          'create-update' => 'Tạo/Cập nhật',
          'delete' => 'Xóa bỏ',
          'download-sample' => 'Tải xuống mẫu',
          'field-separator' => 'Dấu phân cách trường',
          'file' => 'Tài liệu',
          'general' => 'Tổng quan',
          'images-directory' => 'Đường dẫn thư mục hình ảnh',
          'process-in-queue' => 'Quá trình trong hàng đợi',
          'results' => 'Kết quả',
          'save-btn' => 'Lưu nhập',
          'settings' => 'Cài đặt',
          'skip-errors' => 'Bỏ qua lỗi',
          'stop-on-errors' => 'Dừng khi có lỗi',
          'title' => 'Chỉnh sửa nhập',
          'type' => 'Kiểu',
          'validation-strategy' => 'Chiến lược xác thực',
        ],
        'index' => 
        [
          'button-title' => 'Tạo nhập',
          'title' => 'Nhập khẩu',
          'datagrid' => 
          [
            'actions' => 'hành động',
            'completed-at' => 'Hoàn thành vào lúc',
            'created' => 'Tạo',
            'delete' => 'Xóa bỏ',
            'deleted' => 'Đã xóa',
            'edit' => 'Biên tập',
            'error-file' => 'Tệp lỗi',
            'id' => 'NHẬN DẠNG',
            'started-at' => 'Bắt đầu lúc',
            'state' => 'Tình trạng',
            'summary' => 'Bản tóm tắt',
            'type' => 'Kiểu',
            'updated' => 'Đã cập nhật',
            'uploaded-file' => 'Tệp đã tải lên',
          ],
        ],
        'import' => 
        [
          'back-btn' => 'Mặt sau',
          'completed-batches' => 'Tổng số lô đã hoàn thành:',
          'download-error-report' => 'Tải xuống báo cáo đầy đủ',
          'edit-btn' => 'Biên tập',
          'imported-info' => 'Chúc mừng! Quá trình nhập của bạn đã thành công.',
          'importing-info' => 'Đang nhập khẩu',
          'indexing-info' => 'Đang lập chỉ mục tài nguyên (Giá, hàng tồn kho và tìm kiếm linh hoạt)',
          'linking-info' => 'Đang liên kết tài nguyên',
          'progress' => 'Tiến triển:',
          'title' => 'Nhập khẩu',
          'total-batches' => 'Tổng số lô:',
          'total-created' => 'Tổng số bản ghi được tạo:',
          'total-deleted' => 'Tổng số bản ghi đã xóa:',
          'total-errors' => 'Tổng số lỗi:',
          'total-invalid-rows' => 'Tổng số hàng không hợp lệ:',
          'total-rows-processed' => 'Tổng số hàng được xử lý:',
          'total-updated' => 'Tổng số bản ghi được cập nhật:',
          'validate' => 'Xác thực',
          'validate-info' => 'Nhấp vào Xác thực dữ liệu để kiểm tra quá trình nhập của bạn.',
          'validating-info' => 'Dữ liệu bắt đầu đọc và xác thực',
          'validation-failed-info' => 'Quá trình nhập của bạn không hợp lệ. Vui lòng sửa các lỗi sau và thử lại.',
          'validation-success-info' => 'Quá trình nhập của bạn hợp lệ. Bấm vào Nhập để bắt đầu quá trình nhập.',
        ],
        'create-success' => 'Đã nhập thành công.',
        'delete-failed' => 'Việc xóa nhập không thành công.',
        'delete-success' => 'Đã xóa thành công.',
        'not-valid' => 'Nhập không hợp lệ',
        'nothing-to-import' => 'Không có tài nguyên để nhập khẩu.',
        'setup-queue-error' => 'Vui lòng thay đổi trình điều khiển hàng đợi của bạn thành "cơ sở dữ liệu" hoặc "redis" để bắt đầu quá trình nhập.',
        'update-success' => 'Đã cập nhật nhập thành công.',
      ],
    ],
  ],
  'activities' => 
  [
    'index' => 
    [
      'title' => 'Các hoạt động',
      'datagrid' => 
      [
        'comment' => 'Bình luận',
        'created_at' => 'Được tạo tại',
        'created_by' => 'Được tạo bởi',
        'edit' => 'Biên tập',
        'id' => 'NHẬN DẠNG',
        'done' => 'Đã xong',
        'not-done' => 'Chưa hoàn thành',
        'lead' => 'Chỉ huy',
        'mass-delete' => 'Xóa hàng loạt',
        'mass-update' => 'Cập nhật hàng loạt',
        'schedule-from' => 'Lên lịch từ',
        'schedule-to' => 'Lên lịch đến',
        'schedule_from' => 'Lên lịch từ',
        'schedule_to' => 'Lên lịch tới',
        'title' => 'Tiêu đề',
        'is_done' => 'Đã xong',
        'type' => 'Kiểu',
        'update' => 'Cập nhật',
        'call' => 'Gọi',
        'meeting' => 'Cuộc họp',
        'lunch' => 'Bữa trưa',
      ],
    ],
    'edit' => 
    [
      'title' => 'Chỉnh sửa hoạt động',
      'back-btn' => 'Mặt sau',
      'save-btn' => 'Lưu hoạt động',
      'type' => 'Loại hoạt động',
      'call' => 'Gọi',
      'meeting' => 'Cuộc họp',
      'lunch' => 'Bữa trưa',
      'schedule_to' => 'Lên lịch đến',
      'schedule_from' => 'Lên lịch từ',
      'location' => 'Vị trí',
      'comment' => 'Bình luận',
      'lead' => 'Chỉ huy',
      'participants' => 'Người tham gia',
      'general' => 'Tổng quan',
      'persons' => 'Người',
      'no-result-found' => 'Không tìm thấy hồ sơ.',
      'users' => 'Người dùng',
    ],
    'updated' => 'Đã cập nhật: thuộc tính',
    'created' => 'Tạo',
    'duration-overlapping' => 'Những người tham gia có một cuộc họp khác vào lúc này. Bạn có muốn tiếp tục không?',
    'create-success' => 'Hoạt động được tạo thành công.',
    'update-success' => 'Hoạt động được cập nhật thành công.',
    'overlapping-error' => 'Những người tham gia có một cuộc họp khác vào lúc này.',
    'destroy-success' => 'Đã xóa hoạt động thành công.',
    'delete-failed' => 'Hoạt động không thể bị xóa.',
    'mass-update-success' => 'Hoạt động được cập nhật thành công.',
    'mass-destroy-success' => 'Đã xóa hoạt động thành công.',
    'mass-delete-failed' => 'Các hoạt động không thể bị xóa.',
  ],
  'mail' => 
  [
    'index' => 
    [
      'compose' => 'Soạn',
      'draft' => 'Bản nháp',
      'inbox' => 'Hộp thư đến',
      'outbox' => 'Hộp thư đi',
      'sent' => 'Đã gửi',
      'trash' => 'Rác',
      'compose-mail-btn' => 'Soạn thư',
      'btn' => 'Thư',
      'mail' => 
      [
        'title' => 'Soạn thư',
        'to' => 'ĐẾN',
        'enter-emails' => 'Nhấn enter để thêm email',
        'cc' => 'CC',
        'bcc' => 'BCC',
        'subject' => 'Chủ thể',
        'send-btn' => 'Gửi',
        'message' => 'Tin nhắn',
        'draft' => 'Bản nháp',
      ],
      'datagrid' => 
      [
        'id' => 'NHẬN DẠNG',
        'from' => 'Từ',
        'to' => 'ĐẾN',
        'subject' => 'Chủ thể',
        'tags' => 'Thẻ',
        'content' => 'Nội dung',
        'attachments' => 'Tệp đính kèm',
        'date' => 'Ngày',
        'move-to-inbox' => 'Đã chuyển sang Hộp thư đến',
        'move-to-trash' => 'Đã chuyển vào thùng rác',
        'edit' => 'Biên tập',
        'view' => 'Xem',
        'delete' => 'Xóa bỏ',
      ],
    ],
    'create-success' => 'Email đã được gửi thành công.',
    'update-success' => 'Email được cập nhật thành công.',
    'mass-update-success' => 'Email được cập nhật thành công.',
    'delete-success' => 'Đã xóa email thành công.',
    'delete-failed' => 'Email không thể xóa được.',
    'view' => 
    [
      'title' => 'Thư',
      'subject' => ':chủ thể',
      'link-mail' => 'Liên kết thư',
      'to' => 'ĐẾN',
      'cc' => 'CC',
      'bcc' => 'BCC',
      'reply' => 'Hồi đáp',
      'reply-all' => 'Trả lời tất cả',
      'forward' => 'Phía trước',
      'delete' => 'Xóa bỏ',
      'enter-mails' => 'Nhập id email',
      'rotten-days' => 'Chì bị thối trong :ngày ngày',
      'search-an-existing-lead' => 'Tìm kiếm khách hàng tiềm năng hiện có',
      'search-an-existing-contact' => 'Tìm kiếm một liên hệ hiện có',
      'message' => 'Tin nhắn',
      'add-attachments' => 'Thêm tệp đính kèm',
      'discard' => 'Loại bỏ',
      'send' => 'Gửi',
      'no-result-found' => 'Không tìm thấy kết quả nào',
      'add-new-contact' => 'Thêm liên hệ mới',
      'description' => 'Sự miêu tả',
      'search' => 'Tìm kiếm...',
      'add-new-lead' => 'Thêm khách hàng tiềm năng mới',
      'create-new-contact' => 'Tạo liên hệ mới',
      'save-contact' => 'Lưu liên hệ',
      'create-lead' => 'Tạo khách hàng tiềm năng',
      'linked-contact' => 'Liên hệ được liên kết',
      'link-to-contact' => 'Liên kết để liên hệ',
      'link-to-lead' => 'Liên kết để dẫn đầu',
      'linked-lead' => 'Khách hàng tiềm năng được liên kết',
      'lead-details' => 'Chi tiết khách hàng tiềm năng',
      'contact-person' => 'Người liên hệ',
      'product' => 'Sản phẩm',
      'send-whatsapp-message' => 
      [
        'title' => 'Gửi tin nhắn WhatsApp',
        'message' => 'Tin nhắn',
        'placeholder' => 'Nhập tin nhắn của bạn tại đây...',
        'phone-number' => 'Số điện thoại',
        'select-phone' => 'Chọn số điện thoại',
        'send' => 'Gửi',
        'clear' => 'Xóa',
        'no-phone' => 'Không có số điện thoại',
        'add-phone-first' => 'Vui lòng thêm số điện thoại cho khách hàng trước khi gửi tin nhắn',
        'character-count' => 'Ký tự',
        'success' => 'Tin nhắn đã gửi thành công!',
        'error' => 'Lỗi gửi tin nhắn',
      ],
      'tags' => 
      [
        'create-success' => 'Đã tạo thẻ thành công.',
        'destroy-success' => 'Đã xóa thẻ thành công.',
      ],
    ],
  ],
  'common' => 
  [
    'custom-attributes' => 
    [
      'add-more' => 'Thêm nhiều hơn nữa',
      'address' => 'Địa chỉ',
      'city' => 'Thành phố',
      'contact' => 'Số liên lạc',
      'country' => 'Quốc gia',
      'email' => 'E-mail',
      'home' => 'Trang chủ',
      'postcode' => 'Mã bưu điện',
      'save' => 'Cứu',
      'select' => 'Lựa chọn',
      'select-country' => 'Chọn quốc gia',
      'select-state' => 'Chọn tiểu bang',
      'state' => 'Tình trạng',
      'update-contact-title' => 'Cập nhật số liên lạc',
      'update-emails-title' => 'Cập nhật email liên hệ',
      'work' => 'Công việc',
    ],
  ],
  'leads' => 
  [
    'create-success' => 'Đã tạo thành công khách hàng tiềm năng.',
    'update-success' => 'Đã cập nhật khách hàng tiềm năng thành công.',
    'update-failed' => 'Khách hàng tiềm năng không thể bị xóa.',
    'destroy-success' => 'Đã xóa khách hàng tiềm năng thành công.',
    'destroy-failed' => 'Chì không thể bị xóa.',
    'file' => 
    [
      'data-not-found' => 'Không tìm thấy dữ liệu.',
      'empty-content' => 'Nội dung PDF trống hoặc không thể trích xuất được.',
      'failed-extract' => 'Không thể trích xuất văn bản từ tệp.',
      'insufficient-info' => 'Do không đủ dữ liệu nên chúng tôi không thể xử lý yêu cầu của bạn vào lúc này.',
      'invalid-base64' => 'Định dạng base64 không hợp lệ.',
      'invalid-format' => 'Định dạng JSON không hợp lệ.',
      'invalid-response' => 'Định dạng phản hồi AI không hợp lệ.',
      'missing-api-key' => 'Thiếu khóa API hoặc cấu hình mô hình.',
      'not-found' => 'Không tìm thấy tập tin.',
      'recursive-call' => 'Đã phát hiện cuộc gọi đệ quy.',
      'text-generation-failed' => 'Trích xuất văn bản không thành công. Tệp có thể trống hoặc không thể đọc được.',
    ],
    'index' => 
    [
      'title' => 'Khách hàng tiềm năng',
      'create-btn' => 'Tạo khách hàng tiềm năng',
      'datagrid' => 
      [
        'id' => 'NHẬN DẠNG',
        'sales-person' => 'nhân viên bán hàng',
        'subject' => 'Chủ thể',
        'source' => 'Nguồn',
        'lead-value' => 'Giá trị chì',
        'lead-type' => 'Loại chì',
        'tag-name' => 'Tên thẻ',
        'contact-person' => 'Người liên hệ',
        'stage' => 'Sân khấu',
        'rotten-lead' => 'Chì thối',
        'date-to' => 'Ngày đến',
        'created-at' => 'Được tạo tại',
        'no' => 'KHÔNG',
        'yes' => 'Đúng',
        'delete' => 'Xóa bỏ',
        'mass-delete' => 'Xóa hàng loạt',
        'mass-update' => 'Cập nhật hàng loạt',
      ],
      'kanban' => 
      [
        'rotten-days' => 'Chì bị thối trong :ngày ngày',
        'empty-list' => 'Danh sách khách hàng tiềm năng của bạn trống',
        'empty-list-description' => 'Tạo khách hàng tiềm năng để sắp xếp các mục tiêu của bạn.',
        'create-lead-btn' => 'Tạo khách hàng tiềm năng',
        'columns' => 
        [
          'contact-person' => 'Người liên hệ',
          'id' => 'NHẬN DẠNG',
          'lead-type' => 'Loại chì',
          'lead-value' => 'Giá trị chì',
          'sales-person' => 'nhân viên bán hàng',
          'source' => 'Nguồn',
          'title' => 'Tiêu đề',
          'tags' => 'Thẻ',
          'expected-close-date' => 'Ngày đóng dự kiến',
          'created-at' => 'Được tạo tại',
        ],
        'toolbar' => 
        [
          'search' => 
          [
            'title' => 'Tìm kiếm theo tiêu đề',
          ],
          'filters' => 
          [
            'apply-filters' => 'Áp dụng bộ lọc',
            'clear-all' => 'Xóa tất cả',
            'filter' => 'Lọc',
            'filters' => 'Bộ lọc',
            'from' => 'Từ',
            'select' => 'Lựa chọn',
            'to' => 'ĐẾN',
          ],
        ],
      ],
      'view-switcher' => 
      [
        'all-pipelines' => 'Tất cả các đường ống',
        'create-new-pipeline' => 'Tạo đường ống mới',
      ],
      'upload' => 
      [
        'create-lead' => 'Tạo khách hàng tiềm năng bằng AI',
        'file' => 'Tải lên tệp',
        'file-info' => 'Chỉ chấp nhận các tệp định dạng pdf, bmp, jpg, jpeg, png.',
        'file-required' => 'Vui lòng chọn ít nhất một tệp hợp lệ để tiếp tục.',
        'save-btn' => 'Cứu',
        'upload-file' => 'Tải tệp lên',
      ],
    ],
    'create' => 
    [
      'title' => 'Tạo khách hàng tiềm năng',
      'save-btn' => 'Cứu',
      'details' => 'Chi tiết',
      'details-info' => 'Đưa thông tin cơ bản của khách hàng tiềm năng',
      'contact-person' => 'Người liên hệ',
      'contact-info' => 'Thông tin về người liên hệ',
      'products' => 'Các sản phẩm',
      'products-info' => 'Thông tin về sản phẩm',
    ],
    'edit' => 
    [
      'title' => 'Chỉnh sửa khách hàng tiềm năng',
      'save-btn' => 'Cứu',
      'details' => 'Chi tiết',
      'details-info' => 'Đưa thông tin cơ bản của khách hàng tiềm năng',
      'contact-person' => 'Người liên hệ',
      'contact-info' => 'Thông tin về người liên hệ',
      'products' => 'Các sản phẩm',
      'products-info' => 'Thông tin về sản phẩm',
    ],
    'common' => 
    [
      'contact' => 
      [
        'name' => 'Tên',
        'email' => 'E-mail',
        'contact-number' => 'Số liên lạc',
        'organization' => 'Tổ chức',
      ],
      'products' => 
      [
        'product-name' => 'Tên sản phẩm',
        'quantity' => 'Số lượng',
        'price' => 'Giá',
        'amount' => 'Số lượng',
        'action' => 'Hoạt động',
        'add-more' => 'Thêm nhiều hơn nữa',
        'total' => 'Tổng cộng',
      ],
    ],
    'view' => 
    [
      'title' => 'Dẫn: :tiêu đề',
      'rotten-days' => ':ngày Ngày',
      'tabs' => 
      [
        'description' => 'Sự miêu tả',
        'products' => 'Các sản phẩm',
        'quotes' => 'Báo giá',
      ],
      'attributes' => 
      [
        'title' => 'Về Chì',
      ],
      'quotes' => 
      [
        'subject' => 'Chủ thể',
        'expired-at' => 'Hết hạn vào lúc',
        'sub-total' => 'Tổng phụ',
        'discount' => 'Giảm giá',
        'tax' => 'Thuế',
        'adjustment' => 'Điều chỉnh',
        'grand-total' => 'Tổng cộng',
        'delete' => 'Xóa bỏ',
        'edit' => 'Biên tập',
        'download' => 'Tải xuống',
        'destroy-success' => 'Đã xóa báo giá thành công.',
        'empty-title' => 'Không tìm thấy trích dẫn nào',
        'empty-info' => 'Không tìm thấy trích dẫn nào cho khách hàng tiềm năng này',
        'add-btn' => 'Thêm trích dẫn',
      ],
      'products' => 
      [
        'product-name' => 'Tên sản phẩm',
        'quantity' => 'Số lượng',
        'price' => 'Giá',
        'amount' => 'Số lượng',
        'action' => 'Hoạt động',
        'add-more' => 'Thêm nhiều hơn nữa',
        'total' => 'Tổng cộng',
        'empty-title' => 'Không tìm thấy sản phẩm',
        'empty-info' => 'Không tìm thấy sản phẩm nào cho khách hàng tiềm năng này',
        'add-product' => 'Thêm sản phẩm',
      ],
      'persons' => 
      [
        'title' => 'Về con người',
        'job-title' => ':job_title tại :tổ chức',
      ],
      'stages' => 
      [
        'won-lost' => 'Thắng/Thua',
        'won' => 'Thắng',
        'lost' => 'Mất',
        'need-more-info' => 'Cần thêm chi tiết',
        'closed-at' => 'Đóng cửa vào lúc',
        'won-value' => 'Giá trị giành được',
        'lost-reason' => 'Lý do bị mất',
        'save-btn' => 'Cứu',
      ],
      'tags' => 
      [
        'create-success' => 'Đã tạo thẻ thành công.',
        'destroy-success' => 'Đã xóa thẻ thành công.',
      ],
    ],
  ],
  'configuration' => 
  [
    'index' => 
    [
      'back' => 'Mặt sau',
      'delete' => 'Xóa bỏ',
      'save-btn' => 'Lưu cấu hình',
      'save-success' => 'Đã lưu cấu hình thành công.',
      'search' => 'Tìm kiếm',
      'select-country' => 'Chọn quốc gia',
      'select-state' => 'Chọn tiểu bang',
      'title' => 'Cấu hình',
      'general' => 
      [
        'title' => 'Tổng quan',
        'info' => 'Cấu hình chung',
        'general' => 
        [
          'title' => 'Tổng quan',
          'info' => 'Cập nhật cài đặt chung của bạn tại đây.',
          'locale-settings' => 
          [
            'title' => 'Cài đặt ngôn ngữ',
            'title-info' => 'Xác định ngôn ngữ được sử dụng trong giao diện người dùng, chẳng hạn như tiếng Ả Rập (ar], tiếng Anh (en], tiếng Tây Ban Nha (es], tiếng Ba Tư(fa) và tiếng Thổ Nhĩ Kỳ (tr).',
          ],
          'admin-logo' => 
          [
            'logo-image' => 'Hình ảnh biểu tượng',
            'title' => 'Logo quản trị viên',
            'title-info' => 'Định cấu hình hình ảnh logo cho bảng quản trị của bạn.',
          ],
        ],
        'settings' => 
        [
          'title' => 'Cài đặt',
          'info' => 'Cập nhật cài đặt của bạn ở đây.',
          'footer' => 
          [
            'info' => 'Chúng ta có thể cấu hình phần Powered by ở đây.',
            'powered-by' => 'Được hỗ trợ bởi trình soạn thảo văn bản',
            'title' => 'Được hỗ trợ bởi cấu hình phần',
          ],
          'menu' => 
          [
            'activities' => 'Các hoạt động',
            'configuration' => 'Cấu hình',
            'contacts' => 'Danh bạ',
            'dashboard' => 'Trang tổng quan',
            'draft' => 'Bản nháp',
            'inbox' => 'Hộp thư đến',
            'info' => 'Chúng ta có thể cấu hình tên các mục menu ở đây.',
            'leads' => 'Khách hàng tiềm năng',
            'mail' => 'Thư',
            'organizations' => 'Tổ chức',
            'outbox' => 'Hộp thư đi',
            'persons' => 'Người',
            'products' => 'Các sản phẩm',
            'quotes' => 'Báo giá',
            'sent' => 'Đã gửi',
            'settings' => 'Cài đặt',
            'title' => 'Cấu hình mục menu',
            'trash' => 'Rác',
          ],
          'menu-color' => 
          [
            'brand-color' => 'Màu thương hiệu',
            'info' => 'Chúng ta có thể thay đổi màu sắc của các mục menu ở đây.',
            'title' => 'Cấu hình màu mục menu',
          ],
        ],
      ],
      'email' => 
      [
        'title' => 'Cài đặt email',
        'info' => 'Cấu hình email cho ứng dụng.',
        'imap' => 
        [
          'title' => 'Cài đặt IMAP',
          'info' => 'Cấu hình email IMAP để nhận email.',
          'account' => 
          [
            'title' => 'Tài khoản IMAP',
            'title-info' => 'Định cấu hình cài đặt tài khoản IMAP của bạn tại đây.',
            'host' => 'Chủ nhà',
            'port' => 'Cảng',
            'encryption' => 'Loại mã hóa',
            'validate-cert' => 'Xác thực chứng chỉ SSL',
            'username' => 'Tên người dùng IMAP',
            'password' => 'Mật khẩu IMAP',
          ],
        ],
      ],
      'magic-ai' => 
      [
        'title' => 'AI ma thuật',
        'info' => 'Cấu hình Magic AI cho ứng dụng.',
        'settings' => 
        [
          'api-key' => 'Khóa API',
          'api-key-info' => 'Hãy nhớ sử dụng khóa API OpenRouter cho từng kiểu máy. Đây là một bước đơn giản để tăng cường bảo mật và hiệu suất.',
          'enable' => 'Cho phép',
          'info' => 'Nâng cao trải nghiệm Magic AI của bạn với Khóa API OpenRouter. Hãy tích hợp nó ngay bây giờ để có một cuộc phiêu lưu AI liền mạch, được cá nhân hóa dành riêng cho bạn! Dễ dàng tùy chỉnh cài đặt và kiểm soát hành trình AI của bạn.',
          'other' => 'Mẫu khác',
          'other-model' => 'Đối với các kiểu máy khác, hãy sử dụng ID mẫu từ OpenRouter.',
          'doc-generation' => 'Thế hệ DOC',
          'doc-generation-info' => 'Kích hoạt tính năng DOC Generation để tự động trích xuất dữ liệu từ file DOC và chuyển đổi chúng sang định dạng văn bản. Nâng cao năng suất và hiệu quả của bạn bằng cách kích hoạt tính năng này để hợp lý hóa quy trình làm việc của bạn.',
          'title' => 'Cài đặt chung',
          'models' => 
          [
            'deepseek-r1' => 'Deepseek R1 Distill-llama-8b',
            'gemini-2-0-flash-001' => 'Song Tử 2.0 flash-001',
            'gpt-4o' => 'GPT-4.0',
            'gpt-4o-mini' => 'GPT-4.0 mini',
            'grok-2-1212' => 'Grok 2.12',
            'llama-3-2-3b-instruct' => 'Llama 3.2 3b Hướng dẫn',
            'title' => 'Người mẫu',
          ],
        ],
      ],
    ],
  ],
  'dashboard' => 
  [
    'index' => 
    [
      'title' => 'Trang tổng quan',
      'revenue' => 
      [
        'lost-revenue' => 'Doanh thu bị mất',
        'won-revenue' => 'Doanh thu giành được',
      ],
      'over-all' => 
      [
        'average-lead-value' => 'Giá trị khách hàng tiềm năng trung bình',
        'total-leads' => 'Tổng số khách hàng tiềm năng',
        'average-leads-per-day' => 'Khách hàng tiềm năng trung bình mỗi ngày',
        'total-quotations' => 'Tổng báo giá',
        'total-persons' => 'Tổng số người',
        'total-organizations' => 'Tổng số tổ chức',
      ],
      'total-leads' => 
      [
        'title' => 'Khách hàng tiềm năng',
        'total' => 'Tổng số khách hàng tiềm năng',
        'won' => 'Giành được vị trí dẫn đầu',
        'lost' => 'Mất khách hàng tiềm năng',
      ],
      'revenue-by-sources' => 
      [
        'title' => 'Doanh thu theo nguồn',
        'empty-title' => 'Không có sẵn dữ liệu',
        'empty-info' => 'Không có dữ liệu cho khoảng thời gian đã chọn',
      ],
      'revenue-by-types' => 
      [
        'title' => 'Doanh thu theo loại',
        'empty-title' => 'Không có sẵn dữ liệu',
        'empty-info' => 'Không có dữ liệu cho khoảng thời gian đã chọn',
      ],
      'top-selling-products' => 
      [
        'title' => 'Sản phẩm hàng đầu',
        'empty-title' => 'Không tìm thấy sản phẩm',
        'empty-info' => 'Không có sản phẩm nào trong khoảng thời gian đã chọn',
      ],
      'top-persons' => 
      [
        'title' => 'Người đứng đầu',
        'empty-title' => 'Không tìm thấy người',
        'empty-info' => 'Không có người nào rảnh trong khoảng thời gian đã chọn',
      ],
      'open-leads-by-states' => 
      [
        'title' => 'Khách hàng tiềm năng mở theo giai đoạn',
        'empty-title' => 'Không có sẵn dữ liệu',
        'empty-info' => 'Không có dữ liệu cho khoảng thời gian đã chọn',
      ],
    ],
  ],
  'layouts' => 
  [
    'social-message' => 'Chat Socials',
    'facebook' => 'Facebook',
    'app-version' => 'Phiên bản: :phiên bản',
    'dashboard' => 'Trang tổng quan',
    'leads' => 'Khách hàng tiềm năng',
    'quotes' => 'Báo giá',
    'quote' => 'Trích dẫn',
    'mail' => 
    [
      'title' => 'Thư',
      'compose' => 'Soạn',
      'inbox' => 'Hộp thư đến',
      'draft' => 'Bản nháp',
      'outbox' => 'Hộp thư đi',
      'sent' => 'Đã gửi',
      'trash' => 'Rác',
      'setting' => 'Cài đặt',
    ],
    'activities' => 'Các hoạt động',
    'contacts' => 'Danh bạ',
    'persons' => 'Người',
    'person' => 'Người',
    'organizations' => 'Tổ chức',
    'organization' => 'Tổ chức',
    'products' => 'Các sản phẩm',
    'product' => 'Sản phẩm',
    'settings' => 'Cài đặt',
    'user' => 'người dùng',
    'user-info' => 'Quản lý tất cả người dùng của bạn và quyền của họ trong CRM, những gì họ được phép làm.',
    'groups' => 'Nhóm',
    'groups-info' => 'Thêm, chỉnh sửa hoặc xóa nhóm khỏi CRM',
    'roles' => 'Vai trò',
    'role' => 'Vai trò',
    'roles-info' => 'Thêm, chỉnh sửa hoặc xóa vai trò khỏi CRM',
    'users' => 'Người dùng',
    'users-info' => 'Thêm, chỉnh sửa hoặc xóa người dùng khỏi CRM',
    'lead' => 'Chỉ huy',
    'lead-info' => 'Quản lý tất cả các cài đặt liên quan đến khách hàng tiềm năng của bạn trong CRM',
    'pipelines' => 'Đường ống',
    'pipelines-info' => 'Thêm, chỉnh sửa hoặc xóa quy trình khỏi CRM',
    'sources' => 'Nguồn',
    'sources-info' => 'Thêm, chỉnh sửa hoặc xóa nguồn từ CRM',
    'types' => 'Các loại',
    'types-info' => 'Thêm, chỉnh sửa hoặc xóa loại khỏi CRM',
    'automation' => 'Tự động hóa',
    'automation-info' => 'Quản lý tất cả các cài đặt liên quan đến tự động hóa của bạn trong CRM',
    'attributes' => 'Thuộc tính',
    'attribute' => 'Thuộc tính',
    'attributes-info' => 'Thêm, chỉnh sửa hoặc xóa thuộc tính khỏi CRM',
    'email-templates' => 'Mẫu email',
    'email' => 'E-mail',
    'email-templates-info' => 'Thêm, chỉnh sửa hoặc xóa mẫu email khỏi CRM',
    'events' => 'Sự kiện',
    'events-info' => 'Thêm, chỉnh sửa hoặc xóa sự kiện khỏi CRM',
    'campaigns' => 'Chiến dịch',
    'campaigns-info' => 'Thêm, chỉnh sửa hoặc xóa chiến dịch khỏi CRM',
    'workflows' => 'Quy trình làm việc',
    'workflows-info' => 'Thêm, chỉnh sửa hoặc xóa quy trình công việc khỏi CRM',
    'webhooks' => 'Webhook',
    'webhooks-info' => 'Thêm, chỉnh sửa hoặc xóa webhook khỏi CRM',
    'other-settings' => 'Cài đặt khác',
    'other-settings-info' => 'Quản lý tất cả cài đặt bổ sung của bạn trong CRM',
    'tags' => 'Thẻ',
    'tags-info' => 'Thêm, chỉnh sửa hoặc xóa thẻ khỏi CRM',
    'my-account' => 'Tài khoản của tôi',
    'sign-out' => 'Đăng xuất',
    'back' => 'Mặt sau',
    'name' => 'Tên',
    'configuration' => 'Cấu hình',
    'howdy' => 'Xin chào!',
    'warehouses' => 'Kho hàng',
    'warehouse' => 'Kho',
    'warehouses-info' => 'Thêm, chỉnh sửa hoặc xóa kho khỏi CRM',
    'data_transfer' => 'Truyền dữ liệu',
    'data_transfer_info' => 'Quản lý các cài đặt liên quan đến con người, sản phẩm và khách hàng tiềm năng trong CRM',
  ],
  'user' => 
  [
    'account' => 
    [
      'name' => 'Tên',
      'email' => 'E-mail',
      'password' => 'Mật khẩu',
      'my_account' => 'Tài khoản của tôi',
      'update_details' => 'Cập nhật chi tiết',
      'current_password' => 'Mật khẩu hiện tại',
      'confirm_password' => 'Xác nhận mật khẩu',
      'password-match' => 'Mật khẩu hiện tại không khớp.',
      'account-save' => 'Đã lưu thành công các thay đổi về tài khoản.',
      'permission-denied' => 'Quyền bị từ chối',
      'remove-image' => 'Xóa hình ảnh',
      'upload_image_pix' => 'Tải lên hình ảnh hồ sơ (100px x 100px)',
      'upload_image_format' => 'ở định dạng PNG hoặc JPG',
      'image_upload_message' => 'Chỉ cho phép hình ảnh (.jpeg, .jpg, .png, ..).',
    ],
  ],
  'emails' => 
  [
    'common' => 
    [
      'dear' => 'Kính gửi: tên',
      'cheers' => 'Xin chúc mừng,</br>Nhóm :app_name',
      'user' => 
      [
        'dear' => 'Kính gửi: tên người dùng',
        'create-subject' => 'Bạn được thêm làm thành viên.',
        'create-body' => 'Chúc mừng! Bây giờ bạn là thành viên của nhóm chúng tôi.',
        'forget-password' => 
        [
          'subject' => 'Khách hàng đặt lại mật khẩu',
          'dear' => 'Kính gửi: tên người dùng',
          'reset-password' => 'Đặt lại mật khẩu',
          'info' => 'Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn',
          'final-summary' => 'Nếu bạn không yêu cầu đặt lại mật khẩu thì không cần thực hiện thêm hành động nào',
          'thanks' => 'Cảm ơn!',
        ],
      ],
    ],
  ],
  'validations' => 
  [
    'message' => 
    [
      'decimal' => ':attribute phải là số thập phân.',
    ],
  ],
  'errors' => 
  [
    'dashboard' => 'Trang tổng quan',
    'go-back' => 'Quay lại',
    'support' => 'Nếu sự cố vẫn tiếp diễn, hãy liên hệ với chúng tôi theo địa chỉ <a href=":link" class=":class">:email</a> để được hỗ trợ.',
    404 => 
    [
      'description' => 'Ối! Trang bạn đang tìm kiếm đang trong kỳ nghỉ. Có vẻ như chúng tôi không thể tìm thấy những gì bạn đang tìm kiếm.',
      'title' => 'Không tìm thấy trang 404',
    ],
    401 => 
    [
      'description' => 'Ối! Có vẻ như bạn không được phép truy cập trang này. Có vẻ như bạn đang thiếu thông tin xác thực cần thiết.',
      'title' => '401 trái phép',
    ],
    403 => 
    [
      'description' => 'Ối! Trang này nằm ngoài giới hạn. Có vẻ như bạn không có quyền cần thiết để xem nội dung này.',
      'title' => '403 bị cấm',
    ],
    500 => 
    [
      'description' => 'Ối! Đã xảy ra lỗi. Có vẻ như chúng tôi đang gặp sự cố khi tải trang bạn đang tìm kiếm.',
      'title' => 'Lỗi máy chủ nội bộ 500',
    ],
    503 => 
    [
      'description' => 'Ối! Có vẻ như chúng tôi tạm thời ngừng hoạt động để bảo trì. Vui lòng kiểm tra lại một chút.',
      'title' => 'Dịch vụ 503 không khả dụng',
    ],
  ],
  'export' => 
  [
    'csv' => 'CSV',
    'download' => 'Tải xuống',
    'export' => 'Xuất khẩu',
    'no-records' => 'Không có gì để xuất khẩu',
    'xls' => 'XLS',
    'xlsx' => 'XLSX',
  ],
];