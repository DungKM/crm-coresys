@extends('admin::layouts.master')

@section('page_title')
    Chỉnh sửa Lịch Hẹn #{{ $appointment->id }}
@stop

@section('css')
<style>
    .appointment-form {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .form-section {
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #ecf0f1;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .form-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section h3 i {
        color: #3498db;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: space-between;
        padding-top: 20px;
        border-top: 1px solid #ecf0f1;
    }

    .status-info {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-scheduled { background: #e3f2fd; color: #1976d2; }
    .status-confirmed { background: #e8f5e9; color: #388e3c; }
    .status-cancelled { background: #ffebee; color: #d32f2f; }
    .status-completed { background: #f3e5f5; color: #7b1fa2; }
    .status-no_show { background: #fff3e0; color: #f57c00; }
</style>
@endsection

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon icon-calendar"></i>
                    Chỉnh sửa Lịch Hẹn #{{ $appointment->id }}
                </h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-md">
                    <i class="icon icon-arrow-left"></i>
                    Quay lại
                </a>
            </div>
        </div>

        {{-- Status Info --}}
        <div class="status-info">
            <div>
                <strong>Trạng thái hiện tại:</strong>
                <span class="status-badge status-{{ $appointment->status }}">
                    @switch($appointment->status)
                        @case('scheduled') Đã lên lịch @break
                        @case('confirmed') Đã xác nhận @break
                        @case('cancelled') Đã hủy @break
                        @case('completed') Hoàn thành @break
                        @case('no_show') Không đến @break
                    @endswitch
                </span>
            </div>
            <div>
                <small style="color: #7f8c8d;">
                    Tạo lúc: {{ $appointment->created_at->format('d/m/Y H:i') }}
                    @if($appointment->assignedUser)
                        | Phụ trách: {{ $appointment->assignedUser->name }}
                    @endif
                </small>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.appointments.update', $appointment->id) }}" class="appointment-form">
            @csrf
            @method('PUT')

            {{-- Thông tin khách hàng --}}
            <div class="form-section">
                <h3>
                    <i class="icon icon-users"></i>
                    Thông tin khách hàng
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Tên khách hàng</label>
                        <input type="text"
                               name="customer_name"
                               class="control"
                               value="{{ old('customer_name', $appointment->customer_name) }}"
                               required>
                        @error('customer_name')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">Số điện thoại</label>
                        <input type="tel"
                               name="customer_phone"
                               class="control"
                               value="{{ old('customer_phone', $appointment->customer_phone) }}"
                               required>
                        @error('customer_phone')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email"
                               name="customer_email"
                               class="control"
                               value="{{ old('customer_email', $appointment->customer_email) }}">
                    </div>

                    <div class="form-group">
                        <label>Nguồn</label>
                        <select name="source" class="control">
                            <option value="website" {{ old('source', $appointment->source) == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="facebook" {{ old('source', $appointment->source) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="hotline" {{ old('source', $appointment->source) == 'hotline' ? 'selected' : '' }}>Hotline</option>
                            <option value="zalo" {{ old('source', $appointment->source) == 'zalo' ? 'selected' : '' }}>Zalo</option>
                            <option value="other" {{ old('source', $appointment->source) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Thông tin lịch hẹn --}}
            <div class="form-section">
                <h3>
                    <i class="icon icon-calendar"></i>
                    Thông tin lịch hẹn
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Ngày hẹn</label>
                        <input type="date"
                               name="appointment_date"
                               class="control"
                               value="{{ old('appointment_date', $appointment->start_at->format('Y-m-d')) }}"
                               required>
                        @error('appointment_date')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="required">Giờ bắt đầu</label>
                        <input type="time"
                               name="start_time"
                               class="control"
                               value="{{ old('start_time', $appointment->start_at->format('H:i')) }}"
                               required>
                        @error('start_time')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Thời lượng (phút)</label>
                        <input type="number"
                               name="duration_minutes"
                               class="control"
                               value="{{ old('duration_minutes', $appointment->duration_minutes) }}"
                               min="15"
                               step="15"
                               placeholder="30">
                    </div>

                    <div class="form-group">
                        <label class="required">Loại lịch hẹn</label>
                        <select name="meeting_type" class="control" required>
                            <option value="call" {{ old('meeting_type', $appointment->meeting_type) == 'call' ? 'selected' : '' }}>Gọi điện thoại</option>
                            <option value="onsite" {{ old('meeting_type', $appointment->meeting_type) == 'onsite' ? 'selected' : '' }}>Gặp trực tiếp</option>
                            <option value="online" {{ old('meeting_type', $appointment->meeting_type) == 'online' ? 'selected' : '' }}>Họp online</option>
                        </select>
                        @error('meeting_type')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Dịch vụ / Mục đích</label>
                        <select name="service_name" class="control" required>
                            <option value="">-- Chọn dịch vụ --</option>
                            <option value="Tư vấn sản phẩm" {{ old('service_name', $appointment->service_name) == 'Tư vấn sản phẩm' ? 'selected' : '' }}>Tư vấn sản phẩm</option>
                            <option value="Demo hệ thống" {{ old('service_name', $appointment->service_name) == 'Demo hệ thống' ? 'selected' : '' }}>Demo hệ thống</option>
                            <option value="Ký hợp đồng" {{ old('service_name', $appointment->service_name) == 'Ký hợp đồng' ? 'selected' : '' }}>Ký hợp đồng</option>
                            <option value="Hỗ trợ kỹ thuật" {{ old('service_name', $appointment->service_name) == 'Hỗ trợ kỹ thuật' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                            <option value="Khác" {{ old('service_name', $appointment->service_name) == 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('service_name')
                            <span class="control-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Người phụ trách</label>
                        <select name="assigned_user_id" class="control">
                            <option value="">-- Tự động phân bổ --</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('assigned_user_id', $appointment->assigned_user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Link họp (nếu online)</label>
                    <input type="url"
                           name="meeting_link"
                           class="control"
                           value="{{ old('meeting_link', $appointment->meeting_link) }}"
                           placeholder="https://meet.google.com/xxx-xxxx-xxx">
                </div>

                <div class="form-group">
                    <label class="required">Trạng thái</label>
                    <select name="status" class="control" required>
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>Đã lên lịch</option>
                        <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="no_show" {{ old('status', $appointment->status) == 'no_show' ? 'selected' : '' }}>Không đến</option>
                    </select>
                    @error('status')
                        <span class="control-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Ghi chú</label>
                    <textarea name="note"
                              class="control"
                              rows="4"
                              placeholder="Nhập ghi chú bổ sung...">{{ old('note', $appointment->note) }}</textarea>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="form-actions">
                <div>
                    <button type="button"
                            class="btn btn-danger btn-md"
                            onclick="if(confirm('Bạn có chắc chắn muốn xóa lịch hẹn này?')) { document.getElementById('delete-form').submit(); }">
                        <i class="icon icon-delete"></i>
                        Xóa lịch hẹn
                    </button>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary btn-lg">
                        <i class="icon icon-cancel"></i>
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="icon icon-save"></i>
                        Cập nhật lịch hẹn
                    </button>
                </div>
            </div>
        </form>

        {{-- Delete Form --}}
        <form id="delete-form"
              method="POST"
              action="{{ route('admin.appointments.delete', $appointment->id) }}"
              style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Auto calculate end time based on duration
    $('input[name="duration_minutes"]').on('change', function() {
        updateEndTime();
    });

    $('input[name="start_time"]').on('change', function() {
        updateEndTime();
    });

    function updateEndTime() {
        var startTime = $('input[name="start_time"]').val();
        var duration = parseInt($('input[name="duration_minutes"]').val());

        if (startTime && duration) {
            var start = new Date('2000-01-01 ' + startTime);
            var end = new Date(start.getTime() + duration * 60000);
            var endTime = end.toTimeString().slice(0, 5);

            console.log('Thời gian kết thúc dự kiến: ' + endTime);
        }
    }

    // Show/hide meeting link field based on meeting type
    $('select[name="meeting_type"]').on('change', function() {
        var meetingLinkField = $('input[name="meeting_link"]').closest('.form-group');

        if ($(this).val() === 'online') {
            meetingLinkField.show();
            meetingLinkField.find('label').addClass('required');
        } else {
            meetingLinkField.hide();
            meetingLinkField.find('label').removeClass('required');
        }
    });

    // Initialize on page load
    $(document).ready(function() {
        $('select[name="meeting_type"]').trigger('change');
    });
</script>
@endpush
